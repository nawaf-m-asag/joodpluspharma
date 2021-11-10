<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\PrescriptionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use RvMedia;
use Botble\Ecommerce\Enums\OrderStatusEnum;
class PrescriptionTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * notificationTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PrescriptionInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PrescriptionInterface $prescriptionRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $prescriptionRepository;

        if (!Auth::user()->hasAnyPermission(['services.edit', 'services.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('user_id', function ($item) {
                return $item->user->name ?? $item->address->name;
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('address', function ($item) {
                return $item->getFullAddressAttribute();
            })
            ->editColumn('notes', function ($item) {
                return $item->notes;
            })
            ->editColumn('file', function ($item) {
                $color=empty($item->file)?"btn-warning":"btn-success";
                $download=empty($item->file)?"":"download";
                return '<a '.$download.' href="'.$item->file.'" class="btn '.$color.' pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i></a>';
            })
            ->editColumn('image_file', function ($item) {
                $url=RvMedia::getImageUrl($item->image_file, null, false, RvMedia::getDefaultImage());
                $data=Html::image(RvMedia::getImageUrl($item->image_file, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->user_id, ['width' => 50]);
                return "<a href=$url>$data</a>";
                
             })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations(null, 'service.destroy',$item);
            });

            

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'notes',
            'address_id',
            'image_file',
            'file',
            'user_id',
            'created_at',
            'status'
        ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'user_id' => [
                'title' =>trans('plugins/medical::medical.user-name'),
                'class' => 'text-left',
            ],
            'address' => [
                'title' =>trans('plugins/medical::medical.address'),
                'class' => 'text-left',
            ],
            'notes' => [
                'title' =>trans('plugins/medical::medical.note'),
                'class' => 'text-left',
            ],
            'file' => [
                'title' => trans('plugins/medical::medical.file'),
                'width' => '100px',
            ],
            'image_file' => [
                'title' => trans('core/base::tables.image'),
                'width' => '80px',
            ],
            'status'      => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ]
 
        ];
    }

    public function buttons()
    {
        return $this->addCreateButton(route('orders.create'), 'orders.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction('services.deletes', 'services.deletes', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => OrderStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', OrderStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
