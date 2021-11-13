@extends('core/base::layouts.master')
@section('content')
<div class="container">
    
    <table class="table table-striped border" dir="rtl">
        <tr>
            <td>{{trans('plugins/medical::medical.patient-name')}}</td>
            <td>{{$nursing->p_name}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.doctor-name')}}</td>
            <td>{{$nursing->doctor->name}}</td>
        </tr>  
        <tr>
            <td>{{trans('plugins/medical::medical.services')}}</td>
            <td>{!!$nursing->getAllSelectedServes()!!}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.age')}}</td>
            <td>{{$nursing->p_age}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.sex')}}</td>
            <td>{{$nursing->p_sex}}</td>
        </tr>   
        <tr>
            <td>{{trans('plugins/medical::medical.address')}}</td>
            <td>{{$nursing->address}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.user-name')}}</td>
            <td>{{$nursing->user->name}}</td>
        </tr> 
        <tr>
            <td>{{trans('core/base::tables.status')}}</td>
            <td>{!!$nursing->status->toHtml()!!}</td>
        </tr> 
        <tr>
            @php
                $color=empty($item->attachedFile)?"btn-warning":"btn-success";
                $download=empty($item->attachedFile)?"":"download";
            @endphp
            <td>{{trans('plugins/medical::medical.file')}}</td>
            <td><a {{$download}} class="btn {{$color}} pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i>{{$nursing->attachedFile}}</a></td>
        </tr>
        
    </table> 

</div> 
@endsection