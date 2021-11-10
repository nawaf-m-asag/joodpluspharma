<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\SpecialtiesInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
class SpecialtiesTable extends TableAbstract
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
     * @param SpecialtiesInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, SpecialtiesInterface $SpecialtiesRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $SpecialtiesRepository;

        if (!Auth::user()->hasAnyPermission(['specialties.edit', 'specialties.destroy'])) {
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
                if (!Auth::user()->hasPermission('specialties.edit')) {
                    return $item->name;
                }
                return Html::link(route('specialties.edit', $item->id), $item->name);
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
                return $this->getOperations('specialties.edit', 'specialties.destroy', $item);
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
    public function buttons()
    {
        return $this->addCreateButton(route('specialties.create'), 'specialties.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('specialties.deletes'), 'specialties.deletes', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title'    => trans('plugins/medical::medical.specialties-name'),
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
