<?php

namespace Botble\App_setting\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\App_setting\Http\Requests\App_settingRequest;
use Botble\App_setting\Models\App_setting;

class App_settingForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        
          $this->setupModel(new App_setting)
            ->setValidatorClass(App_settingRequest::class)
            ->withCustomFields()
            ->add('privacy_policy', 'editor', [
                'label'      => trans('plugins/app_setting::app_setting.privacy_policy'),
                'label_attr' => ['class' => 'control-label'],
               
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('plugins/app_setting::app_setting.privacy_policy'),
                    'with-short-code' => true,
                ],
                'default_value'=>$this->getSetting('privacy_policy')
            ])
            ->add('terms_conditions', 'editor', [
                'label'      => trans('plugins/app_setting::app_setting.terms_conditions'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('plugins/app_setting::app_setting.terms_conditions'),
                    'with-short-code' => true,
                ],
                'default_value'=>$this->getSetting('terms_conditions')
            ])
            ->add('contact_us', 'editor', [
                'label'      => trans('plugins/app_setting::app_setting.contact_us'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('plugins/app_setting::app_setting.contact_us'),
                    'with-short-code' => true,
                ],
                'default_value'=>$this->getSetting('contact_us')
            ])
            ->add('about_us', 'editor', [
                'label'      => trans('plugins/app_setting::app_setting.about_us'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('plugins/app_setting::app_setting.about_us'),
                    'with-short-code' => true,
                ],
                'default_value'=>$this->getSetting('about_us')
            ])
            ->add('fcm_server_key', 'text', [
                'label'      => trans('plugins/app_setting::app_setting.fcm_server_key'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('plugins/app_setting::app_setting.fcm_server_key'),
                    'with-short-code' => true,
                ],
                'default_value'=>$this->getSetting('fcm_server_key')
            ])
            ->add('is_time_slots_enabled', 'onOff', [
                'label'         => trans('plugins/app_setting::app_setting.is_time_slots_enabled'),
                'label_attr'    => ['class' => 'control-label '],
                'default_value' => $this->getSetting('time_slot_config','is_time_slots_enabled'),
            ]) ->add('time_slot_config', 'onOff', [
                'label'         => trans('plugins/app_setting::app_setting.time_slot_config'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getSetting('time_slot_config','time_slot_config'),
            ])
            ->add('delivery_starts_from', 'select', [
                'label'      => trans('plugins/app_setting::app_setting.delivery_starts_from'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ['1'=>'Today','2'=>'Tomorrow','3'=>'Third Day','4'=>'Fourth Day','5'=>'Fifth Day','6'=>'Sixth Day','7'=>'Seventh Day'],
                'default_value' =>$this->getSetting('time_slot_config','delivery_starts_from'),
            ])->add('allowed_days', 'select', [
                'label'      => trans('plugins/app_setting::app_setting.delivery_starts_from'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ['1'=>'1','7'=>'7','15'=>'15','30'=>'30'],
                'default_value' =>$this->getSetting('time_slot_config','allowed_days'),
            ]);
           
            
            
    }

    public function getSetting($variable,$lavalTow=null)
    {
        $setting=App_setting::select('value')->where('variable',$variable)->get();
        if($lavalTow!=null&&$variable=='time_slot_config'){
            $json=json_decode($setting[0]->value);
            return $json->$lavalTow;
        }
        return isset($setting[0]->value)?$setting[0]->value:null;
    }
}
