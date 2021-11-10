<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\ServiceInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
class ServiceTable extends TableAbstract
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
     * @param ServiceInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ServiceInterface $serviceRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $serviceRepository;

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
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('services.edit')) {
                    return $item->name;
                }
                return Html::link(route('service.edit', $item->id), $item->name);
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
            'name',
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
            'name' => [
                'title' =>trans('plugins/medical::medical.service-name'),
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
        return $this->addCreateButton('services/create', 'services.create');
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
            'name' => [
                'title'    => trans('plugins/medical::medical.service-name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
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
