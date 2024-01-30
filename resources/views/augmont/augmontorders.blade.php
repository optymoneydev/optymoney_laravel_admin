@extends('layouts.simple.master')
@section('title', 'Datatables Server Side')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/daterange-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Augmont Orders</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Augmont</li>
<li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-xl-3">
							<div class="theme-form">
								<div class="mb-3">
									<select class="form-control col-sm-12" name="resultType" id="resultType" data-select2-id="resultType" tabindex="-1" aria-hidden="true">
										<option value="">Select Data Type</option>
										<option value="1">Transactions</option>
										<option value="2">Summary</option>	
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-9" id="transactionFilter">
							<div class="row">
								<div class="col-xl-3">
									<div class="theme-form">
										<div class="mb-3">
											<input class="form-control digits" type="text" name="daterange" id="daterange" value="">
										</div>
									</div>
								</div>
								<div class="col-xl-3">
									<div class="theme-form">
										<div class="mb-3">
											<select class="form-control col-sm-12" name="filter_cust_id" id="filter_cust_id" data-select2-id="filter_cust_id" tabindex="-1" aria-hidden="true">
												<option value="" data-select2-id="2">Select</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-xl-2">
									<div class="theme-form">
										<div class="mb-3">
											<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="searchFilter" id="searchFilter">Search</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive" id="transactionsTable">
                  		<table class="display datatables" id="server-side-datatable" style="font-size: 12px">
							<thead>
								<tr>
									<th>Transaction<br>Date</th>
									<th>Customer</th>
									<th>Contact</th>
									<th>Order<br>Type</th>
									<th>Metal<br>Type</th>
                           			<th>Gold/Silver<br>Grams</th>
									<th>Purchase/Sell<br>Amount</th>
									<th>Current<br>Amount</th>
									<th>Profit/Loss</th>
									<th>Invoice</th>
									<th>Buy Info</th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="table-responsive" id="summaryTable">
                  		<table class="display datatables" id="summaryTableData" style="font-size: 12px">
							<thead>
								<tr>
									<th>Customer</th>
									<th>Contact</th>
									<th>Gold<br>Quantity</th>
									<th>Gold<br>Pre-Tax<br>Amount</th>
									<th>Gold<br>Total<br>Amount</th>
									<th>Gold<br>Current<br>Amount</th>
									<th>Gold<br>Profit/Loss</th>
									<th>Silver<br>Quantity</th>
									<th>Silver<br>Pre-Tax<br>Amount</th>
									<th>Silver<br>Total<br>Amount</th>
									<th>Silver<br>Current<br>Amount</th>
									<th>Silver<br>Profit/Loss</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="newForm" tabindex="-1" role="dialog" aria-labelledby="aoForm_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
				<div class="modal-content">
					<form class="needs-validation" novalidate="" name="addao" id="addao" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="modal-header">						
							<h4 class="modal-title">New Augmont Order</h4>
						</div>
						<div class="modal-body">						 
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="mandatory mandatory_label">Customer Name</label>
										<select class="form-control col-sm-12" name="ao_cust_id" id="ao_cust_id" data-select2-id="ao_cust_id" tabindex="-1" aria-hidden="true" required="">
											<option value="" data-select2-id="2">Select</option>
										</select>
										<div class="invalid-feedback">Please select the customer</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="mandatory mandatory_label">Orders</label>
										<select  class="form-control" name="ao_orders" id="ao_orders">
											<option value="">Select</option>
										</select>
									</div>
								</div>
							</div>
							<br><hr><br>
							<div class="row">
								<div class="col-md-6">
									<h6>Order Details</h6>
									<p>Razorpay Order Id: <span id="roi"></span></p>
									<p>Razorpay Payment Id: <span id="rpi"></span></p>
									<p>Augmont Merchant Transaction Id: <span id="amt_id"></span></p>
									<p>Metal Type: <span id="metal"></span></p>
									<p>Total Amount: <span id="totamt"></span></p>
									<p>No. of Grams: <span id="grams"></span></p>
									<p>Lock Price: <span id="lprice"></span></p>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="mandatory mandatory_label">Mode of Payment</label>
										<select  class="form-control" name="ao_mop" id="ao_mop">
											<option value="">Select</option>
											<option value="Cards">Card</option>
											<option value="UPI/OR">UPI</option>
											<option value="Netbanking">Net banking</option>
											<option value="EMI">EMI</option>
											<option value="Wallet">Wallet</option>
										</select>
									</div>
									<div class="form-group">
										<label class="mandatory mandatory_label">Razorpay Payment Id</label>
										<div class="input-group">
											<input type="text" class="form-control" name="ao_transaction_id" id="ao_transaction_id" value="" placeholder="Enter Razorpay Payment Id" title="Enter Razorpay Payment Id" alt="Razorpay Payment Id"><span class="input-group-text btn-warning"><a href="#" name="verifyRPI" id="verifyRPI">Verify</a></span>
										</div>
										<div id="payStat"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">						
							<div class="row mt-4">
								<div class="col-md-12 text-center">
									<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveao" id="saveao">Save</button>
									<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Server Side Processing end-->
	</div>
