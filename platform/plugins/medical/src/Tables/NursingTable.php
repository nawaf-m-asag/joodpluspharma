<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\NursingInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use RvMedia;
use Botble\Ecommerce\Enums\OrderStatusEnum;
class NursingTable extends TableAbstract
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
     * @param  NursingInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, NursingInterface $nursingRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $nursingRepository;

        if (!Auth::user()->hasAnyPermission(['nursing.edit', 'nursing.destroy'])) {
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
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('p_name', function ($item) {
                return Html::link(route('nursing.details', $item->id), $item->p_name);
                
            })
            ->editColumn('services_id', function ($item) {
                return $item->getAllSelectedServes();
            })
            ->editColumn('doctor_id', function ($item) {
                return $item->doctor->name;
            })
            ->editColumn('attachedFile', function ($item) {
                $color=empty($item->attachedFile)?"btn-warning":"btn-success";
                $download=empty($item->attachedFile)?"":"download";
                return '<a '.$download.' href="'.$item->attachedFile.'" class="btn '.$color.' pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations(null, 'nursing.destroy',$item);
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
            'p_name',
            'doctor_id',
            'services_id',
            'attachedFile',
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
            'p_name' => [
                'title' =>trans('plugins/medical::medical.patient-name'),
                'class' => 'text-left',
            ],
            'doctor_id' => [
                'title' =>trans('plugins/medical::medical.doctor-name'),
                'class' => 'text-left',
            ],
            'services_id' => [
                'title' =>trans('plugins/medical::medical.services'),
                'class' => 'text-left',
            ],
            'attachedFile' => [
                'title' => trans('plugins/medical::medical.file'),
                'width' => '100px',
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
    public function bulkActions(): array
    {
        return $this->addDeleteAction('nursing.deletes', 'nursing.deletes', parent::bulkActions());
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
