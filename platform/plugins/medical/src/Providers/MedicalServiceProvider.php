<?php

namespace Botble\Medical\Providers;


use Illuminate\Support\ServiceProvider;

use Botble\Medical\Models\Services;
use Botble\Medical\Repositories\Caches\ServiceCacheDecorator;
use Botble\Medical\Repositories\Eloquent\ServiceRepository;
use Botble\Medical\Repositories\Interfaces\ServiceInterface;

    use Botble\Medical\Models\Prescriptions;
use Botble\Medical\Repositories\Caches\PrescriptionCacheDecorator;
use Botble\Medical\Repositories\Eloquent\PrescriptionRepository;
use Botble\Medical\Repositories\Interfaces\PrescriptionInterface;

use Botble\Medical\Models\Specialties;
use Botble\Medical\Repositories\Caches\SpecialtiesCacheDecorator;
use Botble\Medical\Repositories\Eloquent\SpecialtiesRepository;
use Botble\Medical\Repositories\Interfaces\SpecialtiesInterface;

use Botble\Medical\Models\Doctors;
use Botble\Medical\Repositories\Caches\DoctorCacheDecorator;
use Botble\Medical\Repositories\Eloquent\DoctorRepository;
use Botble\Medical\Repositories\Interfaces\DoctorInterface;


use Botble\Base\Supports\Helper;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

use Botble\Setting\Supports\SettingStore;
use Botble\SocialLogin\Facades\SocialServiceFacade;
use Illuminate\Foundation\AliasLoader;

class MedicalServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(ServiceInterface::class, function () {
            return new ServiceCacheDecorator(new ServiceRepository(new Services));
        });
        $this->app->bind(PrescriptionInterface::class, function () {
            return new PrescriptionCacheDecorator(new PrescriptionRepository(new PrescriptionS));
        });
        $this->app->bind(SpecialtiesInterface::class, function () {
            return new SpecialtiesCacheDecorator(new SpecialtiesRepository(new Specialties));
        });
        $this->app->bind(DoctorInterface::class, function () {
            return new DoctorCacheDecorator(new DoctorRepository(new Doctors));
        });
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/medical')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes(['web'])
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([Medical::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-medical',
                'priority'    => 8,
                'parent_id'   => null,
                'name'        => 'plugins/medical::medical.name',
                'icon'        => 'fas fa-stethoscope',
                'url'         => null,
                'permissions' => ['medical.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-services',
                'priority'    => 1,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.services',
                'icon'        => "fas fa-briefcase-medical",
                'url'         => route('service.index'),
                'permissions' => ['services.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-medical-prescriptions',
                'priority'    => 2,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.prescriptions',
                'icon'        => "fas fa-prescription-bottle-alt",
                'url'         => route('prescription.index'),
                'permissions' => ['prescriptions.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-medical-specialties',
                'priority'    => 2,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.specialties',
                'icon'        => "fas fa-book-medical",
                'url'         => route('specialties.index'),
                'permissions' => ['specialties.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-doctors',
                'priority'    => 2,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.doctors',
                'icon'        => "fas fa-user-md",
                'url'         => route('doctors.index'),
                'permissions' => ['doctors.index'],
            ]);
            
        });
    }
}
