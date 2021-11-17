<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\MaintenanceInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use Botble\Ecommerce\Enums\OrderStatusEnum;
class MaintenanceTable extends TableAbstract
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
     * @param MaintenanceInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MaintenanceInterface $maintenanceRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $maintenanceRepository;

        if (!Auth::user()->hasAnyPermission(['maintenance.edit', 'maintenance.destroy'])) {
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
            ->editColumn('side_name', function ($item) {
            
                return Html::link(route('maintenance.details', $item->id), $item->side_name);
            })

            ->editColumn('applicant_name', function ($item) {
                return $item->side_name;
            })
            ->editColumn('file', function ($item) {
                $color=empty($item->file)?"btn-warning":"btn-success";
                $download=empty($item->file)?"":"download";
                return '<a '.$download.' href="'.$item->file.'" class="btn '.$color.' pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i></a>';
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('service.edit', 'service.destroy', $item);
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
            'side_name',
            'applicant_name',
            'device_name',
            'file',
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
            'side_name' => [
                'title' =>trans('plugins/medical::medical.side-name'),
                'class' => 'text-left',
            ],
            'applicant_name' => [
                'title' =>trans('plugins/medical::medical.applicant-name'),
                'class' => 'text-left',
            ],
            'device_name' => [
                'title' =>trans('plugins/medical::medical.device-name'),
                'class' => 'text-left',
            ],
            'file' => [
                'title' =>trans('plugins/medical::medical.file'),
                'class' => 'text-left',
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

    /**
     * {@inheritDoc}
     */
 
    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('maintenance.deletes'), 'maintenance.deletes', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'applicant_name' => [
                'title'    => trans('plugins/medical::medical.applicant-name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'device_name' => [
                'title'    => trans('plugins/medical::medical.device-name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
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
