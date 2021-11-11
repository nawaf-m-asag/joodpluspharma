<?php

namespace Botble\Medical\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Medical\Http\Requests\DoctorRequest;
use Botble\Medical\Models\Doctors;
use Botble\Medical\Models\Specialties;
use Illuminate\Support\Arr;

class DoctorForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
       
        $list_specialties= Specialties::select('id','name')->get();
      

        $specialties = [];
        foreach ($list_specialties as $row) {
            $specialties[$row->id] = $row->name;
        }
        
        $specialties = Arr::sortRecursive($specialties);

        $this
            ->setupModel(new Doctors)
            ->setValidatorClass(DoctorRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/medical::medical.doctor-name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/medical::medical.add-doctor-name'),
                    'data-counter' => 120,
                ],
            ])
            ->add('specialty_id', 'customSelect', [
                'label'      => trans('plugins/medical::medical.specialties'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $specialties,
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
