@extends('layouts.simple.master')
@section('title', 'Buy Gold')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Buy Gold</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Buy</li>
<li class="breadcrumb-item active">Gold</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			@if(session()->has('orderData'))
				@if (array_key_exists('augstatusCode', session()->get('orderData')))
					<div class="alert alert-danger">
						@foreach ($errors as $key => $value)
							@foreach ($value[0] as $key1 => $value1)
								{{ $key1 }} - {{ $value1 }}
							@endforeach
						@endforeach
					</div>
				@endif
				@if (array_key_exists('augstatusCode', session()->get('orderData')))
					<div class="alert alert-danger">
						{!!session()->get('orderData')['message']!!}
					</div>
				@endif
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
		<div class="card" id="saveOrderCard">
				<div class="card-header">
					<div class="row">
                    	<div class="col-md-6">
							<h5>Buy Gold</h5>
                    	</div>
                    	<div class="col-md-6">
							<p class="h4 txt-info" style="float: right;">
								<h5">Gold</h5>
								<br>
								<span id="goldSipRate"></span> /gm
							</p>
                    	</div>
                  	</div>
					<span class="font-medium font-teal  m-0">
						This price will be valid for 
						<br class="d-block d-md-none"> next 5 minutes - 
						<span class="font-teal font-bold countdown"></span>
					</span>
				</div>
				<form class="needs-validation" novalidate="" id="saveOrderForm" action="{!!route('augmont.saveOrder')!!}" method="POST" >
					<div class="card-body">
					<div class="text-center dg-black">
							<div class="container">
								<div class="row my-3 mx-auto">
									<div class="col px-auto aug-frame footer-border">
										<div class="row ">
											<div class="col-12">
												<div class="position-relative"></div>
											</div>
										</div>
										<div class="row ">
											<div class="col-8">
												<div class="checkout-details">
													<div class="order-box">
														<ul class="sub-total">
															<li>Plan Name <span class="count">{{ $goldSipInvestmentPurpose }}</span></li>
															<li>Application Date <span class="count">{{ date("Y-m-d") }}</span></li>
															<li>Start Date <span class="count">{{ $goldSipDate }}</span></li>
															<li>SIP Investment Tenure <span class="count">Infinite</span></li>
															<li>SIP Cycle Date <span class="count">{{ $goldSipCycleDate }}th of Month</span></li>
															<li>SIP Amount <span class="count"><span class="rupees" id="goldAmount">₹ {{ round($goldSipAmount, 2) }}</span></span></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- <hr class="">
								<div class="row ">
									<div class="col-12 py-3 ">
										<h5 class="m-0 aug-section-title font-teal">Select Bank Account</h5>
									</div>
								</div>
								<div class="row  ">
									<div class="container">
										<div class="radio-tile-group" id="banksList">
											
										</div>
										<div class="mb-3" id="newbankDetails">
											<div class="row">
												<div class="col">
													<div class="mb-3">
														<input class="form-control" type="text" id="bank_name" name="bank_name" required placeholder="Bank Name" data-bs-original-title="" title="">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="mb-3">
														<input class="form-control" type="text" id="acc_no" name="acc_no" required placeholder="Account Number" data-bs-original-title="" title="">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col">
													<div class="mb-3">
														<input class="form-control" type="text" id="ifsc_code" name="ifsc_code" required placeholder="IFSC Code" data-bs-original-title="" title="">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div> -->
							</div>
						</div>
						<div class="mb-3">
							<input class="form-control" id="sipInvestmentPurpose" name="sipInvestmentPurpose" type="hidden" placeholder="Lock Price" required="required" value="{{ $goldSipInvestmentPurpose }}">
							<input class="form-control" id="sipDate" name="sipDate" type="hidden" placeholder="Lock Price" required="required" value="{{ $goldSipDate }}">
							<input class="form-control" id="lockPrice" name="lockPrice" type="hidden" placeholder="Lock Price" required="required" value="{{ $goldSipPrice }}">
							<input class="form-control" id="metalType" name="metalType" type="hidden" placeholder="Metal Type" required="required" value="gold">
							<input class="form-control" id="quantity" name="quantity" type="hidden" placeholder="gold Grams Input" required="required" value="{{ round($goldSipAmount/$goldSipPrice, 4) }}">
							<input class="form-control" id="blockId" name="blockId" type="hidden" placeholder="gold BlockId Input" required="required" value="{{ $goldSipBlockId }}">
							<input class="form-control" id="amount" name="amount" type="hidden" placeholder="gold Pre Tax Amount Input" required="required" value="{{ round($goldSipAmount, 2) }}">
						</div>
					</div>
					<div class="card-footer text-center">
						<button class="btn btn-primary" type="button" id="proceedToPay_SIP">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{asset('assets/js/augmont/buyorder.js?v=1.0')}}"></script>
@endsection