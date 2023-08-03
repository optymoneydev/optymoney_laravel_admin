@extends('layouts.simple.master')
@section('title', 'Coupons')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Coupons</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Coupons</li>
<li class="breadcrumb-item active">Coupon List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="couponForm_modal" tabindex="-1" role="dialog" aria-labelledby="couponForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addCoupon" id="addCoupon" method="POST">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New Coupon</h4>
										</div>
										<div class="modal-body">
											<div class="row mb-3">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Coupon Name</label>
														<input type="text" class="form-control" name="cou_name" id="cou_name" placeholder="Enter Coupon Name" value="" required="">
														<input type="hidden" name="cou_id" id="cou_id" placeholder="Enter Coupon Id" value="" required="">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Discount percent</label>
														<input type="text" class="form-control" name="cou_per" id="cou_per" placeholder="Enter Coupon Code" value="" required="">
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Quantity</label>
														<input type="text" class="form-control" name="cou_quantity" id="cou_quantity" placeholder="Enter Coupon Quantity" value="" required="">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Partner Company</label>
														<input type="text" class="form-control" name="cou_partner_cmpny" id="cou_partner_cmpny" placeholder="Enter Partner Company" value="" required="">
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Validity</label>
														<input type="text" class="form-control" name="cou_validity" id="cou_validity" placeholder="Enter Coupon Validity" value="" required="">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Coupon Code</label>
														<input type="text" class="form-control" name="cou_code" id="cou_code" placeholder="Enter Coupon Code" value="" required="">
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveCoupon" id="saveCoupon">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="couponView_modal" tabindex="-1" role="dialog" aria-labelledby="couponView_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="couponViewTitle"></h5>
										<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body" id="couponViewContent">
										
									</div>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-coupon">
							<thead>
								<tr>
									<th>Name</th>
									<th>Quantity</th>
									<th>Company Partner</th>
									<th>Validity</th>
									<th>Coupon Code</th>
									<th>Create date</th>
									<th style="width: 10%">Created By</th>
									<th style="width: 10%">Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
							
						</table>
					</div>
				</div>
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
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/marketing/coupon.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection