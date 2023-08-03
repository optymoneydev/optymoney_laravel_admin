@extends('layouts.authentication.master')
@section('title', 'Forget-password')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper">
   <div class="container-fluid p-0">
      <div class="row">
         <div class="col-12">
            <div class="login-card">
               <div>
                  <div><a class="logo" href="{{ route('index') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/login.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
                  <div class="login-main">
                     <h4>Reset Your Password</h4>
                     <form class="theme-form needs-validation" novalidate="" action="{{url('authentication/requestFPOTP')}}" method="POST" id="phoneForm">
                        {{ csrf_field() }}
                        <div class="form-group">
                           <label class="col-form-label">Enter Your Mobile Number</label>
                           <input class="form-control mb-1" id="contact" required name="contact" type="number" value="">
                        </div>
                        <div class="form-group">
                           <label class="col-form-label">Enter Your Email Address</label>
                           <input class="form-control mb-1" id="email" required name="email" type="email" value="">
                        </div>
                        <div class="form-group">
                           <div class="row">
                              <div class="col-12">
                                 <button class="btn btn-primary btn-block m-t-10" type="button" id="sendFPOTP">Send</button>
                              </div>
                           </div>
                        </div>
                     </form>
                     <div id="verifyOTPText"></div>
                     <div class="mt-4 mb-4"><span class="reset-password-link">If don't receive OTP?  <a class="btn-link text-danger" id="resendOFPOTP" href="#">Resend</a></span></div>
                     <form class="theme-form needs-validation" novalidate="" action="{{url('authentication/updatePassword')}}" method="POST" id="FPForm">
                        <div class="form-group">
                           <div class="row">
                              <div class="col">
                                 <label class="col-form-label">Mobile OTP</label>
                                 <input class="form-control text-center opt-text" type="text" required name="motp" id="motp" placeholder="XXXXX" maxlength="5">
                              </div>
                              <div class="col">
                                 <label class="col-form-label">Email OTP</label>
                                 <input class="form-control text-center opt-text" type="text" required name="eotp" id="eotp" placeholder="XXXXX" maxlength="5">
                              </div>
                           </div>
                        </div>
                        <h6 class="mt-4">Create Your Password</h6>
                        <div class="form-group">
                           <label class="col-form-label">New Password</label>
                           <input class="form-control" type="password" name="password" id="password" required="" placeholder="*********">
                        </div>
                        <div class="form-group">
                           <label class="col-form-label">Retype Password</label>
                           <input class="form-control" type="password" name="repassword" id="repassword" required="" placeholder="*********">
                        </div>
                        <div class="form-group mb-0">
                           <div class="checkbox p-0">
                              <input id="checkbox1" type="checkbox">
                              <label class="text-muted" for="checkbox1">Remember password</label>
                           </div>
                           <button class="btn btn-primary btn-block" type="button" id="submitFPForm">Done</button>
                        </div>
                        <p class="mt-4 mb-0">Already have an password?<a class="ms-2" href="{{ route('login') }}">Sign in</a></p>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="{{ asset('assets/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('assets/js/users/forgot.js?v=1.0')}}"></script>
<script src="{{ asset('assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js')}}"></script>
<script>
   (function() {
      'use strict';
      window.addEventListener('load', function() {
         // Fetch all the forms we want to apply custom Bootstrap validation styles to
         var forms = document.getElementsByClassName('needs-validation');
         // Loop over them and prevent submission
         var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
               if (form.checkValidity() === false) {
                  event.preventDefault();
                  event.stopPropagation();
               }
               form.classList.add('was-validated');
            }, false);
         });
      }, false);
   })();
</script>
@endsection