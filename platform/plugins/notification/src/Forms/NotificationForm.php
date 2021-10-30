<?php

namespace Botble\Notification\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Notification\Http\Requests\NotificationRequest;
use Botble\Notification\Models\Notification;
use Illuminate\Support\Arr;

class NotificationForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $list = get_product_categories();

        $categories = [];
        foreach ($list as $row) {
            $categories[$row->id] = $row->indent_text . ' ' . $row->name;
        }
        
        $categories = Arr::sortRecursive($categories);

        $list_pro = get_products();

        $products = [];
        foreach ($list_pro as $row) {
            $products[$row->id] = $row->indent_text . ' ' . $row->name;
        }
        
        $products = Arr::sortRecursive($products);

        
        $this
            ->setupModel(new Notification)
            ->setValidatorClass(NotificationRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label'      => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('message', 'text', [
                'label'      => trans('plugins/notification::notification.message'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 220,
                ],
            ])
            ->add('type', 'select', [
                'label'      => trans('plugins/notification::notification.type'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => ['default'=>'default','products'=>'products','categories'=>'categories'],
                'default_value'      => 'default',

            ])
            ->add('category_id', 'select', [
                'label'      => trans('plugins/notification::notification.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'select-search-full',
                ],
                'choices'    => $categories,
            ])
            ->add('product_id', 'select',[
                'label'      => trans('plugins/notification::notification.product'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'select-search-full',
                ],
                'choices'    => $products,
            ])
            
          
            ->add('date_sent', 'hidden', [
                
                'default_value' => now()
                
            ])
            ->add('image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
         
            ->setBreakFieldPoint('image');
            
            
    }
}
