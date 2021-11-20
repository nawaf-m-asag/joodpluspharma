<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\LaboratoriesInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use Botble\Base\Enums\BaseStatusEnum;
class LaboratoriesTable extends TableAbstract
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
     * @param LaboratoriesInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, LaboratoriesInterface $laboratoriesRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $laboratoriesRepository;

        if (!Auth::user()->hasAnyPermission(['laboratories.edit', 'laboratories.destroy'])) {
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
            ->editColumn('lab_name', function ($item) {
                if (!Auth::user()->hasPermission('laboratories.edit')) {
                    return $item->name;
                }
                return Html::link(route('laboratories.edit', $item->id), $item->lab_name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('email', function ($item) {
                return $item->email;
            })
            ->editColumn('phone', function ($item) {
                return $item->phone;
            })
            ->editColumn('address', function ($item) {
                return $item->address;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('laboratories.edit', 'laboratories.destroy', $item);
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
            'lab_name',
            'email',
            'phone',
            'address',
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
            'lab_name' => [
                'title' =>trans('plugins/medical::medical.lab_name'),
                'class' => 'text-left',
            ],
            'address' => [
                'title' =>trans('plugins/medical::medical.address'),
                'class' => 'text-left',
            ],
            'phone' => [
                'title' =>trans('plugins/medical::medical.phone'),
                'class' => 'text-left',
            ],
            'email' => [
                'title' =>trans('plugins/medical::medical.email'),
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
    public function buttons()
    {
        return $this->addCreateButton(route('laboratories.create'), 'laboratories.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('laboratories.deletes'), 'laboratories.deletes', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title'    => trans('plugins/medical::medical.lab_name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
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
