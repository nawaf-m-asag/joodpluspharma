<?php
namespace Botble\Notification\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Notification\Http\Requests\NotificationRequest;
use Botble\Notification\Http\Requests\OneNotificationRequest;
use Botble\Notification\Repositories\Interfaces\NotificationInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Notification\Tables\NotificationTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Notification\Forms\NotificationForm;
use Botble\Notification\Forms\OneNotificationForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use RvMedia;

class NotificationController extends BaseController
{
    /**
     * @var NotificationInterface
     */
    protected $notificationRepository;

    /**
     * @param NotificationInterface $notificationRepository
     */
    public function __construct(NotificationInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @param NotificationTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(NotificationTable $table)
    {
  
        page_title()->setTitle(trans('plugins/notification::notification.name'));

        return $table->renderTable();
    }
    public function OneNotification(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/notification::notification.one-user'));

        return $formBuilder->create(OneNotificationForm::class, ['model'=>null])->renderForm();
    }
    public function SendOneNotification (OneNotificationRequest $request, BaseHttpResponse $response)
    {
        $fcm_ids[]=null;
        $res=DB::table('ec_customers')->select('fcm_id')->where('id','=',$request->customer_id)->get();
        foreach ($res as $fcm_id) {
            if (!empty($fcm_id)) {
                $fcm_ids[] = $fcm_id->fcm_id;
            }
        }
        $image=(isset($request->image)&&$request->image!=null)?RvMedia::getImageUrl($request->image,'small', false):'null';
        $res=$this->sendNotification($fcm_ids,[
            'content_available' => true,
            'title' => strval($request->title),
            'body' =>  strval($request->message),
            'image' => $image,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ]);
     
        return $response
            ->setPreviousUrl(route('notification.one-notification'))
            ->setNextUrl('#')
            ->setMessage(trans('core/base::notices.create_success_message'));
    }
    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
       
        page_title()->setTitle(trans('plugins/notification::notification.create'));
        Assets::addScriptsDirectly('vendor/core/plugins/notification/js/notification.js');
        return $formBuilder->create(NotificationForm::class)->renderForm();
    }
   
    /**
     * @param NotificationRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(NotificationRequest $request, BaseHttpResponse $response)
    {

        
      
        if(isset($request->type)&&$request->type=='products')
            $request['type_id']=isset($request->product_id)?$request->product_id:0;
       else if(isset($request->type)&&$request->type=='categories')
            $request['type_id']=isset($request->category_id)?$request->category_id:0;
       else
       {
        $request->type_id=0;
        $request->type='default';
       } 
       
       unset($request->category_id);
       unset($request->product_id);

        
      
        $notification = $this->notificationRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(NOTIFICATION_MODULE_SCREEN_NAME, $request, $notification));
        $fcm_ids[]=null;
        $res=DB::table('ec_customers')->select('fcm_id')->where('fcm_id','!=',null)->get();
        foreach ($res as $fcm_id) {
            if (!empty($fcm_id)) {
                $fcm_ids[] = $fcm_id->fcm_id;
            }
        }
        $image=(isset($request->image)&&$request->image!=null)?RvMedia::getImageUrl($request->image,'small', false):'null';
        $res=$this->sendNotification($fcm_ids,[
            'content_available' => true,
            'title' => strval($request->title),
            'body' =>  strval($request->message),
            'image' => $image,
            'type' => strval($request->type),
            'type_id' => strval($request->type_id),
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ]);
     
        return $response
            ->setPreviousUrl(route('notification.index'))
            ->setNextUrl('#')
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        Assets::addScriptsDirectly('vendor/core/plugins/notification/js/notification.js');
        $notification = $this->notificationRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $notification));

        page_title()->setTitle(trans('plugins/notification::notification.edit') . ' "' . $notification->name . '"');
        if($notification->type=='categories'){
            $notification->category_id=$notification->type_id;
        }
        else if($notification->type=='products'){
            $notification->product_id=$notification->type_id;
        }
        return $formBuilder->create(NotificationForm::class, ['model' => $notification])->renderForm();
    }

    /**
     * @param int $id
     * @param NotificationRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, NotificationRequest $request, BaseHttpResponse $response)
    {

        if(isset($request->type)&&$request->type=='products')
        $request['type_id']=isset($request->product_id)?$request->product_id:0;
       else if(isset($request->type)&&$request->type=='categories')
        $request['type_id']=isset($request->category_id)?$request->category_id:0;
       else
       {
        $request->type_id=0;
        $request->type='default';
       } 
       
       unset($request->category_id);
       unset($request->product_id);

        $notification = $this->notificationRepository->findOrFail($id);

        $notification->fill($request->input());

        $this->notificationRepository->createOrUpdate($notification);

        event(new UpdatedContentEvent(NOTIFICATION_MODULE_SCREEN_NAME, $request, $notification));

        return $response
            ->setPreviousUrl(route('notification.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        
        try {
            $notification = $this->notificationRepository->findOrFail($id);

            $this->notificationRepository->delete($notification);

            event(new DeletedContentEvent(NOTIFICATION_MODULE_SCREEN_NAME, $request, $notification));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
               ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $notification = $this->notificationRepository->findOrFail($id);
            $this->notificationRepository->delete($notification);
            event(new DeletedContentEvent(NOTIFICATION_MODULE_SCREEN_NAME, $request, $notification));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }





    ////////////////////////
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification($device_token, $message)
    {

 
        $SERVER_API_KEY = Setting::get_settings('fcm_server_key');
        
        // payload data, it will vary according to requirement
        $data = [
            "registration_ids" =>$device_token,
            "notification" => $message
                
            
        ];
    
        $dataString = json_encode($data);
       
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    
        $response = curl_exec($ch);
      
        curl_close($ch);
        return $response;
         
    }
}
