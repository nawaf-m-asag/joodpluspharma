@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    {!! Form::open(['route' => 'customer.prescription.create', 'class' => 'ps-form--account-setting', 'method' => 'POST','enctype'=>"multipart/form-data"]) !!}
        <div class="ps-form__header">
            <h3>{{ SeoHelper::getTitle() }}</h3>
        </div>
        <div class="ps-form__content">

            <div class="form-group">
                <label for="name">{{ __('name') }}:</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
            </div>
            {!! Form::error('name', $errors) !!}
            <div class="form-group">
                <label for="phone">{{ __('phone') }}:</label>
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">
            </div>
            {!! Form::error('phone', $errors) !!}
            <div class="form-group">
                <label for="address">{{ __('address') }}:</label>
                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}">
            </div>
            {!! Form::error('address', $errors) !!}
            <div class="form-group">
                <label for="city">{{ __('city') }}:</label>
                <select id="city" type="text" class="form-control" name="city_id" value="{{ old('city_id') }}">
                @foreach ($cities as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
                </select>
            </div>
            {!! Form::error('city', $errors) !!}

         

            <div class="form-group">
                <label for="image_file">{{ __('image') }}:</label>
                <input id="image_file" type="file" class="form-control" name="image_file" value="{{ old('image_file') }}">
            </div>
            {!! Form::error('image_file', $errors) !!}
            <div>
                <label for="file">{{ __('file') }}:</label>
                <input id="formFile" class="form-control" type="file"  name="file" value="{{ old('file') }}">
            </div>
            {!! Form::error('file', $errors) !!}



            <div class="form-group">
                <label for="nots">{{ __('notes') }}:</label>
                <textarea id="notes" type="text" class="form-control" name="notes" value="{{ old('notes') }}"></textarea>
            </div>
            {!! Form::error('notes', $errors) !!}

            <div class="form-group">
                <button class="ps-btn ps-btn--sm" type="submit">{{ __('Add Prescription') }}</button>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
