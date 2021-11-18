@extends('core/base::layouts.master')
@section('content')
<div class="container">
    
    <table class="table table-striped border" dir="rtl">
        <tr>
            <td>{{trans('plugins/medical::medical.patient-name')}}</td>
            <td>{{$examinations->p_name}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.doctor-name')}}</td>
            <td>{{$examinations->d_name}}</td>
        </tr>  
        <tr>
            <td>{{trans('plugins/medical::medical.age')}}</td>
            <td>{{$examinations->p_age}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.sex')}}</td>
            <td>{{$examinations->p_sex}}</td>
        </tr>   
        <tr>
            <td>{{trans('plugins/medical::medical.lap_name')}}</td>
            <td>{{$examinations->lap_name}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.address')}}</td>
            <td>{{$examinations->address}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.required_checks')}}</td>
            <td>{{$examinations->required_checks}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.user-name')}}</td>
            <td>{{$examinations->user->name}}</td>
        </tr> 
        <tr>
            <td>{{trans('core/base::tables.status')}}</td>
            <td>{!!$examinations->status->toHtml()!!}</td>
        </tr> 
        <tr>
            @php
                $color=empty($item->file)?"btn-warning":"btn-success";
                $download=empty($item->file)?"":"download";
            @endphp
            <td>{{trans('plugins/medical::medical.file')}}</td>
            <td><a href="{{$examinations->file}}" {{$download}} class="btn {{$color}} pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i></a></td>
        </tr>
        
    </table> 

</div> 
@endsection