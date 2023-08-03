@extends('layouts.simple.master')
@section('title', 'Sample Page')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Sample Page</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pages</li>
<li class="breadcrumb-item active">Contact Us</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12 col-lg-12 col-xl-12 xl-100 col-md-12 box-col-12">
         <div class="card height-equal">
            <div class="card-header">
               <h5>Contact Us</h5>
               <div class="card-header-right">
                  <ul class="list-unstyled card-option">
                     <li><i class="fa fa-spin fa-cog"></i></li>
                     <li><i class="view-html fa fa-code"></i></li>
                     <li><i class="icofont icofont-maximize full-card"></i></li>
                     <li><i class="icofont icofont-minus minimize-card"></i></li>
                     <li><i class="icofont icofont-refresh reload-card"></i></li>
                     <li><i class="icofont icofont-error close-card"></i></li>
                  </ul>
               </div>
            </div>
            <div class="contact-form card-body">
               <div class="row">
                  <div class="col-sm-6 col-lg-6 col-xl-6 xl-50 col-md-6 box-col-6">
                     <p class="text-center" id="responseMsg"></p>
                     <form class="theme-form needs-validation" novalidate="" action="{{url('saveContact')}}" method="POST" id="contactForm">
                        <div class="form-icon"><i class="icofont icofont-envelope-open"></i></div>
                        <div class="mb-3">
                           <label for="inputName">Your Name</label>
                           <input class="form-control" required id="inputName" name="inputName" type="text" placeholder="">
                        </div>
                        <div class="mb-3">
                           <label class="col-form-label" for="inputEmail">Email</label>
                           <input class="form-control" required id="inputEmail" name="inputEmail" type="email" placeholder="Demo@gmail.com">
                        </div>
                        <div class="mb-3">
                           <label class="col-form-label" for="inputEmail">Mobile</label>
                           <input class="form-control" required id="inputContact" name="inputContact" type="number" placeholder="1234567890">
                        </div>
                        <div class="mb-3">
                           <label class="col-form-label" for="exampleInputEmail1">message</label>
                           <textarea class="form-control textarea" rows="3" cols="50" name="message" id="message" placeholder="Your Message"></textarea>
                        </div>
                        <div class="text-sm-end">
                           <button class="btn btn-primary-gradien" id="saveContact" type="button">SEND IT</button>
                        </div>
                     </form>
                  </div>
                  <div class="col-sm-6 col-lg-6 col-xl-6 xl-50 col-md-6 box-col-6">
                     <div class="card">
                        <div class="card-body p-4 p-lg-5">
                           <div class="info-item d-flex align-items-center pb-4 border-bottom">
                              <span class="fa fa-phone"></span>
                              <div class="info-item-details">
                                 <strong>
                                    <a href="Mob:9818465241">+91 741 101 1280</a>
                                 </strong>
                                 <br>
                                 <small>(10 am-6pm IST, Mon-Friday)</small>
                              </div>
                           </div>
                           <div class="info-item d-flex align-items-center py-4 border-bottom">
                              <span class="fa fa-envelope"></span>
                              <div class="info-item-details">
                                 <a href="mailto:support@optymoney.com"> support@optymoney.com</a>
                              </div>
                           </div>
                           <div class="info-item d-flex align-items-center pt-4">
                              <span class="fa fa-map-marker"></span>
                              <div class="info-item-details">
                                 <h6>Devmantra Online Services Pvt Ltd</h6>
                                 <p> No. 85/1, CBI Main Road, Bangalore, Karnataka, 560 024 </p>
                              </div>
                           </div>
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
<script src="{{ asset('assets/js/contactus.js')}}"></script>
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