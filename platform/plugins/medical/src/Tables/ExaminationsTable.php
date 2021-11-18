<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\ExaminationsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use Botble\Ecommerce\Enums\OrderStatusEnum;
class ExaminationsTable extends TableAbstract
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
     * @param ExaminationsInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ExaminationsInterface $examinationsRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $examinationsRepository;

        if (!Auth::user()->hasAnyPermission(['examinations.edit', 'examinations.destroy'])) {
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
                return Html::link(route('examinations.details', $item->id), $item->p_name);
            })

            ->editColumn('lap_name', function ($item) {
                return $item->lap_name;
            })
            ->editColumn('d_name', function ($item) {
                return $item->d_name;
            })
            ->editColumn('user_id', function ($item) {
                return $item->user->name;
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
            'user_id',
            'lap_name',
            'd_name',
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
            'd_name' => [
                'title' =>trans('plugins/medical::medical.doctor-name'),
                'class' => 'text-left',
            ],
            'lap_name' => [
                'title' =>trans('plugins/medical::medical.doctor-name'),
                'class' => 'text-left',
            ],
            'user_id' => [
                'title' =>trans('plugins/medical::medical.user-name'),
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
        return $this->addDeleteAction(route('examinations.deletes'), 'examinations.deletes', parent::bulkActions());
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
