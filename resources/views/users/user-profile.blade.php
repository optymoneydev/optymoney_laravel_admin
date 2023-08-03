@extends('layouts.simple.master')
@section('title', 'User Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/rating.css')}}">
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
					<!-- <div class="cardheader"></div> -->
					<div class="user-image">
						<div class="avatar"><img alt="" src="{{asset('assets/images/dashboard/profile.jpg')}}"></div>
						<!-- <div class="icon-wrapper"><i class="icofont icofont-pencil-alt-5"></i></div> -->
					</div>
					<div class="info">
						<div class="row">
							<div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
								<div class="row">
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-envelope"></i>   Email</h6>
											<span>{{ $login_id ?? '' }}</span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-calendar"></i>   BOD</h6>
											<span>{{ $dob ?? '' }}</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
								<div class="user-designation">
									<div class="title">{{ $cust_name ?? '' }}</div>
									<!-- <div class="desc mt-2">designer</div> -->
								</div>
							</div>
							<div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
								<div class="row">
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-phone"></i>   Contact Us</h6>
											@if(!is_null($contact))
											<span>India +91 {{ $contact ?? '' }}</span>
											@else
											<span>India +91 {{ $contact_no ?? '' }}</span>
											@endif
										</div>
									</div>
									<div class="col-md-6">
										<div class="ttl-info text-start">
											<h6><i class="fa fa-location-arrow"></i>   Location</h6>
											<span>{{ $city ?? '' }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<!-- <div class="social-media">
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
						</div> -->
					</div>
				</div>
			</div>
			<!-- user profile first-style end-->
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