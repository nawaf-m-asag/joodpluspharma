<div class="ps-my-account">
    <div class="container">
        <form id="formData" action="{{ route('customer.register.post') }}" class="ps-form--account ps-tab-root">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group submit">
                        <a class="ps-btn ps-btn--fullwidth @if (Route::currentRouteName() == 'customer.register')   @endif" href="{{ route('customer.register') }}" >{{ __('Sign up By Email') }}</a>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="form-group submit">
                        <a class="ps-btn ps-btn--fullwidth @if (Route::currentRouteName() == 'customer.register_by_phone') active  @endif" href="{{ route('customer.register_by_phone') }}">{{ __('Sign up By Phone') }}</a>
                    </div>
                </div>
            </div>
        <div class="ps-form__content">
        <h4>{{ __('Register An Account') }}</h4>
        <div class="phone-div">   
          
            <div class="form-group">
                
                <input class="form-control" name="name" id="txt-name" type="text" value="{{ old('name') }}" placeholder="{{ __('Your Name') }}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                
            </div>
            <div class="form-group validate-input phone-div">
                <div class="p-t-31 p-b-9 cstm-div phone-div">
                    <span class="txt1">
                        Phone Number <span style="font-size: 10px;">(include country code eg:+91)</span>
                    </span>
                </div>
                <input class="form-control" type="tel" value="{{ old('phone') }}" name="phone"  placeholder="{{ __('Phone Number') }}" required>
                @if ($errors->has('phone'))
                <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" id="txt-password" autocomplete="new-password" placeholder="{{ __('Password') }}">
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password_confirmation" id="txt-password-confirmation" autocomplete="new-password" placeholder="{{ __('Password') }}">
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
            

            <div id="recaptcha-container" style="margin-top: 10px"></div>

            <div class="form-group submit" >
                <a class="ps-btn ps-btn--fullwidth sendOTP" ><span>{{ __(' Send OTP') }}</span></a>
            </div>
        </div>     
        <div class="otp-div" style="display: none">
            <div class="form-group validate-input otp-div" data-validate = "Username is required">
                <input class="form-control"  type="text"  name="verify_otp">
            </div>
            
            <div class="form-group submit" >
                <a id="verifyOTP" class="ps-btn ps-btn--fullwidth" >{{ __('Verify OPT') }}</a>
            </div>
        </div>

        </div>
        </form>
    </div>
</div>


<!-- Firebase files -->
<!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-app.js"></script>

<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-analytics.js"></script>

<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-firestore.js"></script>



