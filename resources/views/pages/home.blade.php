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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.css?v=1.0')}}">
    <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=f5fdf366"></script>
  </head>
  <body class="landing-page">
    <!-- page-wrapper Start-->
    <div class="page-wrapper landing-page">
      <!-- Page Body Start            -->
      <div class="landing-home">
        <div class="container-fluid">
          <div class="sticky-header">
            <header>
              <nav class="navbar navbar-b navbar-trans navbar-expand-xl fixed-top nav-padding" id="sidebar-menu">
                <a class="navbar-brand p-0" href="#"><img class="img-fluid" src="{{asset('assets/images/landing/landing_logo.png')}}" alt=""></a>
                <button class="navbar-toggler navabr_btn-set custom_nav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation" data-bs-original-title="" title=""><span></span><span></span><span></span></button>
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
      <section class="section-space cuba-demo-section landing-home">
        <div class="container">
          <div class="row mt-5">
            <div class="col-xl-4 col-lg-6">
              <div class="content">
                <div>
                  <h1 class="wow fadeIn">Buy 24K 99.9% Pure Gold</h1> 
                  <br>
                  <h1 class="wow fadeIn">One stop  </h1>
                  <h1 class="wow fadeIn">For all Finance Management Platform</h1>
                  <h2 class="txt-secondary wow fadeIn">Flexible, Faster & Hassel-Free.</h2>
                  <!-- <p class="mt-3 wow fadeIn">Cuba Admin Design makes your project modern, clean and reduce your project integration time. cuba comes with 10+ Apps , Dark Mode and RTL Ready</p> -->
                </div>
              </div>
            </div>
            <div class="col-xl-8 col-lg-6">                 
              <div class="wow fadeIn">
                <div class="row">
                  <div class="col-xl-6 col-lg-12 xl-50 morning-sec box-col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="hover-link">
                              <h3 style="color: silver;">SILVER</h3>
                              <h5 style="color: silver;">Buy<span id="silverRate"></span></h5>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <form class="theme-form needs-validation" novalidate="" action="{{url('augmont/silverBuy')}}" method="POST" id="silverBuy">
                          {{ csrf_field() }}
                          <div class="row mb-3"></div>
                          <div class="row mb-3">
                            <div class="col"><input class="form-control" required="" type="number" min="1" step="0.0001" id="silverGrams" name="silverGrams" placeholder="Grams" data-bs-original-title="" title="" oninput="validity.valid||(value='');"></div>
                            <div class="col-auto"><i class="fa fa-exchange" style="font-size: 40px;"></i></div>
                            <div class="col"><input class="form-control" required id="silverAmount" min="100" step="0.01" name="silverAmount" placeholder="Amount" data-bs-original-title="" title="" oninput="validity.valid||(value='');"></div>
                          </div>
                          <div class="col"><input class="form-control" type="hidden" id="silverPrice" name="silverPrice" placeholder="silverPrice" data-bs-original-title="" title=""></div>
                          <div class="col"><input class="form-control" type="hidden" id="silverGST" name="silverGST" placeholder="silverGST" data-bs-original-title="" title=""></div>
                          <div class="col"><input class="form-control" type="hidden" id="silverBlockId" name="silverBlockId" placeholder="silverBlockId" data-bs-original-title="" title=""></div>
                          <div class="row mb-3">
                            <div class="col-md-5"></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-5">
                              <div class="btn-group" style="float: right">
                                <button class="btn btn-light btn-lg silver_add" data-val="500" data-bs-original-title="" title="">₹&nbsp;500</button>
                                <button class="btn btn-light btn-lg silver_add" data-val="1000" data-bs-original-title="" title="">₹&nbsp;1000</button>
                                <button class="btn btn-light btn-lg silver_add" data-val="5000" data-bs-original-title="" title="">₹&nbsp;5000</button>
                              </div>
                            </div>
                          </div>
                            <!-- <div class="col-md-4">
                              <div class="row mb-3">
                                <button class="btn btn-danger btn-lg btn-block" type="button" data-bs-toggle="tooltip" title="" data-bs-original-title="btn btn-danger" data-original-title="btn btn-success btn-lg">Monthly SIP</button>
                              </div>
                            </div> -->
                            <div class="row mb-3">
                          <div class="card-footer text-center">
                            <button class="btn btn-danger btn-lg" type="submit" data-bs-toggle="tooltip" title="" data-bs-original-title="Quick Silver Buy" data-original-title="btn btn-success btn-lg">Quick Buy</button>
                          </div></div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-12 xl-50 morning-sec box-col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="hover-link">
                              <h3 style="color: gold;">GOLD</h3>
                              <h5 style="color: gold;">Buy<span id="goldRate"></span></h5>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <form class="theme-form needs-validation" novalidate="" action="{{url('augmont/goldBuy')}}" method="POST" id="goldBuy">
                          {{ csrf_field() }}
                          <div class="row mb-3"></div>
                          <div class="row mb-3">
                            <div class="col"><input class="form-control" required="" type="number" min="0.1" step="0.0001" id="goldGrams" name="goldGrams" placeholder="Grams" data-bs-original-title="" title="" oninput="validity.valid||(value='');"></div>
                            <div class="col-auto"><i class="fa fa-exchange" style="font-size: 40px;"></i></div>
                            <div class="col"><input class="form-control" required in="100" id="goldAmount" step="0.01" name="goldAmount" placeholder="Amount" data-bs-original-title="" title="" oninput="validity.valid||(value='');"></div>
                          </div>
                          <div class="col"><input class="form-control" type="hidden" id="goldPrice" name="goldPrice" placeholder="goldPrice" data-bs-original-title="" title=""></div>
                          <div class="col"><input class="form-control" type="hidden" id="goldGST" name="goldGST" placeholder="goldGST" data-bs-original-title="" title=""></div>
                          <div class="col"><input class="form-control" type="hidden" id="goldBlockId" name="goldBlockId" placeholder="goldBlockId" data-bs-original-title="" title=""></div>
                          <div class="row mb-3">
                            <div class="col-md-5"></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-5">
                              <div class="btn-group" style="float: right">
                                <button class="btn btn-light btn-lg gold_add" data-val="500" data-bs-original-title="" title="">₹&nbsp;500</button>
                                <button class="btn btn-light btn-lg gold_add" data-val="1000" data-bs-original-title="" title="">₹&nbsp;1000</button>
                                <button class="btn btn-light btn-lg gold_add" data-val="5000" data-bs-original-title="" title="">₹&nbsp;5000</button>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <!-- <div class="col-md-4">
                              <div class="row mb-3">
                                <button class="btn btn-danger btn-lg btn-block" type="button" data-bs-toggle="tooltip" title="" data-bs-original-title="btn btn-danger" data-original-title="btn btn-success btn-lg">Monthly SIP</button>
                              </div>
                            </div> -->
                          <div class="card-footer text-center">
                            <button class="btn btn-danger btn-lg" type="submit" data-bs-toggle="tooltip" title="" data-bs-original-title="Quick Gold Buy" data-original-title="btn btn-success btn-lg">Quick Buy</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="wow fadeIn"><img class="screen2" src="{{asset('assets/images/landing/screen2.jpg')}}" alt=""></div> -->
            </div>
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section layout" id="gold">
        <div class="container">
          <div class="row demo-imgs">
            <div class="col-lg-6 col-sm-6 wow pulse demo-content">
              <div class="title-wrapper">
                <div class="content">
                  <div class="col-sm-12 wow pulse">
                    <div class="cuba-demo-content">
                      <div class="couting">
                        <h2>WHAT IS DIGITALGOLD</h2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6 wow pulse demo-content">
              <p>&#39;Optymoney Digital Gold is a trusted and transparent method of purchasing 24 Karat pure gold to help you start your golden savings journey with the trust of Optymoney and powered by Augmont. You can start with as low as INR 100. You can also exchange your Digital Gold for Physical Gold. . We’re here to make buying gold absolutely seamless for you.</p>
            </div>
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section components-section landing-home" id="gold">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content">
                <div class="couting">
                  <h2>KEY FEATURES</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row demo-imgs">
            <div class="col-xs-6 col-md-3  wow pulse demo-content features">
              <div class="title-wrapper">
                <div class="content">
                  <h3 class="theme-name mb-0 txt-secondary">Safety Guaranteed</h3>
                </div>
              </div>
              <div class="cuba-demo-img">
                <!-- <img class="img-fluid" src="{{asset('assets/images/landing/layout-images/dubai.jpg')}}" alt="default"> -->
                <p>Unlike physical gold, it is virtually bought and you don’t have to worry about theft or expensive locker fees. Your gold is safely stored with us. This is powered by Augmont and backed by the Trust of Optymoney</p>
              </div>
            </div>
            <div class="col-xs-6 col-md-3 wow pulse demo-content features">
              <div class="title-wrapper">
                <div class="content">
                  <h3 class="theme-name mb-0 txt-secondary">Sell anytime from home</h3>
                </div>
              </div>
              <div class="cuba-demo-img">
                <!-- <img class="img-fluid" src="{{asset('assets/images/landing/layout-images/newyork.jpg')}}" alt="centralize"> -->
                <p>Sell anytime, without going anywhere and receive money direct in your account.</p>
              </div>
            </div>
            <div class="col-xs-6 col-md-3 wow pulse demo-content features">
              <div class="title-wrapper">
                <div class="content">
                  <h3 class="theme-name mb-0 txt-secondary">Convert to physical gold</h3>
                </div>
              </div>
              <div class="cuba-demo-img">
                <!-- <img class="img-fluid" src="{{asset('assets/images/landing/layout-images/paris.jpg')}}" alt="classicSidebar"> -->
                <p>You can convert your digital gold to physical gold anytime in the form of jewellery in the partner stores.</p>
              </div>
            </div>
            <div class="col-xs-6 col-md-3 wow pulse demo-content features">
              <div class="title-wrapper">
                <div class="content">
                  <h3 class="theme-name mb-0 txt-secondary">Buy as low as ₹100</h3>
                </div>
              </div>
              <div class="cuba-demo-img">
                <!-- <img class="img-fluid" src="{{asset('assets/images/landing/layout-images/moscow.jpg')}}" alt="collapse"> -->
                <p>Digital gold does not require a large sum of money for buying. You can buy based on your budget.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="section-space cuba-demo-section email_bg">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2>HOW IT WORKS</h2>
                  <p>Bringing convenience and safety to buying Gold!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="card-body">
              <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
              <!-- <li class="nav-item"><a class="nav-link active" id="pills-warninghome-tab" data-bs-toggle="pill" href="#pills-warninghome" role="tab" aria-controls="pills-warninghome" aria-selected="true" data-bs-original-title="" title=""><i class="icofont icofont-ui-home"></i>BUY</a></li> -->
                <li class="nav-item"><a class="nav-link active" id="pills-warninghome-tab" data-bs-toggle="pill" href="#pills-warninghome" role="tab" aria-controls="pills-warninghome" aria-selected="true" data-bs-original-title="" title="">BUY</a></li>
                <li class="nav-item"><a class="nav-link" id="pills-warningprofile-tab" data-bs-toggle="pill" href="#pills-warningprofile" role="tab" aria-controls="pills-warningprofile" aria-selected="false" data-bs-original-title="" title=""></i>SELL</a></li>
                <li class="nav-item"><a class="nav-link" id="pills-warningcontact-tab" data-bs-toggle="pill" href="#pills-warningcontact" role="tab" aria-controls="pills-warningcontact" aria-selected="false" data-bs-original-title="" title="">EXCHANGE</a></li>
              </ul>
              <div class="tab-content" id="pills-warningtabContent">
                <div class="tab-pane fade show active" id="pills-warninghome" role="tabpanel" aria-labelledby="pills-warninghome-tab">
                  <div class="row demo-imgs mt-4">
                    <div class="col-xs-6 col-md-4  wow demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 1</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Login</h5>
                        <p>Login or Register with Optymoney. Complete your account setup with eKYC</p>
                      </div>
                    </div>
                    <div class="col-xs-6 col-md-4 wow demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 2</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Enter Amount</h5>
                        <p>Enter your amount in rupees or gold in grams to buy</p>
                      </div>
                    </div>
                    <div class="col-xs-6 col-md-4 wow demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 3</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Payment</h5>
                        <p>Choose your payment method. You will have multiple payment options to choose from such as an account, card, or wallet.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-warningprofile" role="tabpanel" aria-labelledby="pills-warningprofile-tab">
                  <div class="row demo-imgs mt-4">
                    <div class="col-xs-6 col-md-4 demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 1</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Enter Amount</h5>
                        <p>Enter the amount in rupees or gold in grams to sell</p>
                      </div>
                    </div>
                    <div class="col-xs-6 col-md-4 demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 2</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Bank Details</h5>
                        <p>Fill in the bank details. We’ll be verifying your bank account details.</p>
                      </div>
                    </div>
                    <div class="col-xs-6 col-md-4 demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 3</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Amount Credit</h5>
                        <p>Once the transaction is successful, the gold sold amount will be credited to the bank account within 2-3 working days</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-warningcontact" role="tabpanel" aria-labelledby="pills-warningcontact-tab">
                  <div class="row demo-imgs mt-4">
                    <div class="col-xs-6 col-md-4 demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 1</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Choose product</h5>
                        <p>In case you are buying online, choose the products which you want to buy & add them to the cart. You can also visit our store and redeem your digital gold.</p>
                      </div>
                    </div>
                    <div class="col-xs-6 col-md-4 demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 2</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Redeem Digi Gold</h5>
                        <p>In case you are buying online, on payment page choose Digital Gold, then select Tanishq Digital Gold & Other Digital Gold. Balances can be redeemed for jewelry purchase online. Get in touch with the store manager for store redemption</p>
                      </div>
                    </div>
                    <div class="col-xs-6 col-md-4 demo-content features">
                      <div class="title-wrapper">
                        <div class="content"><h3 class="theme-name1 mb-0">STEP 3</h3></div>
                      </div>
                      <div class="cuba-demo-img">
                        <h5>Payment</h5>
                        <p>The remaining amount can be made by other payment methods & order is placed for online transactions. In case you are buying from our store, our store manager and cashier will help you with the complete transaction</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- <section class="section-space cuba-demo-section app_bg">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 wow pulse" style="visibility: visible; animation-name: pulse;">
              <div class="cuba-demo-content email-txt text-start">
                <div class="couting">
                  <h2> Email</h2>
                  <p> Cuba comes with below six email template.</p>
                  <ul class="landing-ul">
                    <li>Basic template</li>
                    <li>Basic With Header template</li>
                    <li>Ecommerce template</li>
                    <li>Ecommerce-2 template</li>
                    <li>Ecommerce-3 template</li>
                    <li>Order Success template</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-8 wow pulse" style="visibility: visible; animation-name: pulse;"><a href="index.html" data-bs-original-title="" title=""><img class="img-fluid email-img" src="../assets/images/landing/email_section_img.png" alt=""></a></div>
          </div>
        </div>
      </section> -->
      <section class="section-space cuba-demo-section components-section landing-home" id="tax">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <h2>NEED HELP?</h2>
                  <p>Kindly drop in your number and we will get in touch with you. You can email us – support@optymoney.com</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-sm-12 wow pulse">
              <div class="cuba-demo-content mt50">
                <div class="couting">
                  <form class="row row-cols-sm-3 theme-form mt-3 form-bottom needs-validation" novalidate="" method="POST" id="callbackRequestForm">
                  {{ csrf_field() }}
                    <div class="mb-3 d-flex">
                      <input class="form-control" type="text" name="name" id="name" placeholder="Name" autocomplete="off" required>
                    </div>
                    <div class="mb-3 d-flex">
                      <input class="form-control" id="mobile" name="mobile" type="text" placeholder="Mobile" autocomplete="off" required>
                    </div>
                    <div class="mb-3 d-flex">
                      <button class="btn btn-secondary" id="callbackRequest">Request Callback</button>
                    </div>
                  </form>
                  <div id="callbackResponse"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
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
                  <div class="col-md-2">
                    <!-- <div class="social-links float-right">
                      <a href="https://web.whatsapp.com/send?phone=+917411011280" target="_blank" class="whatsapp" style="width: 42px; height: 42px; border-radius: 50%; background-color: var(--theme-deafult); color: #FFF; padding: 4px 12px; display: inline-block; font-size: large;"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                      <a href="https://bit.ly/optytwitter" target="_blank" class="twitter" style="width: 42px; height: 42px; border-radius: 50%; background-color: var(--theme-deafult); color: #FFF; padding: 4px 12px; display: inline-block; font-size: large;"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                      <a href="https://bit.ly/optyfb" target="_blank" class="facebook" style="width: 42px; height: 42px; border-radius: 50%; background-color: var(--theme-deafult); color: #FFF; padding: 4px 12px; display: inline-block; font-size: large;"><i class="fa fa-facebook-f" aria-hidden="true"></i></a>
                      <a href="https://bit.ly/optyinsta" target="_blank" class="instagram" style="width: 42px; height: 42px; border-radius: 50%; background-color: var(--theme-deafult); color: #FFF; padding: 4px 12px; display: inline-block; font-size: large;"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                      <a href="https://bit.ly/optylinkedin" target="_blank" class="linkedin" style="width: 42px; height: 42px; border-radius: 50%; background-color: var(--theme-deafult); color: #FFF; padding: 4px 12px; display: inline-block; font-size: large;"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    </div> -->
                  </div>
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
    <script src="{{asset('assets/js/config.js')}}"></script>
    <!-- Plugins JS start-->
    <script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
    <script src="{{asset('assets/js/tooltip-init.js')}}"></script>
    <script src="{{asset('assets/js/animation/wow/wow.min.js')}}"></script>
    <script src="{{asset('assets/js/landing_sticky.js')}}"></script>
    <script src="{{asset('assets/js/landing.js?v=1.0')}}"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154419016-1"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
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