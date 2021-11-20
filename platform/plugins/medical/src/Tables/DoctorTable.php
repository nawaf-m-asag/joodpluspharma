<?php

namespace Botble\Medical\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Medical\Repositories\Interfaces\DoctorInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
class DoctorTable extends TableAbstract
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
     * @param DoctorInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DoctorInterface $doctorRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $doctorRepository;

        if (!Auth::user()->hasAnyPermission(['doctors.edit', 'doctors.destroy'])) {
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
                if (!Auth::user()->hasPermission('doctors.edit')) {
                    return $item->name;
                }
                return Html::link(route('doctors.edit', $item->id), $item->name);
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
            ->editColumn('specialty_id', function ($item) {
                return $item->specialty->name;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('doctors.edit', 'doctors.destroy', $item);
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
            'email',
            'phone',
            'address',
            'specialty_id',
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
                'title' =>trans('plugins/medical::medical.doctors'),
                'class' => 'text-left',
            ],
            'specialty_id' => [
                'title' =>trans('plugins/medical::medical.specialties'),
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
        return $this->addCreateButton(route('doctors.create'), 'doctors.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('doctors.deletes'), 'doctors.deletes', parent::bulkActions());
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
