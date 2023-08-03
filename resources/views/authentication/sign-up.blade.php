@extends('layouts.authentication.master')
@section('title', 'Sign-up-wizard')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-12 p-0">
         <div>
            <div class="theme-form">
               <div class="wizard-4" id="wizard">
                  <ul>
                     <li>
                        <a class="logo text-start ps-0" href="{{ route('home') }}">
                           <img class="img-fluid for-light" src="{{asset('assets/images/logo/login.png')}}" alt="looginpage">
                           <!-- <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"> -->
                        </a>
                     </li>
                     <li>
                        <a href="#step-1">
                           <h4>1</h4>
                           <h5>Contact Information</h5>
                           <small>Add phone number & email</small>
                        </a>
                     </li>
                     <li>
                        <a href="#step-2">
                           <h4>2</h4>
                           <h5>Contact Verification</h5>
                           <small>Verifying contact info</small>
                        </a>
                     </li>
                     <li>
                        <a href="#step-3">
                           <h4>3</h4>
                           <h5>Basic Information</h5>
                           <small>Add basic info</small>
                        </a>
                     </li>
                     <li>
                        <a href="#step-4">
                           <h4>4</h4>
                           <h5>PAN and AADHAAR</h5>
                           <small>Add pan and aadhaar</small>
                        </a>
                     </li>
                     <li class="pb-0">
                        <a href="#step-5">
                           <h4>5</h4>
                           <h5> Address Information<!-- <i class="fa fa-thumbs-o-up"></i> --></h5>
                           <small>Add address info & Complete.. !</small>
                        </a>
                     </li>
                     <!-- <li><img src="{{asset('assets/images/login/icon.png')}}" alt="loginpage"></li> -->
                  </ul>
                  <div id="step-1">
                     <div class="wizard-title text-center">
                        <h2>Welcome to Optymoney</h2>
                        <!-- <h5 class="text-muted mb-4">Enter your email & password to login</h5> -->
                     </div>
                     <div class="col-lg-8 col-md-8 col-xs-12 login-main">
                        <div class="theme-form">
                           <form class="theme-form needs-validation" novalidate="" action="{{url('authentication/requestOTP')}}" method="POST" id="phoneForm">
                              <div class="form-group mb-3">
                                 <label for="contact">Contact No.<small>(Linked with PAN)</small></label>
                                 <input class="form-control" id="contact" name="contact" type="number" required placeholder="123456789">
                              </div>
                              <div class="form-group mb-3 m-t-15">
                                 <label for="emailAddress">Email address</label>
                                 <input class="form-control" id="emailAddress" name="emailAddress" type="email" required placeholder="name@example.com">
                              </div>
                              <div class="form-group mb-3">
                                 <p>You will receive an OTP to phone number and email address</p>
                                 <p>By clicking continue, you agree to our <a href="{{url('terms')}}">Terms &amp; Conditions</a></p>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <div id="step-2">
                     <div class="wizard-title text-center">
                        <h2>Enter OTP</h2>
                     </div>
                     <div class="col-lg-8 col-md-8 col-xs-12 login-main">
                        <div class="theme-form">
                           <div class="alert alert-primary dark" role="alert" id="verifyOTPText">
                              <span>We’ve sent you an SMS with a 6-digit verification code!</span>
                           </div>
                           <form class="form-space needs-validation theme-form" novalidate="" action="{{url('authentication/verifyOTP')}}" method="POST" id="otpForm" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                              <div class="col">
                                 <label class="col-form-label">Mobile OTP</label>
                                 <input class="form-control text-center opt-text" type="text" required name="motp" id="motp" placeholder="XXXXX" maxlength="5">
                              </div>
                              <div class="col">
                                 <label class="col-form-label">Email OTP</label>
                                 <input class="form-control text-center opt-text" type="text" required name="eotp" id="eotp" placeholder="XXXXX" maxlength="5">
                              </div>
                           </form>
                           <!-- <label>Didn't receive OTP?</label> -->
                           <!-- <label><a href="#" id="resendOTP">Resend</a> | Call Me or Change number?</label> -->
                        </div>
                     </div>
                  </div>
                  <div id="step-3">
                     <div class="wizard-title text-center">
                        <h2>Basic Information</h2>
                     </div>
                     <div class="col-lg-8 col-md-8 col-xs-12 login-main">
                        <div class="theme-form">
                           <div class="alert alert-primary dark" role="alert" id="basicInfoMsg">
                              <span>We’ve sent you an SMS with a 6-digit verification code!</span>
                           </div>
                           <form class="theme-form needs-validation" novalidate="" action="{{url('authentication/createAccount')}}" method="POST" id="createAccountForm">
                              <div class="form-group mb-3">
                                 <label for="name">First Name</label>
                                 <input class="form-control" id="fname" name="fname" type="text" placeholder="First Name" required="required">
                              </div>
                              <div class="form-group mb-3">
                                 <label for="lname">Last Name</label>
                                 <input class="form-control" id="lname" name="lname" type="text" required="required" placeholder="Last Name">
                              </div>
                              <div class="form-group mb-3">
                                 <label for="password">Password</label>
                                 <input class="form-control" id="password" name="password" type="password" required="required" placeholder="Password">
                              </div>
                              <div class="form-group mb-3">
                                 <label for="repassword">Confirm Password</label>
                                 <input class="form-control" id="repassword" name="repassword" type="password" required="required" placeholder="Enter again">
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <div id="step-4">
                     <div class="wizard-title text-center">
                        <h2>Validate PAN and AADHAAR</h2>
                     </div>
                     <div class="col-lg-8 col-md-8 col-xs-12 login-main">
                        <div class="theme-form">
                           <div class="alert alert-primary dark" role="alert" id="pan_aadhaar_msg">
                              <span>We’ve sent you an SMS with a 6-digit verification code!</span>
                           </div>
                           <form class="theme-form needs-validation" novalidate="" action="{{url('authentication/validatePanAadhaar')}}" method="POST" id="validatePanAadhaar">
                              <div class="form-group mb-3">
                                 <label for="exampleFormControlInput1">Date of Birth&nbsp;<small>(As on PAN Card)</small></label>
                                 <input class="form-control digits" id="dob" name="dob" type="text">
                              </div>
                              <div class="form-group mb-3">
                                 <label class="control-label">PAN</label>
                                 <input class="form-control" placeholder="PAN Number" type="text" id="pan" name="pan">
                              </div>
                              <div class="form-group mb-3">
                                 <label class="control-label">Aadhaar Number</label>
                                 <input class="form-control" placeholder="Aadhaar Number" type="text" id="aadhaar" name="aadhaar">
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <div id="step-5">
                     <div class="wizard-title text-center">
                        <h2>Address & Nominee</h2>
                     </div>
                     <div class="col-lg-8 col-md-8 col-xs-12 login-main">
                        <div class="theme-form">
                           <div class="alert alert-primary dark" role="alert" id="address_msg">
                              <span>We’ve sent you an SMS with a 6-digit verification code!</span>
                           </div>
                           <form class="theme-form needs-validation" novalidate="" action="{{url('authentication/finishSteps')}}" method="POST" id="finishSteps">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label class="control-label">State</label>
                                       <select class="form-control mt-1" id="state" name="state" required="required">
                                          
                                       </select>
                                    </div>
                                    <div class="form-group mb-3">
                                       <label class="control-label">City</label>
                                       <select class="form-control mt-1" id="city" name="city" required="required">
                                          
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label class="control-label">Nominee Name</label>
                                       <input class="form-control mt-1" type="text" placeholder="Nominee Name" id="nominee_name" name="nominee_name" required="required">
                                    </div>
                                    <div class="form-group mb-3">
                                       <label class="control-label">Nominee Relation</label>
                                       <input class="form-control mt-1" type="text" placeholder="Nominee Relation" id="nominee_relation" name="nominee_relation" required="required">
                                    </div>
                                    <div class="form-group mb-3">
                                       <label class="control-label">Nominee Date of Birth</label>
                                       <input class="form-control digits" id="nominee_dob" name="nominee_dob" type="text" required="required">
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-wizard/form-wizard-five.js?v=1.0')}}"></script>
<script src="{{ asset('assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
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