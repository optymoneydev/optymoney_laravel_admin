<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Simple, Secure & Safe way to buy Digital Gold & Silver">
    <meta name="keywords" content="Digital Gold, Digital Silver, Gold Online">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <title>Optymoney - Fintech</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css?v=1.0')}}">
    
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.css?v=1.0?v=1.0')}}">
    <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=f5fdf366"></script>
  </head>
  <body class="landing-page">
    <!-- page-wrapper Start-->
    <div class="page-wrapper landing-page">
      <!-- Page Body Start            -->
      <div class="landing-home">
        <!-- <ul class="decoration">
          <li class="one"><img class="img-fluid" src="{{asset('assets/images/landing/decore/1.png')}}" alt=""></li>
          <li class="two"><img class="img-fluid" src="{{asset('assets/images/landing/decore/2.png')}}" alt=""></li>
          <li class="three"><img class="img-fluid" src="{{asset('assets/images/landing/decore/4.png')}}" alt=""></li>
          <li class="four"><img class="img-fluid" src="{{asset('assets/images/landing/decore/3.png')}}" alt=""></li>
          <li class="five"><img class="img-fluid" src="{{asset('assets/images/landing/2.png')}}" alt=""></li>
          <li class="six"><img class="img-fluid" src="{{asset('assets/images/landing/decore/cloud.png')}}" alt=""></li>
          <li class="seven"><img class="img-fluid" src="{{asset('assets/images/landing/2.png')}}" alt=""></li>
        </ul> -->
        <div class="container-fluid">
          <div class="sticky-header">
            <header>                       
            <nav class="navbar navbar-b navbar-trans navbar-expand-xl fixed-top nav-padding" id="sidebar-menu">
                <a class="navbar-brand p-0" href="#"><img class="img-fluid" src="{{asset('assets/images/landing/landing_logo.png')}}" alt=""></a>
                <button class="navbar-toggler navabr_btn-set custom_nav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
                <div class="navbar-collapse justify-content-end collapse hidenav" id="navbarDefault">
                  <ul class="navbar-nav navbar_nav_modify" id="scroll-spy">
                    <li class="nav-item"><a class="nav-link" href="#gold">Gold/Silver</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://www.optymoney.com/tax.html" target="_blank">Tax</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://www.optymoney.com/all_product.html" target="_blank">Investments</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://www.optymoney.com/createwill.html" target="_blank">Will</a></li>
                    <li class="nav-item buy-btn"><a class="nav-link" href="{{url('authentication/login')}}">Sign In</a></li>
                  </ul>
                </div>
              </nav>
            </header>
          </div>
        </div>
      </div>
      <section class="section-space cuba-demo-section layout" id="gold">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content">
                <div class="couting">
                  <h5 class="text-center">Contact Us</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
          <div class="card height-equal">
            <div class="contact-form card-body">
               <div class="row">
                  <div class="col-sm-6 col-lg-6 col-xl-6 xl-50 col-md-6 box-col-6">
                     <p class="text-center" id="responseMsg"></p>
                     <form class="theme-form needs-validation" novalidate="" action="{{url('saveContact')}}" method="POST" id="contactForm">
                        <div class="form-icon"><i class="icofont icofont-envelope-open"></i></div>
                        <div class="mb-3">
                           <label for="inputName" style="float: left">Your Name</label>
                           <input class="form-control" required id="inputName" name="inputName" type="text" placeholder="">
                        </div>
                        <div class="mb-3">
                           <label class="col-form-label"  style="float: left" for="inputEmail">Email</label>
                           <input class="form-control" required id="inputEmail" name="inputEmail" type="email" placeholder="Demo@gmail.com">
                        </div>
                        <div class="mb-3">
                           <label class="col-form-label"  style="float: left" for="inputEmail">Mobile</label>
                           <input class="form-control" required id="inputContact" name="inputContact" type="number" placeholder="1234567890">
                        </div>
                        <div class="mb-3">
                           <label class="col-form-label" style="float: left" for="exampleInputEmail1">message</label>
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
                           <div class="info-item d-flex  pb-4 border-bottom">
                              <span class="fa fa-phone"></span>
                              <div class="info-item-details">
                                 <strong>
                                    <a href="Mob:9818465241">+91 741 101 1280</a>
                                 </strong>
                                 <br>
                                 <small>(10 am-6pm IST, Mon-Friday)</small>
                              </div>
                           </div>
                           <div class="info-item d-flex  py-4 border-bottom">
                              <span class="fa fa-envelope"></span>
                              <div class="info-item-details">
                                 <a href="mailto:support@optymoney.com"> support@optymoney.com</a>
                              </div>
                           </div>
                           <div class="info-item d-flex  pt-4">
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
      </section>
      <!--
      <section class="section-space cuba-demo-section bg-Widget pb-0 bg-primary">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2>Cards</h2>
                </div>
                <p>So many unique cards</p>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid o-hidden">
          <div class="row landing-cards">
            
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section email_bg">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2> Email</h2>
                  <p> Usefull Templates</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section components-section" id="tax">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2>UI</h2>
                </div>
                <p>Components</p>
              </div>
            </div>
          </div>
        </div>
        <div class="container container-modify">
          <div class="row component_responsive">
            
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section app_bg" id="investment">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2>20+</h2>
                </div>
                <p>Usefull application</p>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid container-modify apps">
          <div class="landing-slider">
            <div class="row">
              
            </div>
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section frameworks-section" id="testament">
        <div class="container">
          <div class="row">                 
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2>4+</h2>
                </div>
                <p class="mb-0">Top Frameworks</p>
              </div>
            </div>
            <div class="col-sm-12 framworks">                 
              
            </div>
          </div>
        </div>
      </section> -->
      <footer class="footer-bg">
        <div class="container">
          <div class="landing-center ptb50">
            <div class="title"><img class="img-fluid" src="{{asset('assets/images/landing/landing_logo.png')}}" alt=""></div>
            <div class="footer-content">
              <div class="col-md-12 footer-copyright text-center">
                <div class="row">
                <div class="col-md-2"><a href="{{url('privacy_home')}}" class="mb-0">Privacy Policy</a></div>
                  <div class="col-md-2"><a href="{{url('terms-conditions')}}" class="mb-0">Terms & Conditions</a></div>
                  <div class="col-md-2"><a href="{{url('faq_home')}}"  class="mb-0">FAQ</a></div>
                  <div class="col-md-2"><a href="{{url('about_home')}}"  class="mb-0">About Us</a></div>
                  <div class="col-md-2"><a href="{{url('contact_home')}}"  class="mb-0">Contact Us</a></div>
                  <div class="col-md-2"></div>
                </div>
              </div>
              <!-- <h1>The Cuba Bootstrap Admin Theme Trusted By Many Developers World Wide.</h1>
              <p>If You like Our Theme So Please Rate Us.</p><a class="btn mrl5 btn-lg btn-primary default-view" target="_blank" href="{{ route('index') }}">Check Now</a><a class="btn mrl5 btn-lg btn-secondary btn-md-res" target="_blank" href="https://1.envato.market/3GVzd">Buy Now                    </a> -->
            </div>
          </div>
        </div>
      </footer>
    </div>
    <!-- latest jquery-->
    <script src="{{asset('assets/js/jquery-3.5.1.min.js')}}"></script>
    <!-- Bootstrap js-->
    <script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- feather icon js-->
    <script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
    <!-- scrollbar js-->
    <!-- Sidebar jquery-->
    <script src="{{asset('assets/js/config.js?v=1.0')}}"></script>
    <!-- Plugins JS start-->
    <script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
    <script src="{{asset('assets/js/tooltip-init.js')}}"></script>
    <script src="{{asset('assets/js/animation/wow/wow.min.js')}}"></script>
    <script src="{{asset('assets/js/landing_sticky.js')}}"></script>
    <script src="{{asset('assets/js/landing.js?v=1.0')}}"></script>
    <!-- Plugins JS Ends-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-154419016-1"></script>
    <!-- Theme js-->
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
    <script src="{{asset('assets/js/script.js')}}"></script>
    <!-- login js-->
    <!-- Plugin used-->
  </body>
</html>