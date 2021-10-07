<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use EcommerceHelper;
use Botble\Marketplace\Http\Requests\BecomeVendorRequest;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Marketplace\Repositories\Interfaces\VendorInfoInterface;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Botble\Slug\Models\Slug;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Theme;

class DashboardController
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var StoreInterface
     */
    protected $storeRepository;

    /**
     * @var VendorInfoInterface
     */
    protected $vendorInfoRepository;

    /**
     * @var RevenueInterface
     */
    protected $revenueRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * DashboardController constructor.
     * @param Repository $config
     * @param CustomerInterface $customerRepository
     * @param StoreInterface $storeRepository
     * @param VendorInfoInterface $vendorInfoRepository
     * @param RevenueInterface $revenueRepository
     * @param ProductInterface $productRepository
     * @param OrderInterface $orderRepository
     */
    public function __construct(
        Repository $config,
        CustomerInterface $customerRepository,
        StoreInterface $storeRepository,
        VendorInfoInterface $vendorInfoRepository,
        RevenueInterface $revenueRepository,
        ProductInterface $productRepository,
        OrderInterface $orderRepository
    ) {
        $this->storeRepository = $storeRepository;
        $this->customerRepository = $customerRepository;
        $this->vendorInfoRepository = $vendorInfoRepository;
        $this->orderRepository = $orderRepository;
        $this->revenueRepository = $revenueRepository;
        $this->productRepository = $productRepository;

        Assets::setConfig($config->get('plugins.marketplace.assets', []));

        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css');
        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery']);

        Theme::asset()
            ->container('footer')
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery']);
    }

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request, BaseHttpResponse $response, OrderInterface $order)
    {
        page_title()->setTitle(__('Dashboard'));

        Assets::addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.js',
                'vendor/core/plugins/ecommerce/libraries/apexcharts-bundle/dist/apexcharts.min.js',
                'vendor/core/plugins/ecommerce/js/report.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.css',
                'vendor/core/plugins/ecommerce/libraries/apexcharts-bundle/dist/apexcharts.css',
                'vendor/core/plugins/ecommerce/css/report.css',
            ])
            ->addScripts(['moment']);

        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport($request);

        $user = auth('customer')->user();
        $store = $user->store;
        $data = compact('startDate', 'endDate');

        $revenue = $this->revenueRepository
            ->getModel()
            ->select([
                DB::raw('SUM(mp_customer_revenues.sub_amount) as sub_amount'),
                DB::raw('COALESCE(SUM(mp_customer_revenues.amount), 0) - COALESCE(SUM(mp_customer_withdrawals.amount), 0) as amount'),
                DB::raw('SUM(mp_customer_withdrawals.amount) as withdrawal'),
                DB::raw('COALESCE(SUM(mp_customer_withdrawals.fee), 0) + COALESCE(SUM(mp_customer_revenues.fee), 0) as fee'),
            ])
            ->join('mp_customer_withdrawals', 'mp_customer_withdrawals.customer_id', 'mp_customer_revenues.customer_id')
            ->where('mp_customer_revenues.customer_id', $user->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('mp_customer_withdrawals.created_at', '>=', $startDate)
                ->whereDate('mp_customer_withdrawals.created_at', '<=', $endDate);
            })
            ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->whereDate('mp_customer_revenues.created_at', '>=', $startDate)
                ->whereDate('mp_customer_revenues.created_at', '<=', $endDate);
            })
            ->groupBy('mp_customer_revenues.customer_id')
            ->first();

        if (!$revenue) {
            $revenue = collect([
                'amount'     => 0,
                'fee'        => 0,
                'sub_amount' => 0,
                'withdrawal' => 0,
            ]);
        }

        $data['revenue'] = $revenue;
 
        $data['orders'] = $this->orderRepository
            ->getModel()
            ->select([
                'id',
                'status',
                'user_id',
                'created_at',
                'amount',
                'tax_amount',
                'shipping_amount',
                'payment_id',
            ])
            ->with(['user', 'payment'])
            ->where([
                'is_finished' => 1,
                'store_id'    => $store->id
            ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $data['products'] = $this->productRepository
            ->getModel()
            ->select([
                'id',
                'name',
                'order',
                'created_at',
                'status',
                'sku',
                'images',
                'price',
                'sale_price',
                'sale_type',
                'start_date',
                'end_date',
                'quantity',
                'with_storehouse_management',
            ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where([
                'status'       => BaseStatusEnum::PUBLISHED,
                'is_variation' => false,
                'store_id'     => $store->id
            ])
            ->limit(10)
            ->get();

        $totalProducts = $store->products()->count();
        $totalOrders = $store->orders()->count();
        $compact = compact('user', 'store', 'data', 'totalProducts', 'totalOrders');
        if ($request->ajax()) {
            return $response->setData([
                'html' => view('plugins/marketplace::themes.dashboard.partials.main-content', $compact)->render(),
            ]);
        }
        return view('plugins/marketplace::themes.dashboard.index', $compact);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|JsonResponse
     */
    public function postUpload(Request $request, BaseHttpResponse $response)
    {
        if (setting('media_chunk_enabled') != '1') {
            $validator = Validator::make($request->all(), [
                'file.0' => 'required|image|mimes:jpg,jpeg,png',
            ]);

            if ($validator->fails()) {
                return $response->setError()->setMessage($validator->getMessageBag()->first());
            }

            $result = RvMedia::handleUpload(Arr::first($request->file('file')), 0, 'customers');

            if ($result['error']) {
                return $response->setError(true)->setMessage($result['message']);
            }

            return $response->setData($result['data']);
        }

        try {
            // Create the file receiver
            $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
            // Check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException;
            }
            // Receive the file
            $save = $receiver->receive();
            // Check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                $result = RvMedia::handleUpload($save->getFile(), 0, 'accounts');

                if ($result['error'] == false) {
                    return $response->setData($result['data']);
                }

                return $response->setError(true)->setMessage($result['message']);
            }
            // We are in chunk mode, lets send the current progress
            $handler = $save->handler();
            return response()->json([
                'done'   => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $exception) {
            return $response->setError(true)->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws FileNotFoundException
     */
    public function postUploadFromEditor(Request $request)
    {
        return RvMedia::uploadFromEditor($request);
    }

    /**
     * @return Response
     */
    public function getBecomeVendor()
    {
        SeoHelper::setTitle(__('Become Vendor'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Become Vendor'), route('marketplace.vendor.become-vendor'));

        return Theme::scope('marketplace.become-vendor', [], 'plugins/marketplace::themes.become-vendor')->render();
    }

    /**
     * @param BecomeVendorRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function postBecomeVendor(BecomeVendorRequest $request, BaseHttpResponse $response)
    {
        $existing = SlugHelper::getSlug($request->input('shop_url'), SlugHelper::getPrefix(Store::class), Store::class);

        if ($existing) {
            return $response->setError()->setMessage(__('Shop URL is existing. Please choose another one!'));
        }

        $store = $this->storeRepository->createOrUpdate([
            'name'        => $request->input('shop_name'),
            'phone'       => $request->input('shop_phone'),
            'customer_id' => auth('customer')->id(),
        ]);

        Slug::create([
            'reference_type' => Store::class,
            'reference_id'   => $store->id,
            'key'            => Str::slug($request->input('shop_url')),
            'prefix'         => SlugHelper::getPrefix(Store::class),
        ]);

        $customer = auth('customer')->user();

        $customer->is_vendor = true;

        if (!$customer->vendorInfo->id) {
            // Create vendor info
            $this->vendorInfoRepository->createOrUpdate([
                'customer_id' => $customer->id,
            ]);
        }

        $this->customerRepository->createOrUpdate($customer);

        return $response->setNextUrl(route('marketplace.vendor.dashboard'))->setMessage(__('Registered successfully!'));
    }
}
