<?php

namespace Botble\App_setting\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\App_setting\Repositories\Interfaces\TimeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class TimeTable extends TableAbstract
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
     * CityTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TimeInterface $timeRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, TimeInterface $timeRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $timeRepository;

        if (!Auth::user()->hasAnyPermission(['time.edit', 'time.destroy'])) {
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
            ->editColumn('title', function ($item) {
                if (!Auth::user()->hasPermission('time.edit')) {
                    return $item->title;
                }
                return Html::link(route('time.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('from_time', function ($item) {
                return $item->from_time;
            })
            ->editColumn('to_time', function ($item) {
                return $item->to_time;
            })
            ->editColumn('last_order_time', function ($item) {
                return $item->last_order_time;
            })
            ->editColumn('from_time', function ($item) {
                return $item->from_time;
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('time.edit', 'time.destroy', $item);
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
               'title',
               'from_time',
               'to_time',
               'last_order_time',
               'from_time',
            
               
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
            'title' => [
                'title' => trans('plugins/app_setting::app_setting.time_title'),
                'class' => 'text-left',
            ],
            'from_time' => [
                'title' => trans('plugins/app_setting::app_setting.from_time'),
                'class' => 'text-left',
            ],
            'to_time' => [
                'title' => trans('plugins/app_setting::app_setting.to_time'),
                'class' => 'text-left',
            ],
            'last_order_time' => [
                'title' => trans('plugins/app_setting::app_setting.last_order_time'),
                'class' => 'text-left',
            ],
            'from_time' => [
                'title' => trans('plugins/app_setting::app_setting.from_time'),
                'class' => 'text-left',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('time.create'), 'time.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('time.deletes'), 'time.destroy', parent::bulkActions());
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
