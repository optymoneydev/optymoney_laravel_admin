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
								<span id="goldRate"></span> /gm
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
										<div class="row  ">
											<div class="col-6 col-sm-4 col-md py-3">
												<h6 class="pb-2 ">Quantity</h6>
												<div class="dg-minibold"><span class="rupees" id="goldQuantity">{{ round($goldAmount/$goldPrice, 4) }}</span> grams</div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3">
												<h6 class="pb-2 ">Amount</h6>
												<div class="dg-minibold"><span class="rupees" id="goldAmount">₹ {{ $goldAmount }}</span> </div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3 ">
												<h6 class="pb-2 ">Tax</h6>
												<div class="dg-minibold"><span class="rupees" id="goldTax">₹ {{ round(($goldAmount/$goldPrice) * $goldGST, 2) }}</span> </div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3 ">
												<h6 class="pb-2 "> Total Amount </h6>
												<div class="dg-minibold"><span class="rupees" id="goldTotalAmount">₹ {{ round($goldAmount + (($goldAmount/$goldPrice) * $goldGST), 2) }}</span></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<input class="form-control" id="lockPrice" name="lockPrice" type="hidden" placeholder="Lock Price" required="required" value="{{ $goldPrice }}">
							<input class="form-control" id="metalType" name="metalType" type="hidden" placeholder="Metal Type" required="required" value="gold">
							<input class="form-control" id="quantity" name="quantity" type="hidden" placeholder="gold Grams Input" required="required" value="{{ round($goldAmount/$goldPrice, 4) }}">
							<input class="form-control" id="blockId" name="blockId" type="hidden" placeholder="gold BlockId Input" required="required" value="{{ $goldBlockId }}">
							<input class="form-control" id="totalTaxAmount" name="totalTaxAmount" type="hidden" placeholder="gold Tax Amount Input" required="required" value="{{ round(($goldAmount/$goldPrice) * $goldGST, 2) }}">
							<input class="form-control" id="preTaxAmount" name="preTaxAmount" type="hidden" placeholder="gold Pre Tax Amount Input" required="required" value="{{ round($goldAmount, 2) }}">
							<input class="form-control" id="totalAmount" name="totalAmount" type="hidden" placeholder="gold Amount Input" required="required" value="{{ round($goldAmount + (($goldAmount/$goldPrice) * $goldGST), 2) }}">
						</div>
					</div>
					<div class="card-footer text-center">
						<button class="btn btn-primary" type="button" id="proceedToPay">Submit</button>
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