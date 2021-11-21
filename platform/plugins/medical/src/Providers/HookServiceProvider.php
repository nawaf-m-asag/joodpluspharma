<?php

namespace Botble\Medical\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Medical\Repositories\Interfaces\PrescriptionInterface;
use Botble\Medical\Repositories\Interfaces\NursingInterface;
use Botble\Medical\Repositories\Interfaces\MaintenanceInterface;
use Botble\Medical\Repositories\Interfaces\ConsultingInterface;
use Botble\Medical\Repositories\Interfaces\ExaminationsInterface;
use Html;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
        $this->app->booted(function () {
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getPrescriptionCountPendingHtml'], 120, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getPrescriptionCountPending'], 120);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getNursingCountPendingHtml'], 120, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getNursingCountPending'], 120);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getMaintenanceCountPendingHtml'], 120, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getMaintenanceCountPending'], 120);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getConsultingCountPendingHtml'], 120, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getConsultingCountPending'], 120);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getExaminationsCountPendingHtml'], 120, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getExaminationsCountPending'], 120);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getCountPendingHtml'], 120, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getCountPending'], 120);
        });
    }

    /**
     * @param string $options
     * @return string
     *
     * @throws \Throwable
     */

    /**
     * @param int $number
     * @param string $menuId
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPrescriptionCountPendingHtml($number, $menuId)
    {
        if ( $menuId == 'cms-plugins-medical-prescriptions' ) {
            $attributes = [
                'class'    => 'badge badge-success left menu-item-count pending-prescriptions',
                'style'    => 'display: none; left:20px; right:unset',
            ];

            return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }


    /**
     * @param array $data
     * @return array
     */
    public function getPrescriptionCountPending(array $data = []) : array
    {
        if (Auth::user()->hasPermission('prescriptions.index')) {
            $data[] = [
                'key'   => 'pending-prescriptions',
                'value' => app(PrescriptionInterface::class)->count([
                    'status'      => BaseStatusEnum::PENDING,
                ]),
            ];
        }

        return $data;
    }

    public function getNursingCountPendingHtml($number, $menuId)
    {
        if ($menuId == 'cms-plugins-medical-nursing' ) {
            $attributes = [
                'class'    => 'badge badge-success left menu-item-count pending-nursing',
                'style'    => 'display: none; left:20px; right:unset',
            ];

            return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }


    /**
     * @param array $data
     * @return array
     */
    public function getNursingCountPending(array $data = []) : array
    {
        if (Auth::user()->hasPermission('nursing.index')) {
            $data[] = [
                'key'   => 'pending-nursing',
                'value' => app(NursingInterface::class)->count([
                    'status'      => BaseStatusEnum::PENDING,
                ]),
            ];
        }

        return $data;
    }

    public function getMaintenanceCountPendingHtml($number, $menuId)
    {
        if ($menuId == 'cms-plugins-medical-maintenance' ) {
            $attributes = [
                'class'    => 'badge badge-success left menu-item-count pending-maintenance',
                'style'    => 'display: none; left:20px; right:unset',
            ];

            return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }


    /**
     * @param array $data
     * @return array
     */
    public function getMaintenanceCountPending(array $data = []) : array
    {
        if (Auth::user()->hasPermission('maintenance.index')) {
            $data[] = [
                'key'   => 'pending-maintenance',
                'value' => app(MaintenanceInterface::class)->count([
                    'status'      => BaseStatusEnum::PENDING,
                ]),
            ];
        }

        return $data;
    }


    public function getConsultingCountPendingHtml($number, $menuId)
    {
        if ($menuId == 'cms-plugins-medical-consulting' ) {
            $attributes = [
                'class'    => 'badge badge-success left menu-item-count pending-consulting',
                'style'    => 'display: none; left:20px; right:unset',
            ];

            return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }


    /**
     * @param array $data
     * @return array
     */
    public function getConsultingCountPending(array $data = []) : array
    {
        if (Auth::user()->hasPermission('consulting.index')) {
            $data[] = [
                'key'   => 'pending-consulting',
                'value' => app(ConsultingInterface::class)->count([
                    'status'      => BaseStatusEnum::PENDING,
                ]),
            ];
        }

        return $data;
    }

    public function getExaminationsCountPendingHtml($number, $menuId)
    {
        if ($menuId == 'cms-plugins-medical-examinations' ) {
            $attributes = [
                'class'    => 'badge badge-success left menu-item-count pending-examinations',
                'style'    => 'display: none; left:20px; right:unset',
            ];

            return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }


    /**
     * @param array $data
     * @return array
     */
    public function getExaminationsCountPending(array $data = []) : array
    {
        if (Auth::user()->hasPermission('examinations.index')) {
            $data[] = [
                'key'   => 'pending-examinations',
                'value' => app(ExaminationsInterface::class)->count([
                    'status'      => BaseStatusEnum::PENDING,
                ]),
            ];
        }

        return $data;
    }
    public function getCountPendingHtml($number, $menuId)
    {
        if ($menuId == 'cms-plugins-medical' ) {
            $attributes = [
                'class'    => 'badge badge-success left menu-item-count pending-all',
                'style'    => 'display: none;',
            ];

            return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }


    /**
     * @param array $data
     * @return array
     */
    public function getCountPending(array $data = []) : array
    {
        if (Auth::user()->hasPermission('medical.index')) {
            $data[] = [
                'key'   => 'pending-all',
                'value' => 
                 app(ExaminationsInterface::class)->count(['status'      => BaseStatusEnum::PENDING, ])+
                 app(ConsultingInterface::class)->count(['status'      => BaseStatusEnum::PENDING, ])+
                 app(NursingInterface::class)->count(['status'      => BaseStatusEnum::PENDING, ])+
                 app(PrescriptionInterface::class)->count(['status'      => BaseStatusEnum::PENDING, ])+
                 app(MaintenanceInterface::class)->count(['status'      => BaseStatusEnum::PENDING, ]),
            ];
        }

        return $data;
    }
  
  
}
