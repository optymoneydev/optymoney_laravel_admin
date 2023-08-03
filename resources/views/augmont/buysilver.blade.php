@extends('layouts.simple.master')
@section('title', 'Buy Silver')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Buy Silver</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Buy</li>
<li class="breadcrumb-item active">Silver</li>
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
							<h5>Buy Silver</h5>
                    	</div>
                    	<div class="col-md-6">
							<p class="h4 txt-info" style="float: right;">
								<h5">SILVER</h5>
								<br>
								<span id="silverRate"></span> /gm
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
												<div class="dg-minibold"><span class="rupees" id="silverQuantity">{{ round($silverAmount/$silverPrice, 4) }}</span> grams</div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3">
												<h6 class="pb-2 ">Amount</h6>
												<div class="dg-minibold"><span class="rupees" id="silverAmount">₹ {{ round($silverAmount, 2) }}</span> </div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3 ">
												<h6 class="pb-2 ">Tax</h6>
												<div class="dg-minibold"><span class="rupees" id="silverTax">₹ {{ round(($silverAmount/$silverPrice) * $silverGST, 2) }}</span> </div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3 ">
												<h6 class="pb-2 "> Total Amount </h6>
												<div class="dg-minibold"><span class="rupees" id="silverTotalAmount">₹ {{ round($silverAmount + (($silverAmount/$silverPrice) * $silverGST), 2) }}</span></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<input class="form-control" id="lockPrice" name="lockPrice" type="hidden" placeholder="Lock Price" required="required" value="{{ $silverPrice }}">
							<input class="form-control" id="metalType" name="metalType" type="hidden" placeholder="Metal Type" required="required" value="silver">
							<input class="form-control" id="quantity" name="quantity" type="hidden" placeholder="silver Grams Input" required="required" value="{{ round($silverAmount/$silverPrice, 4) }}">
							<input class="form-control" id="blockId" name="blockId" type="hidden" placeholder="silver BlockId Input" required="required" value="{{ $silverBlockId }}">
							<input class="form-control" id="totalTaxAmount" name="totalTaxAmount" type="hidden" placeholder="silver Tax Amount Input" required="required" value="{{ round(($silverAmount/$silverPrice) * $silverGST, 2) }}">
							<input class="form-control" id="preTaxAmount" name="preTaxAmount" type="hidden" placeholder="silver Pre Tax Amount Input" required="required" value="{{ round($silverAmount, 2) }}">
							<input class="form-control" id="totalAmount" name="totalAmount" type="hidden" placeholder="silver Pre Tax Amount Input" required="required" value="{{ round($silverAmount + (($silverAmount/$silverPrice) * $silverGST), 2) }}">
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