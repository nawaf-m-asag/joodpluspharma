<?php

namespace Botble\Medical\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Medical\Http\Requests\SpecialtiesRequest;
use Botble\Medical\Models\Specialties;
use Illuminate\Support\Arr;

class SpecialtiesForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
     
        $this
            ->setupModel(new Specialties)
            ->setValidatorClass(SpecialtiesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/medical::medical.specialties-name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/medical::medical.add-specialties-name'),
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
