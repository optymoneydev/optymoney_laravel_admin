@extends('layouts.simple.master')
@section('title', 'Helpdesk Ticket')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/photoswipe.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Helpdesk Ticket</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Helpdesk</li>
<li class="breadcrumb-item active">Ticket</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="user-profile social-app-profile email-wrap">
		<div class="row">
			{{ $helpdesk }}
			<!-- user profile first-style start-->
		</div>
		<div class="tab-content" id="top-tabContent">
			<div class="tab-pane fade show active" id="timeline" role="tabpanel" aria-labelledby="timeline">
				<div class="row">
					<div class="col-xl-3 xl-40 col-lg-12 col-md-5 box-col-4">
						<div class="default-according style-1 faq-accordion job-accordion" id="accordionoc4">
							<div class="row">
								<div class="col-xl-12">
									<div class="card">
										<div class="card-header">
											<h5 class="mb-0">
												<button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseicon" aria-expanded="true" aria-controls="collapseicon">My Profile</button>
											</h5>
										</div>
										<div class="collapse show" id="collapseicon" aria-labelledby="collapseicon" data-bs-parent="#accordion">
											<div class="card-body socialprofile filter-cards-view">
												<div class="media">
													<img class="img-50 img-fluid m-r-20 rounded-circle" src="{{asset('assets/images/user/1.jpg')}}" alt="">
													<div class="media-body">
														<h6 class="font-primary f-w-600">{{ $helpdesk['cust_name'] }}</h6>
													</div>
												</div>
												<hr>
												<!-- <div class="social-btngroup d-flex">
													<button class="btn btn-primary text-center" type="button">Likes</button>
													<button class="btn btn-light text-center" type="button">View</button>
												</div> -->
												<div class="media">
													<div class="media-body">
														<span class="f-w-600 d-block">PAN</span>
														<span class="d-block">{{ $helpdesk['pan'] }}</span>
													</div>
												</div>
												<div class="media">
													<div class="media-body">
														<span class="f-w-600 d-block">Aadhaar Number</span>
														<span class="d-block">{{ $helpdesk['aadhaar'] }}</span>
													</div>
												</div>
												<div class="media">
													<div class="media-body">
														<span class="f-w-600 d-block">Mobile</span>
														<span class="d-block">{{ $helpdesk['contact_no'] }}</span>
													</div>
												</div>
												<div class="media">
													<div class="media-body">
														<span class="f-w-600 d-block">Email</span>
														<span class="d-block">{{ $helpdesk['login_id'] }}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-12">
									<div class="card">
										<div class="card-header">
											<h5 class="mb-0">
												<button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseicon" aria-expanded="true" aria-controls="collapseicon">Status Update</button>
											</h5>
										</div>
										<div class="collapse show" id="collapseicon" aria-labelledby="collapseicon" data-bs-parent="#accordion">
											<div class="card-body socialprofile filter-cards-view">
												<form class="needs-validation" novalidate="" id="updateHelpdeskStatus" method="POST">
													{{ csrf_field() }}
													<div class="col-md-12 mb-4">
														<div class="form-group">
															<label class="mandatory mandatory_label">Status</label>
															<select class="form-control" name="status" required="">
																<option value="open">Open</option>
																<option value="assigned">Assigned</option>
																<option value="pending">Pending</option>
																<option value="filed">Filed</option>
																<option value="e_verified">E-Verified</option>
																<option value="paid">Paid</option>
																<option value="closed">Closed</option>
															</select>
															<input type="text" class="form-control" value="{{ $helpdesk['id'] }}" id="statusId" name="statusId">
															<div class="invalid-feedback">Please select the assessment year</div>
														</div>
													</div>
													<div class="col-md-12 mb-4">
														<div class="form-group">
															<label class="mandatory mandatory_label">Assigned To</label>
															<select class="form-control col-sm-12" name="assigned_to" id="assigned_to" data-select2-id="assigned_to" tabindex="-1" aria-hidden="true" required="">
																<option value="" data-select2-id="2">Select</option>
																@foreach ($empdata as $emp)
																	<option value="{{ $emp['pk_emp_id'] }}">{{ ucfirst($emp['full_name']) }}</option>
																@endforeach
															</select>
															<div class="invalid-feedback">Please select the employee</div>
														</div>
													</div>
													<div class="col-md-12 mb-4">
														<div class="form-group">
															<label class="mandatory">Remarks</label>
															<textarea class="form-control" name="helpdesk_remarks" id="helpdesk_remarks" rows="3"></textarea>
														</div>
													</div>
													<div class="col-md-12 text-center mb-4">
														<button type="submit" class="btn btn-primary" id="btn_upload" name="btn_upload" value="upload">Update</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-12">
									<div class="card">
										<div class="card-header">
											<h5 class="mb-0">
												<button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseicon1" aria-expanded="true" aria-controls="collapseicon1">Bank Details</button>
											</h5>
										</div>
										<div class="collapse show" id="collapseicon1" data-bs-parent="#accordion" aria-labelledby="collapseicon1">
											<div class="card-body social-status filter-cards-view">
												@foreach ($bankdata as $bank)
													<div class="media">
														<img class="img-50 rounded-circle m-r-15" src="{{asset('assets/images/bank/sbi.png')}}" alt="">
														<div class="media-body">
															<span class="f-w-600 d-block">Bank Name : {{$bank['bank_name']}}</span>
															<span class="f-w-600 d-block">A/c No.   : {{$bank['acc_no']}}</span>
															<span class="f-w-600 d-block">IFSC Code : {{$bank['ifsc_code']}}</span>
														</div>
													</div>
												@endforeach
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-12">
									<div class="card">
										<div class="card-header">
											<h5 class="mb-0">
												<button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseicon12" aria-expanded="true" aria-controls="collapseicon12">Files</button>
											</h5>
										</div>
										<div class="collapse show" id="collapseicon12" aria-labelledby="collapseicon12" data-bs-parent="#accordion">
											<div class="card-body social-status filter-cards-view">
												@foreach (explode('|',$helpdesk['file']) as $file) 
													@if ($file!="")
														<div class="media">
															<img class="img-50 rounded-circle m-r-15" src="{{asset('assets/images/bank/sbi.png')}}" alt="">
															<div class="media-body">
																<a target="_blank" href="https://optymoney.com/__uploaded.files/helpdesk/{{ $helpdesk['user_id'] }}/{{ $file }}">Download</a>
															</div>
														</div>
													@endif
												@endforeach
												<div class="media">
													<img class="img-50 rounded-circle m-r-15" src="{{asset('assets/images/user/5.jpg')}}" alt="">
													<div class="media-body">
														<span class="f-w-600 d-block">Comeren Diaz</span>
														<p>Commented on Shaun Park's <a href="#">Photo</a></p>
														<span class="light-span">6 days Ago</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-9 xl-60 col-lg-12 col-md-7 box-col-8">
						<div class="email-wrap">
							<div class="row">
								<div class="col-sm-12">
									<div class="email-right-aside">
										<div class="card email-body radius-left">
											<div class="ps-0">
												<div class="tab-content">
													<div class="tab-pane fade" id="pills-darkhome" role="tabpanel" aria-labelledby="pills-darkhome-tab">
														<div class="email-compose">
															<div class="email-top compose-border">
																<div class="row">
																	<div class="col-sm-8 xl-50">
																		<h4 class="mb-0">New Message</h4>
																	</div>
																	<div class="col-sm-4 btn-middle xl-50">
																		<button class="btn btn-primary btn-block btn-mail text-center mb-0 mt-0" type="button"><i class="fa fa-paper-plane me-2"></i> SEND</button>
																	</div>
																</div>
															</div>
															<div class="email-wrapper">
																<form class="theme-form">
																	<div class="mb-3">
																		<label class="col-form-label pt-0" for="exampleInputEmail1">To</label>
																		<input class="form-control" id="exampleInputEmail1" type="email">
																	</div>
																	<div class="mb-3">
																		<label for="exampleInputPassword1">Subject</label>
																		<input class="form-control" id="exampleInputPassword1" type="text">
																	</div>
																	<div class="mb-3 mb-0">
																		<label class="text-muted">Message</label>
																		<textarea id="text-box" name="text-box" cols="10" rows="2">                                                            </textarea>
																	</div>
																</form>
															</div>
														</div>
													</div>
													<div class="tab-pane fade active show" id="pills-darkprofile" role="tabpanel" aria-labelledby="pills-darkprofile-tab">
														<div class="email-content">
															<div class="email-top">
																<div class="row">
																	<div class="col-md-6 xl-100 col-sm-12">
																		<div class="media">
																			<img class="me-3 rounded-circle" src="{{asset('assets/images/user/user.png')}}" alt="">
																			<div class="media-body">
																				<h6 class="mb-0 f-w-700">{{ $helpdesk['cust_name'] }}</h6>
																				<p>{{ $helpdesk['upload_date'] }} - January, 12,2019</p>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-sm-12">
																		<div class="float-end d-flex">
																			<p class="user-emailid">{{ $helpdesk['login_id'] }}</p>
																			<!-- <i class="fa fa-star-o f-18 mt-1"></i> -->
																		</div>
																	</div>
																</div>
															</div>
															<div class="email-wrapper">
																<p>Hello</p>
																<p>Dear Sir Good Morning,</p>
																<h5>Elementum varius nisi vel tempus. Donec eleifend egestas viverra.</h5>
																<p class="m-b-20">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur non diam facilisis, commodo libero et, commodo sapien. Pellentesque sollicitudin massa sagittis dolor facilisis, sit amet vulputate nunc molestie. Pellentesque maximus nibh id luctus porta. Ut consectetur dui nec nulla mattis luctus. Donec nisi diam, congue vitae felis at, ullamcorper bibendum tortor. Vestibulum pellentesque felis felis. Etiam ac tortor felis. Ut elit arcu, rhoncus in laoreet vel, gravida sed tortor.</p>
																<p>In elementum varius nisi vel tempus. Donec eleifend egestas viverra. Donec dapibus sollicitudin blandit. Donec scelerisque purus sit amet feugiat efficitur. Quisque feugiat semper sapien vel hendrerit. Mauris lacus felis, consequat nec pellentesque viverra, venenatis a lorem. Sed urna lectus.Quisque feugiat semper sapien vel hendrerit</p>
																<hr>
																<div class="d-inline-block">
																	<h6 class="text-muted"><i class="icofont icofont-clip"></i> ATTACHMENTS</h6>
																	<a class="text-muted text-end right-download" href="#"><i class="fa fa-long-arrow-down me-2"></i>Download All</a>
																	<div class="clearfix"></div>
																</div>
																<div class="attachment">
																	<ul class="list-inline">
																	<li class="list-inline-item"><img class="img-fluid" src="{{asset('assets/images/email/1.jpg')}}" alt=""></li>
																	<li class="list-inline-item"><img class="img-fluid" src="{{asset('assets/images/email/2.jpg')}}" alt=""></li>
																	<li class="list-inline-item"><img class="img-fluid" src="{{asset('assets/images/email/3.jpg')}}" alt=""></li>
																	</ul>
																</div>
																<hr>
																<div class="action-wrapper">
																	<ul class="actions">
																	<li><a class="text-muted" href="#"><i class="fa fa-reply me-2"></i>Reply</a></li>
																	<li><a class="text-muted" href="#"><i class="fa fa-reply-all me-2"></i>Reply All</a></li>
																	<li><a class="text-muted" href="#"><i class="fa fa-share me-2"></i></a>Forward</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										</div>
									</div>
									<div class="card">
										<div class="card-body">
											<div class="new-users-social">
												<div class="media">
													<img class="rounded-circle image-radius m-r-15" src="{{asset('assets/images/user/1.jpg')}}" alt="">
													<div class="media-body">
														<h6 class="mb-0 f-w-700">{{ $helpdesk['cust_name'] }}</h6>
														<p>{{ $helpdesk['upload_date'] }} - January, 12,2019</p>
													</div>
													<span class="pull-right mt-0"><i data-feather="more-vertical"></i></span>
												</div>
											</div>
											<div class="timeline-content">
												<p>{{ $helpdesk['description'] }}</p>
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
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.js')}}"></script>
@endsection