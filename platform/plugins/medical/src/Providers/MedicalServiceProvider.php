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


use Botble\Medical\Models\Nursing;
use Botble\Medical\Repositories\Caches\NursingCacheDecorator;
use Botble\Medical\Repositories\Eloquent\NursingRepository;
use Botble\Medical\Repositories\Interfaces\NursingInterface;

use Botble\Medical\Models\Maintenance;
use Botble\Medical\Repositories\Caches\MaintenanceCacheDecorator;
use Botble\Medical\Repositories\Eloquent\MaintenanceRepository;
use Botble\Medical\Repositories\Interfaces\MaintenanceInterface;

use Botble\Medical\Models\Consulting;
use Botble\Medical\Repositories\Caches\ConsultingCacheDecorator;
use Botble\Medical\Repositories\Eloquent\ConsultingRepository;
use Botble\Medical\Repositories\Interfaces\ConsultingInterface;

use Botble\Medical\Models\Examinations;
use Botble\Medical\Repositories\Caches\ExaminationsCacheDecorator;
use Botble\Medical\Repositories\Eloquent\ExaminationsRepository;
use Botble\Medical\Repositories\Interfaces\ExaminationsInterface;

use Botble\Medical\Models\Laboratories;
use Botble\Medical\Repositories\Caches\LaboratoriesCacheDecorator;
use Botble\Medical\Repositories\Eloquent\LaboratoriesRepository;
use Botble\Medical\Repositories\Interfaces\LaboratoriesInterface;

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
            return new PrescriptionCacheDecorator(new PrescriptionRepository(new Prescriptions));
        });
        $this->app->bind(SpecialtiesInterface::class, function () {
            return new SpecialtiesCacheDecorator(new SpecialtiesRepository(new Specialties));
        });
        $this->app->bind(DoctorInterface::class, function () {
            return new DoctorCacheDecorator(new DoctorRepository(new Doctors));
        });
        $this->app->bind(NursingInterface::class, function () {
            return new NursingCacheDecorator(new NursingRepository(new Nursing));
        });
        $this->app->bind(MaintenanceInterface::class, function () {
            return new MaintenanceCacheDecorator(new MaintenanceRepository(new Maintenance));
        });
        $this->app->bind(ConsultingInterface::class, function () {
            return new ConsultingCacheDecorator(new ConsultingRepository(new Consulting));
        });
        $this->app->bind(ExaminationsInterface::class, function () {
            return new ExaminationsCacheDecorator(new ExaminationsRepository(new Examinations));
        });
        $this->app->bind(LaboratoriesInterface::class, function () {
            return new LaboratoriesCacheDecorator(new LaboratoriesRepository(new Laboratories));
        });
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/medical')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes(['web'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
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
                'url'         => route('prescriptions.index'),
                'permissions' => ['prescriptions.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-medical-specialties',
                'priority'    => 3,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.specialties',
                'icon'        => "fas fa-book-medical",
                'url'         => route('specialties.index'),
                'permissions' => ['specialties.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-doctors',
                'priority'    => 4,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.doctors',
                'icon'        => "fas fa-user-md",
                'url'         => route('doctors.index'),
                'permissions' => ['doctors.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-nursing',
                'priority'    => 5,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.nursing',
                'icon'        => "fas fa-user-nurse",
                'url'         => route('nursing.index'),
                'permissions' => ['nursing.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-maintenance',
                'priority'    => 6,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.maintenance',
                'icon'        => "fas fa-tools",
                'url'         => route('maintenance.index'),
                'permissions' => ['maintenance.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-consulting',
                'priority'    => 7,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.consulting',
                'icon'        => "fas fa-comment-medical",
                'url'         => route('consulting.index'),
                'permissions' => ['consulting.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-medical-examinations',
                'priority'    => 8,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.examinations',
                'icon'        => "fas fa-microscope",
                'url'         => route('examinations.index'),
                'permissions' => ['examinations.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-medical-laboratories',
                'priority'    => 8,
                'parent_id'   => 'cms-plugins-medical',
                'name'        => 'plugins/medical::medical.laboratories',
                'icon'        => "fas fa-vial",
                'url'         => route('laboratories.index'),
                'permissions' => ['laboratories.index'],
            ]);
            
            
        });
        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
