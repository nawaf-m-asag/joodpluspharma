<?php

namespace Botble\Marketplace\Listeners;

use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Slug\Models\Slug;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SlugHelper;

class SaveVendorInformationListener
{
    /**
     * @var StoreInterface
     */
    public $storeRepository;

    /**
     * @var Request
     */
    protected $request;

    /**
     * SaveVendorInformationListener constructor.
     * @param StoreInterface $storeRepository
     * @param Request $request
     */
    public function __construct(StoreInterface $storeRepository, Request $request)
    {
        $this->storeRepository = $storeRepository;
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (get_class($event->user) == Customer::class && $this->request->input('is_vendor') == 1) {
            $store = $this->storeRepository->createOrUpdate([
                'name'        => $this->request->input('shop_name'),
                'phone'       => $this->request->input('shop_phone'),
                'customer_id' => $event->user->getAuthIdentifier(),
            ]);

            Slug::create([
                'reference_type' => Store::class,
                'reference_id'   => $store->id,
                'key'            => Str::slug($this->request->input('shop_url')),
                'prefix'         => SlugHelper::getPrefix(Store::class),
            ]);

            $event->user->is_vendor = true;

            $event->user->save();
        }
    }
}
