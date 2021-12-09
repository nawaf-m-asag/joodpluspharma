
<style>

    @import url('https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css');
#otp .form-control {
  -webkit-transition: none;
  transition: none;
  width: 32px !important;
  height: 32px !important;
  text-align: center;
  padding: unset;
  display: inline !important;
}

#otp .form-control:focus {
  color: #3F4254;
  background-color: #ffffff;
  border-color: #884377;
  outline: 0;
}

#otp .form-control .form-control-solid {
  background-color: #F3F6F9;
  border-color: #F3F6F9;
  color: #3F4254;
  transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
}

#otp .form-control .form-control-solid:active,
#otp .form-control .form-control-solid.active,
#otp .form-control .form-control-solid:focus,
#otp .form-control .form-control-solid.focus {
  background-color: #EBEDF3;
  border-color: #EBEDF3;
  color: #3F4254;
  transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
}
.form-group .iti--separate-dial-code{
        width: 100%;
        
    } 
   #phone{
       padding-left: 0 !important
; }
.ps-form__content{
    padding: 10px !important;
}
.otp-div{
    display: none;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<div class="ps-my-account">
    <div class="container">
        <form class="ps-form--account ps-tab-root" method="POST" action="">
            @csrf
            <div class="ps-form__content">
                <h4>{{ __('Reset Password') }}</h4>
                <div class="divResetPhone">
                    <div class="form-group validate-input">
                        <input name="phone" type="text" class="form-control mb-2 inptFielsd" id="phone"  value="{{ old('phone') }}" placeholder="Phone Number" />
                        @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" id="txt-password" placeholder="{{ __('Password') }}">
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password_confirmation" id="txt-password-confirmation" placeholder="{{ __('Password') }}">
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div id="recaptcha-container" style="margin-top: 10px"></div>

                <div class="form-group submit">
                    <a  style="color:#fff" class="sendOTPReset ps-btn ps-btn--fullwidth">{{ __('Send OTP') }}</a>
                </div>
                <span class="d-block text-center my-4 text-muted">— or —</span>
                <div class="form-group submit">
                    <a class="ps-btn ps-btn--fullwidth" style="color:#fff" href="{{ route('customer.password.reset') }}" >{{ __('Reset Passwor By Emai') }}</a>
                </div>

                @if (session('status'))
                    <div class="text-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success_msg'))
                    <div class="text-success">
                        {{ session('success_msg') }}
                    </div>
                @endif

                @if (session('error_msg'))
                    <div class="text-danger">
                        {{ session('error_msg') }}
                    </div>
                @endif
            </div>  
            <div class="otp-div">
                <div dir="ltr" class="mb-6 text-center">
                    <div id="otp" class="mb-4 w-100 flex justify-center">
                      <input class="m-1 text-center form-control form-control-solid rounded focus:border-blue-400 focus:shadow-outline" type="text" id="first" maxlength="1" />
                      <input class="m-1 text-center form-control form-control-solid rounded focus:border-blue-400 focus:shadow-outline" type="text" id="second" maxlength="1" />
                      <input class="m-1 text-center form-control form-control-solid rounded focus:border-blue-400 focus:shadow-outline" type="text" id="third" maxlength="1" />
                      <input class="m-1 text-center form-control form-control-solid rounded focus:border-blue-400 focus:shadow-outline" type="text" id="fourth" maxlength="1" />
                      <input class="m-1 text-center form-control form-control-solid rounded focus:border-blue-400 focus:shadow-outline" type="text" id="fifth" maxlength="1" />
                      <input class="m-1 text-center form-control form-control-solid rounded focus:border-blue-400 focus:shadow-outline" type="text" id="sixth" maxlength="1" />
                    </div>
                  </div>
                <div class="form-group submit" >
                    <a id="verifyOTPReset" style="color:#fff" class="ps-btn ps-btn--fullwidth" >{{ __('Verify OPT') }}</a>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>



<script>
    function OTPInput() {
  const inputs = document.querySelectorAll('#otp > *[id]');
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('keydown', function(event) {
      if (event.key === "Backspace") {
        inputs[i].value = '';
        if (i !== 0)
          inputs[i - 1].focus();
      } else {
        if (i === inputs.length - 1 && inputs[i].value !== '') {
          return true;
        } else if (event.keyCode > 47 && event.keyCode < 58) {
          inputs[i].value = event.key;
          if (i !== inputs.length - 1)
            inputs[i + 1].focus();
          event.preventDefault();
        } else if (event.keyCode > 64 && event.keyCode < 91) {
          inputs[i].value = String.fromCharCode(event.keyCode);
          if (i !== inputs.length - 1)
            inputs[i + 1].focus();
          event.preventDefault();
        }
      }
    });
  }
}
OTPInput();
</script>

<script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
        customPlaceholder: function (
            selectedCountryPlaceholder,
            selectedCountryData
        ) {
            return "e.g. " + selectedCountryPlaceholder;
        },
    });
</script>
<!-- REQUIRED CDN  -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"
        integrity="sha512-DNeDhsl+FWnx5B1EQzsayHMyP6Xl/Mg+vcnFPXGNjUZrW28hQaa1+A4qL9M+AiOMmkAhKAWYHh1a+t6qxthzUw=="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css"
        integrity="sha512-yye/u0ehQsrVrfSd6biT17t39Rg9kNc+vENcCXZuMz2a+LWFGvXUnYuWUW6pbfYj1jcBb/C39UZw2ciQvwDDvg=="
        crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        integrity="sha512-BNZ1x39RMH+UYylOW419beaGO0wqdSkO7pi1rYDYco9OL3uvXaC/GTqA5O4CVK2j4K9ZkoDNSSHVkEQKkgwdiw=="
        crossorigin="anonymous"></script>
<!-- Firebase files -->
<!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-app.js"></script>

<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-analytics.js"></script>

<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-firestore.js"></script>


