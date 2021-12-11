@extends('core/base::layouts.master')
@section('content')
<div class="container">
    
    <table class="table table-striped border" dir="rtl">
        <tr>
            <td>{{trans('plugins/medical::medical.side-name')}}</td>
            <td>{{$maintenance->side_name}}</td>
        </tr>
        <tr>
            <td>{{trans('plugins/medical::medical.applicant-name')}}</td>
            <td>{{$maintenance->applicant_name}}</td>
        </tr>  
        <tr>
            <td>{{trans('plugins/medical::medical.device-name')}}</td>
            <td>{!!$maintenance->device_name!!}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.descrip-defect')}}</td>
            <td>{{$maintenance->descrip_defect}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.phone')}}</td>
            <td>{{$maintenance->phone}}</td>
        </tr>   
        <tr>
            <td>{{trans('plugins/medical::medical.address')}}</td>
            <td>{{$maintenance->getFullAddressAttribute()}}</td>
        </tr> 
        <tr>
            <td>{{trans('plugins/medical::medical.user-name')}}</td>
            <td>{{$maintenance->user->name}}</td>
        </tr> 
        <tr>
            <td>{{trans('core/base::tables.status')}}</td>
            <td>{!!$maintenance->status->toHtml()!!}</td>
        </tr> 
        <tr>
            @php
                $color=empty($maintenance->file)?"btn-warning":"btn-success";
                $download=empty($maintenance->file)?"":"download";
            @endphp
            <td>{{trans('plugins/medical::medical.file')}}</td>
            <td><a href="{{$maintenance->file}}" {{$download}} class="btn {{$color}} pl-4 pr-4"><i class="fas fa-cloud-download-alt"></i></a></td>
        </tr>
        
    </table> 

</div> 
@endsection