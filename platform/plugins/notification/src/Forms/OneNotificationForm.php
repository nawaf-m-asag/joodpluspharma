<?php

namespace Botble\Notification\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Support\Facades\DB;
use Botble\Notification\Http\Requests\OneNotificationRequest;
use Botble\Notification\Models\Notification;
use Illuminate\Support\Arr;
class OneNotificationForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
       
        $id=isset($_GET['id'])?$_GET['id']:null;
        $list_customers = DB::table('ec_customers')->where('fcm_id','!=',null);
        if($id!=null)
        $list_customers->where('id',$id);
        $list_customers= $list_customers->get();

        $customers = [];
        foreach ($list_customers as $row) {
            $customers[$row->id] = $row->name;
        }
        
        $customers = Arr::sortRecursive($customers);

        $this->setupModel(new Notification)
        ->setValidatorClass(OneNotificationRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label'      => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('message', 'textarea', [
                'label'      => trans('plugins/notification::notification.message'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  =>trans('plugins/notification::notification.message'),
                    'data-counter' => 220,
                ],
            ])->add('customer_id', 'select',[
                'label'      => trans('plugins/notification::notification.customers'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'select-search-full',
                ],
                'choices'    => $customers,
                
            ])
            ->add('image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('image');
            
            
    }
}
