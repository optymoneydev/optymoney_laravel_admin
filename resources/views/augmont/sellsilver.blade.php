@extends('layouts.simple.master')
@section('title', 'Sell Silver')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Sell Silver</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Sell</li>
<li class="breadcrumb-item active">Silver</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div id="sellStatusMsg">
				
			</div>
			<div class="card" id="saveOrderCard">
				<div class="card-header">
					<div class="row">
                    	<div class="col-md-6">
							<h5>Sell Silver</h5>
                    	</div>
                    	<div class="col-md-6">
							<p class="h4 txt-info" style="float: right;">
								<h5">Silver</h5>
								<br>
								<span id="silverSellRate"></span> /gm
							</p>
                    	</div>
                  	</div>
					<span class="font-medium font-teal  m-0">
						This price will be valid for 
						<br class="d-block d-md-none"> next 5 minutes - 
						<span class="font-teal font-bold countdown"></span>
					</span>
				</div>
				<form class="needs-validation" novalidate="" id="saveSellOrderForm" action="{!!route('augmont.saveSellOrder')!!}" method="POST" >
					{{ csrf_field() }}
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
												<div class="dg-minibold"><span id="silverSellGrams">{{ $silverGrams }}</span> grams</div>
											</div>
											<div class="col-6 col-sm-4 col-md py-3">
												<h6 class="pb-2 ">Amount</h6>
												<div class="dg-minibold"><span class="rupees">₹</span><span id="silverSellAmount"> {{ $silverGrams * $silverPrice }}</span></div>
											</div>
										</div>
										<hr class="">
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
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<input class="form-control" id="lockPrice" name="lockPrice" type="hidden" placeholder="Lock Price" required="required" value="{{ $silverPrice }}">
							<input class="form-control" id="metalType" name="metalType" type="hidden" placeholder="Metal Type" required="required" value="silver">
							<input class="form-control" id="quantity" name="quantity" type="hidden" placeholder="Quantity" required="required" value="{{ $silverGrams }}">
							<input class="form-control" id="blockId" name="blockId" type="hidden" placeholder="BlockId" required="required" value="{{ $silverBlockId }}">
							<input class="form-control" id="totalAmount" name="totalAmount" type="hidden" placeholder="Amount" required="required" value="{{ ($silverGrams * $silverPrice) }}">
						</div>
					</div>
					<div class="card-footer text-center">
						<button class="btn btn-primary" type="button" id="proceedToSell">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/form-wizard/form-wizard.js?v=1.0')}}"></script>
<script src="{{asset('assets/js/augmont/sellorder.js?v=1.0')}}"></script>
@endsection