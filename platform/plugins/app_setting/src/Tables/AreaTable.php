<?php

namespace Botble\App_setting\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;

use Botble\App_setting\Repositories\Interfaces\AreaInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class AreaTable extends TableAbstract
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
     * areaTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param AreaInterface $areaRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AreaInterface $areaRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $areaRepository;

        if (!Auth::user()->hasAnyPermission(['area.edit', 'area.destroy'])) {
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
                if (!Auth::user()->hasPermission('area.edit')) {
                    return $item->name;
                }
                return Html::link(route('area.edit', $item->id), $item->name);
            })
            ->editColumn('city_id', function ($item) {
                return $item->City->name;
            })
            
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            
            ->addColumn('operations', function ($item) {
                return $this->getOperations('area.edit', 'area.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
               'id',
               'name',
               'city_id'
               
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
                'title' => trans('plugins/app_setting::app_setting.area_name'),
                'class' => 'text-left',
            ],
            'city_id' => [
                'title' => trans('plugins/app_setting::app_setting.city'),
                'class' => 'text-left',
            ],
            
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('area.create'), 'area.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('area.deletes'), 'area.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
   

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
