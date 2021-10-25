@extends('core/base::layouts.master')
@section('content')
{!! Form::open(['route' => ['social-login.settings']]) !!}
<div class="max-width-1200">
    <div class="flexbox-annotated-section">
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <div class="form-group">
                   
                    <label>
                        <textarea type="editor" class="control-label">
                        </textarea>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
