<?php

namespace Botble\App_setting\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\App_setting\Http\Requests\App_settingRequest;
use Illuminate\Http\Request;
use Botble\App_setting\Models\App_setting;
use Botble\App_setting\Forms\App_settingForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Support\Facades\DB;
class App_settingController extends BaseController
{

    /**
     * Redirect the user to the {provider} authentication page.
     *
     * @param string $provider
     * @return mixed
     */
   

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSettings( FormBuilder $formBuilder)
    {
      
    
        page_title()->setTitle(trans('plugins/app_setting::app_setting.app_setting'));
        return $formBuilder->create(App_settingForm::class,['model'=>''])->renderForm();
       // return view('plugins/app_setting::settings');
    }
    /**
     * @param SocialLoginRequest $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     */
    public function postSettings(Request $request,BaseHttpResponse $response,App_settingForm $app_setting)
    {
       
        if($request->privacy_policy!=$app_setting->getSetting('privacy_policy')){
           
            DB::table('app_settings')->where('variable','privacy_policy')->update(['value'=>$request->privacy_policy]);
        }
        if($request->terms_conditions!=$app_setting->getSetting('terms_conditions')){
           
            DB::table('app_settings')->where('variable','terms_conditions')->update(['value'=>$request->terms_conditions]);
        }
        if($request->contact_us!=$app_setting->getSetting('contact_us')){
           
            DB::table('app_settings')->where('variable','contact_us')->update(['value'=>$request->contact_us]);
        }
        if($request->about_us!=$app_setting->getSetting('about_us')){
           
            DB::table('app_settings')->where('variable','about_us')->update(['value'=>$request->about_us]);
        }
        if($request->fcm_server_key!=$app_setting->getSetting('fcm_server_key')){
           
            DB::table('app_settings')->where('variable','fcm_server_key')->update(['value'=>$request->fcm_server_key]);
        }
        
            DB::table('app_settings')->where('variable','time_slot_config')->update([
                'value'=>[
                    'allowed_days'=>$request->allowed_days,
                    'delivery_starts_from'=>$request->delivery_starts_from,
                    'time_slot_config'=>$request->time_slot_config,
                    'is_time_slots_enabled'=>$request->is_time_slots_enabled,
                     ]
            ]);
        
 
        return $response
            ->setPreviousUrl(route('app_setting.get_settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
