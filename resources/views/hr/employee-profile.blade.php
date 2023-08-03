@extends('layouts.simple.master')
@section('title', 'User Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/photoswipe.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>User Profile</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Users</li>
<li class="breadcrumb-item active">User Profile</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="user-profile">
		<div class="row">
			<!-- user profile first-style start-->
			<div class="col-sm-12">
				<div class="card hovercard text-center">
					<div class="cardheader"></div>
					<div class="user-image">
						<div class="avatar"><img alt="" src="{{asset('assets/images/user/7.jpg')}}"></div>
						<div class="icon-wrapper"><i class="icofont icofont-pencil-alt-5"></i></div>
					</div>
					<div class="info">
						<div class="row">
							<div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
								<div class="row">
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-envelope"></i>   Email</h6>
											<span>{{ $employee->official_email }}</span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-calendar"></i>   BOD</h6>
											<span>{{ $employee->dob }}</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
								<div class="user-designation">
									<div class="title"><a target="_blank" href="">{{ $employee->full_name }}</a></div>
									<div class="desc mt-2">{{ $employee->department }}, {{ $employee->role }}</div>
								</div>
							</div>
							<div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
								<div class="row">
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-phone"></i>   Contact Us</h6>
											<span>India +91 {{ $employee->official_mobile }}</span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-location-arrow"></i>   Location</h6>
											<span>{{ $employee->permanent_address_line1." ".$employee->permanent_address_line2." ".$employee->permanent_city." ".$employee->permanent_state }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="social-media">
							<ul class="list-inline">
								<li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fa fa-google-plus"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fa fa-instagram"></i></a></li>
								<li class="list-inline-item"><a href="#"><i class="fa fa-rss"></i></a></li>
							</ul>
						</div>
						<div class="follow">
							<div class="row">
								<div class="col-6 text-md-end border-right">
									<div class="follow-num counter">25869</div>
									<span>Follower</span>
								</div>
								<div class="col-6 text-md-start">
									<div class="follow-num counter">659887</div>
									<span>Following</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="card">
					<div class="profile-img-style">
						<div class="row">
							<div class="col-sm-8">
								<div class="media">
									<img class="img-thumbnail rounded-circle me-3" src="{{asset('assets/images/user/7.jpg')}}" alt="Generic placeholder image">
									<div class="media-body align-self-center">
										<h5 class="mt-0 user-name">Personal Details</h5>
									</div>
								</div>
							</div>
							<div class="col-sm-4 align-self-center">
								<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
							</div>
						</div>
						<hr>
						<!-- <p>{{ $employee }}</p> -->
						<div class="info">
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Gender</h6>
										<span>{{ ucfirst($employee->gender) }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-1">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-location-arrow"></i>   Father Name</h6>
										<span>{{ ucfirst($employee->father_name) }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-3 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Marital Status</h6>
										<span>{{ $employee->marital_status }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-4 order-xl-4">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   Spouse Name</h6>
										<span>{{ ucfirst($employee->spouse_name) }}</span>
									</div>
								</div>
							</div>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   Personal Mobile</h6>
										<span>{{ ucfirst($employee->personal_mobile) }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-1">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-location-arrow"></i>   Personal Email</h6>
										<span>{{ $employee->personal_email }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-3 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Alternate Contact Person</h6>
										<span>{{ $employee->alternate_contact_person }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-4 order-xl-3">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Alternate Mobile Number</h6>
										<span>{{ $employee->alternate_contact_mobile }}</span>
									</div>
								</div>
							</div>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-0 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-location-arrow"></i>   PAN</h6>
										<span>{{ $employee->pan }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-1">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   PAN Upload</h6>
										<span>{{ $employee->pan_upload }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Aadhaar Number</h6>
										<span>{{ $employee->aadhar }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-3 order-xl-3">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Aadhaar Upload</h6>
										<span>{{ $employee->aadhar_upload }}</span>
									</div>
								</div>
							</div>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-0 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-location-arrow"></i>   Passport Number</h6>
										<span>{{ $employee->passport_no }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-1">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Passport Upload</h6>
										<span>{{ $employee->passport_upload }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   Qualification</h6>
										<span>{{ $employee->qualification }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-3 order-xl-3">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Qualification Upload</h6>
										<span>{{ $employee->qualification_upload }}</span>
									</div>
								</div>
							</div>
							<hr>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="row">
										<div class="col-sm-8">
											<div class="media">
												<div class="media-body align-self-center">
													<h5 class="mt-0 user-name">Personal Bank Details</h5>
												</div>
											</div>
										</div>
										<div class="col-sm-4 align-self-center">
											<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
										</div>
									</div>
									<hr>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-phone"></i>   Personal Bank Name</h6>
										<span>{{ $employee->personal_bank_name }}&nbsp;</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-location-arrow"></i>   Personal Bank Account</h6>
										<span>{{ $employee->personal_bank_acno }}&nbsp;</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-envelope"></i>   Name as on Bank</h6>
										<span>{{ $employee->personal_name_as_on_bank }}&nbsp;</span>
									</div>
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   IFSC Number</h6>
										<span>{{ $employee->personal_ifsc_code }}&nbsp;</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="row">
										<div class="col-sm-8">
											<div class="media">
												<div class="media-body align-self-center">
													<h5 class="mt-0 user-name">Salary Bank Details</h5>
												</div>
											</div>
										</div>
										<div class="col-sm-4 align-self-center">
											<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
										</div>
									</div>
									<hr>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-phone"></i>   Salary Bank Name</h6>
										<span>{{ $employee->salary_bank_name }}&nbsp;</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-location-arrow"></i>   Salary Bank Account Number</h6>
										<span>{{ $employee->salary_bank_acno }}&nbsp;</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-envelope"></i>   Salary Account - Name as on Bank</h6>
										<span>{{ $employee->salary_name_as_on_bank }}&nbsp;</span>
									</div>
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   IFSC Number</h6>
										<span>{{ $employee->salary_ifsc_code }}&nbsp;</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="row">
										<div class="col-sm-8">
											<div class="media">
												<div class="media-body align-self-center">
													<h5 class="mt-0 user-name">Present Address</h5>
												</div>
											</div>
										</div>
										<div class="col-sm-4 align-self-center">
											<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
										</div>
									</div>
									<hr>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-phone"></i>   Present Address Line1</h6>
										<span>{{ $employee->present_address_line1 }}</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-location-arrow"></i>   Present Address Line2</h6>
										<span>{{ $employee->present_address_line2 }}</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-envelope"></i>   Present City</h6>
										<span>{{ $employee->present_city }}</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-calendar"></i>   Present State</h6>
										<span>{{ $employee->present_state }}</span>
									</div>
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Present Pincode</h6>
										<span>{{ $employee->present_pincode }}</span>
									</div>	
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="row">
										<div class="col-sm-8">
											<div class="media">
												<div class="media-body align-self-center">
													<h5 class="mt-0 user-name">Permanent Address</h5>
												</div>
											</div>
										</div>
										<div class="col-sm-4 align-self-center">
											<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
										</div>
									</div>
									<hr>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-phone"></i>   Permanent Address Line1</h6>
										<span>{{ $employee->permanent_address_line1 }}</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-phone"></i>   Permanent Address Line2</h6>
										<span>{{ $employee->permanent_address_line2 }}</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-envelope"></i>   Permanent City</h6>
										<span>{{ $employee->permanent_city }}</span>
									</div>
									<div class="ttl-info text-start mb-3">
										<h6><i class="fa fa-calendar"></i>   Permanent State</h6>
										<span>{{ $employee->permanent_state }}</span>
									</div>
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Permanent Pincode</h6>
										<span>{{ $employee->permanent_pincode }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="card">
					<div class="profile-img-style">
						<div class="row">
							<div class="col-sm-8">
								<div class="media">
									<img class="img-thumbnail rounded-circle me-3" src="{{asset('assets/images/user/7.jpg')}}" alt="Generic placeholder image">
									<div class="media-body align-self-center">
										<h5 class="mt-0 user-name">Official Details</h5>
									</div>
								</div>
							</div>
							<div class="col-sm-4 align-self-center">
								<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
							</div>
						</div>
						<hr>
						<div class="info">
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   Access Code</h6>
										<span>{{ $employee->access_code }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Designation</h6>
										<span>{{ ucfirst($employee->designation) }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   UAN Number</h6>
										<span>{{ $employee->uan_no }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-location-arrow"></i>   PF Number</h6>
										<span>{{ $employee->pf_no }}</span>
									</div>
								</div>
							</div>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   ESI Number</h6>
										<span>{{ $employee->esi_no }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Official Mobile</h6>
										<span>{{ $employee->official_mobile }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Official Email</h6>
										<span>{{ $employee->official_email }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   D Drive Access</h6>
										<span>{{ $employee->d_drive_access }}</span>
									</div>
								</div>
							</div>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Laptop Name</h6>
										<span>{{ $employee->laptop_name }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-envelope"></i>   Laptop Id</h6>
										<span>{{ $employee->laptop_id }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-1 order-xl-0">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-calendar"></i>   Id Card Issued</h6>
										<span>{{ $employee->id_card }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Authorization Letter</h6>
										<span>{{ $employee->authorization_letter }}</span>
									</div>
								</div>
							</div>
							<div class="row mb-5">
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Exit Date</h6>
										<span>{{ $employee->exit_date }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Employee Status</h6>
										<span>{{ $employee->employee_status }}</span>
									</div>
								</div>
								<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
									<div class="ttl-info text-start">
										<h6><i class="fa fa-phone"></i>   Remarks</h6>
										<span>{{ $employee->remarks }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="pswp__bg"></div>
				<div class="pswp__scroll-wrap">
					<div class="pswp__container">
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
					</div>
					<div class="pswp__ui pswp__ui--hidden">
						<div class="pswp__top-bar">
							<div class="pswp__counter"></div>
							<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
							<button class="pswp__button pswp__button--share" title="Share"></button>
							<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
							<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
							<div class="pswp__preloader">
								<div class="pswp__preloader__icn">
									<div class="pswp__preloader__cut">
										<div class="pswp__preloader__donut"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
							<div class="pswp__share-tooltip"></div>
						</div>
						<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
						<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
						<div class="pswp__caption">
							<div class="pswp__caption__center"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.js')}}"></script>
@endsection