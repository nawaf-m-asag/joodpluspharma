<?php

namespace Botble\App_setting\Forms;

use Botble\Base\Forms\FormAbstract;

use Botble\App_setting\Http\Requests\TimeRequest;
use Botble\App_setting\Models\Time;
use Illuminate\Support\Arr;
class TimeForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        
        $this
            ->setupModel(new Time)
            ->setValidatorClass(TimeRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label'      => trans('plugins/app_setting::app_setting.time_title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/app_setting::app_setting.time_title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('from_time', 'time', [
                'label'      => trans('plugins/app_setting::app_setting.from_time'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  =>trans('plugins/app_setting::app_setting.from_time'),
                   
                ],
            ])
            ->add('to_time', 'time', [
                'label'      => trans('plugins/app_setting::app_setting.to_time'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  =>trans('plugins/app_setting::app_setting.to_time'),
                   
                ],
            ])
            ->add('last_order_time', 'time', [
                'label'         => trans('plugins/app_setting::app_setting.last_order_time'),
                'label_attr'    => ['class' => 'control-label required'],
                'attr'          => [
                    'placeholder' =>trans('plugins/app_setting::app_setting.last_order_time'),
                ],
               
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ['0'=>'draft','1'=>'publish'],
            ])
            ->setBreakFieldPoint('status');
            ;
           

            
    }
}