</div>
<div class="modal fade" id="invoiceView" tabindex="-1" role="dialog" aria-labelledby="invoiceView" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="invoiceViewTitle"></h5>
				<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
			</div>
			<div class="modal-body">
				<div class="invoice">
					<div class="row">
						<div class="col-sm-6">
							<div class="media">
								<div class="media-left"><img class="media-object img-60" src="{{asset('assets/images/logo/login.png')}}" alt=""></div>
								<div class="media-body m-l-20 text-right">
									<h4 class="media-heading">Optymoney</h4>
									<!-- <p>hello@Cuba.in<br><span>289-335-6503</span></p> -->
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="text-md-end text-xs-center">
								<h3>Invoice #<span class="counter" id="inv_number"></span></h3>
								<p>Issued: <span id="inv_date"></span></p>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="media">
								<div class="media-body m-l-20 text-right">
									<h4 class="media-heading">Sold by:</h4>
									<h5>Augmont Goldtech Private Limited</h5>
									<p>(Formerly known as Augmont Precious Metals Private Limited)</p>
									<p>Address:</p>
									<p>504, 5th Floor, Trade Link, E Wing, Kamala Mills Compound, Lower Parel, Mumbai, Maharashtra 400013</p>
									<h4>GSTIN:</h4>
									<h5>Augmont Goldtech Private Limited</h5>
									<p>27AATCA3030A1Z3</p>
								</div>
							</div>
						</div>
						<div class="col-sm-6"></div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="media">
								<div class="media-body m-l-20 text-right">
									<h4 class="media-heading">Customer Address:</h4>
									<h5 id="custname">Ritesh B</h5>
									<p id="address">401101 - Maharashtra, India</p>
									<p id="contact">1234567890</p>
									<p id="email">email address</p>
									<h5>Augmont Unique Id:</h5>
									<p id="augid">WF238462973648273648</p>
									<h5>Payment Mode Used: <span id="mop"></span></h5>
								</div>
							</div>
						</div>
						<div class="col-sm-6"></div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							<div class="table-responsive invoice-table" id="table">
								<table id="productList" class="table table-bordered table-striped">
									<tbody>
										
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div>
								<h5>Terms & Conditions :-</h5>
								<p class="legal">1. Once goods sold cannot be returned</p>
								<p class="legal">1. Any disputes can be subject to Mumbai jurisdiction</p>
								<p class="legal">1. Additional payment gateway surcharge might be levied by the partner</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div>
								<h5>Authorised Signatory :-</h5>
								<p class="legal">GSTIN : 27AATCA3030A1Z3</p>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div>
								<p class="text-center">This is a computer generated invoice, if you have any questionsconcerning this invoice, contact: support@optymoney.com</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn btn-primary me-2" type="button" onclick="myFunction()">Print</button>
				<button class="btn btn-secondary" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{asset('assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/daterange-picker/daterange-picker.custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>

<script src="{{asset('assets/js/augmont/orders.js?v=1.0')}}"></script>
@endsection