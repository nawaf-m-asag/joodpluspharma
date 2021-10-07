<?php

namespace Botble\App_setting\Forms;

use Botble\Base\Forms\FormAbstract;

use Botble\App_setting\Http\Requests\AreaRequest;
use Botble\App_setting\Models\Area;
use Botble\App_setting\Models\City;
use Illuminate\Support\Arr;
class AreaForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $list_cities = City::all();

        $cities = [];
        foreach ($list_cities as $row) {
            $cities[$row->id] = $row->indent_text . ' ' . $row->name;
        }
        
        $cities = Arr::sortRecursive($cities);
        $this
            ->setupModel(new Area)
            ->setValidatorClass(AreaRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('city_id', 'select', [
                'label'      => trans('plugins/app_setting::app_setting.city'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'select-search-full',
                ],
                'choices'    => $cities,
            ]);

            
    }
}
