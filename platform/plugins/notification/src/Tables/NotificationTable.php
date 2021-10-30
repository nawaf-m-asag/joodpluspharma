<?php

namespace Botble\Notification\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Notification\Repositories\Interfaces\NotificationInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use RvMedia;
class NotificationTable extends TableAbstract
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
     * @param notificationInterface ${+name}Repository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, NotificationInterface $notificationRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $notificationRepository;

        if (!Auth::user()->hasAnyPermission(['notification.edit', 'notification.destroy'])) {
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
            ->editColumn('image', function ($item) {
               return Html::image(RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()),
                   $item->title, ['width' => 100]);
            })
            ->editColumn('title', function ($item) {
                if (!Auth::user()->hasPermission('notification.edit')) {
                    return $item->title;
                }
                return Html::link(route('notification.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('date_sent', function ($item) {
                return BaseHelper::formatDate($item->date_sent);
            })
            
            ->addColumn('operations', function ($item) {
                return $this->getOperations('notification.edit', 'notification.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'notifications.id',
            'notifications.title',
            'notifications.type',
            'notifications.type_id',
            'notifications.image',
            'notifications.date_sent',
            'notifications.message'
        ];

        $query = $model->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'name'  => 'notifications.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name'  => 'notifications.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'message' => [
                'name'  => 'notifications.message',
                'title' => trans('plugins/notification::notification.message'),
                'class' => 'text-left',
            ],
            'type' => [
                'name'  => 'notifications.type',
                'title' => trans('plugins/notification::notification.type'),
                'class' => 'text-left',
            ],
            'date_sent' => [
                'name'  => 'notifications.date_sent',
                'title' =>trans('plugins/notification::notification.date_sent'),
                'class' => 'text-left',
            ],
            'image' => [
                'name'  => 'notifications.image',
                'title' => trans('core/base::tables.image'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton('notification/create', 'notification.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('notification.deletes'), 'notification.deletes', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'notifications.title' => [
                'title'    => trans('core/base::tables.title'),
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
