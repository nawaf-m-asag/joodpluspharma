<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\ConsultingInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use Botble\Ecommerce\Enums\OrderStatusEnum;
class ConsultingTable extends TableAbstract
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
     * @param ConsultingInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ConsultingInterface $consultingRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $consultingRepository;

        if (!Auth::user()->hasAnyPermission(['consulting.edit', 'consulting.destroy'])) {
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
            ->editColumn('p_name', function ($item) {
                return Html::link(route('consulting.details', $item->id), $item->p_name);
            })

            ->editColumn('con_type', function ($item) {
                return $item->con_type;
            })
            ->editColumn('specialty_id', function ($item) {
                return $item->specialty->name;
            })
            ->editColumn('doctor_id', function ($item) {
                return $item->doctor->name;
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
                return $this->getOperations(null, 'consulting.destroy', $item);
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
            'specialty_id',
            'con_type',
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
            'con_type' => [
                'title' =>trans('plugins/medical::medical.con_type'),
                'class' => 'text-left',
            ],
            'doctor_id' => [
                'title' =>trans('plugins/medical::medical.doctor-name'),
                'class' => 'text-left',
            ],
            'specialty_id' => [
                'title' =>trans('plugins/medical::medical.specialties-name'),
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
        return $this->addDeleteAction(route('consulting.deletes'), 'consulting.deletes', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'p_name' => [
                'title'    => trans('plugins/medical::medical.patient-name'),
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
