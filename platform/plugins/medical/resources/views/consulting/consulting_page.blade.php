@extends('core/base::layouts.master')
@section('content')
<div class="container">
    
    <table class="table table-striped border" dir="rtl">
        <tr>
            <td>{{trans('plugins/medical::medical.patient-name')}}</td>
            <td>{{$consulting->p_name}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.doctor-name')}}</td>
            <td>{{$consulting->doctor->name}}</td>
        </tr>  
        <tr>
            <td>{{trans('plugins/medical::medical.specialties-name')}}</td>
            <td>{!!$consulting->specialty->name!!}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.age')}}</td>
            <td>{{$consulting->p_age}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.sex')}}</td>
            <td>{{$consulting->p_sex}}</td>
        </tr>   
        <tr>
            <td>{{trans('plugins/medical::medical.female_status')}}</td>
            <td>{{$consulting->female_status}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.chronic_diseases')}}</td>
            <td>{{$consulting->chronic_diseases}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.operations')}}</td>
            <td>{{$consulting->operations}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.medicines')}}</td>
            <td>{{$consulting->medicines}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.desc_situation')}}</td>
            <td>{{$consulting->desc_situation}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.user-name')}}</td>
            <td>{{$consulting->user->name}}</td>
        </tr> 
        <tr>
            <td>{{trans('core/base::tables.status')}}</td>
            <td>{!!$consulting->status->toHtml()!!}</td>
        </tr> 
        <tr>
            @php
                $color=empty($consulting->file)?"btn-warning":"btn-success";
                $download=empty($consulting->file)?"":"download";
            @endphp
            <td>{{trans('plugins/medical::medical.file')}}</td>
            <td><a href="{{$consulting->file}}" {{$download}} class="btn {{$color}} pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i></a></td>
        </tr>
        
    </table> 

</div> 
@endsection