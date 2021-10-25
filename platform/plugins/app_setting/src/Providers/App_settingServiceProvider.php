<?php

namespace Botble\App_setting\Providers;

use Botble\App_setting\Models\City;
use Botble\App_setting\Models\Area;
use Botble\App_setting\Models\Time;
use Illuminate\Support\ServiceProvider;

use Botble\App_setting\Repositories\Caches\CityCacheDecorator;
use Botble\App_setting\Repositories\Eloquent\CityRepository;
use Botble\App_setting\Repositories\Interfaces\CityInterface;

use Botble\App_setting\Repositories\Caches\AreaCacheDecorator;
use Botble\App_setting\Repositories\Eloquent\AreaRepository;
use Botble\App_setting\Repositories\Interfaces\AreaInterface;

use Botble\App_setting\Repositories\Caches\TimeCacheDecorator;
use Botble\App_setting\Repositories\Eloquent\TimeRepository;
use Botble\App_setting\Repositories\Interfaces\TimeInterface;


use Botble\Base\Supports\Helper;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class App_settingServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(CityInterface::class, function () {
            return new CityCacheDecorator(new CityRepository(new City));
        });
        $this->app->bind(AreaInterface::class, function () {
            return new AreaCacheDecorator(new AreaRepository(new Area));
        });
        $this->app->bind(TimeInterface::class, function () {
            return new TimeCacheDecorator(new TimeRepository(new Time));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }
    

    public function boot()
    {
        $this->setNamespace('plugins/app_setting')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([App_setting::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-app_setting',
                'priority'    => 997,
                'parent_id'   => null,
                'name'        => 'plugins/app_setting::app_setting.name',
                'icon'        => 'fas fa-cog',
                'url'         => null,
                'permissions' => ['app_setting.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-app_setting-cities',
                'priority'    => 3,
                'parent_id'   => "cms-plugins-app_setting",
                'name'        => 'plugins/app_setting::app_setting.cities',
                'icon'        => null,
                'url'         => route('city.index'),
                'permissions' => ['city.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-app_setting-areas',
                'priority'    => 2,
                'parent_id'   => "cms-plugins-app_setting",
                'name'        => 'plugins/app_setting::app_setting.areas',
                'icon'        => null,
                'url'         => route('area.index'),
                'permissions' => ['area.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-app_setting-areas',
                'priority'    => 1,
                'parent_id'   => "cms-plugins-app_setting",
                'name'        => 'plugins/app_setting::app_setting.app_setting',
                'icon'        => null,
                'url'         => route('app_setting.get_settings'),
                'permissions' => ['app_setting.index'],
            ]) ->registerItem([
                'id'          => 'cms-plugins-app_setting-times',
                'priority'    => 3,
                'parent_id'   => "cms-plugins-app_setting",
                'name'        => 'plugins/app_setting::app_setting.times',
                'icon'        => null,
                'url'         => route('time.index'),
                'permissions' => ['time.index'],
            ]);
        });
    }
}
