<?php

namespace Botble\Notification\Providers;

use Botble\Notification\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Botble\Notification\Repositories\Caches\NotificationCacheDecorator;
use Botble\Notification\Repositories\Eloquent\NotificationRepository;
use Botble\Notification\Repositories\Interfaces\NotificationInterface;
use Botble\Base\Supports\Helper;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;





use Botble\Setting\Supports\SettingStore;
use Botble\SocialLogin\Facades\SocialServiceFacade;
use Illuminate\Foundation\AliasLoader;

class NotificationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(NotificationInterface::class, function () {
            return new NotificationCacheDecorator(new NotificationRepository(new Notification));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/notification')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes(['web'])
            ->loadAndPublishTranslations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([Notification::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-notification',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/notification::notification.name',
                'icon'        => 'fas fa-paper-plane',
                'url'         => null,
                'permissions' => ['notification.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-notification-all-user',
                'priority'    => 1,
                'parent_id'   => 'cms-plugins-notification',
                'name'        => 'plugins/notification::notification.all-user',
                'icon'        => null,
                'url'         => route('notification.index'),
                'permissions' => ['notification.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-notification-one-user',
                'priority'    => 2,
                'parent_id'   => 'cms-plugins-notification',
                'name'        => 'plugins/notification::notification.one-user',
                'icon'        => null,
                'url'         => route('notification.one-notification'),
                'permissions' => ['notification.index'],
            ]);;;
        });
    }
}
