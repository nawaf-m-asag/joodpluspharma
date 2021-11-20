<?php

namespace Botble\Medical\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Medical\Http\Requests\LaboratoriesRequest;
use Botble\Medical\Models\laboratories;
use Illuminate\Support\Arr;

class LaboratoriesForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
       
        $this
            ->setupModel(new Laboratories)
            ->setValidatorClass(LaboratoriesRequest::class)
            ->withCustomFields()
            ->add('lab_name', 'text', [
                'label'      => trans('plugins/medical::medical.lab_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/medical::medical.add-lab-name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('phone', 'tel', [
                'label'      => trans('plugins/medical::medical.phone'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/medical::medical.add-phone'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'email', [
                'label'      => trans('plugins/medical::medical.email'),
                'label_attr' => ['class' => 'control-label email required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/medical::medical.add-email'),
                    'data-counter' => 120,
                ],
            ])
            ->add('address', 'text', [
                'label'      => trans('plugins/medical::medical.address'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/medical::medical.add-address'),
                    'data-counter' => 120,
                ],
            ])
           
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
       
    }
}
