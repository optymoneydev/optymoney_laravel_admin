@extends('layouts.simple.master')
@section('title', 'PMS')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>PMS</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">PMS</li>
<li class="breadcrumb-item active">PMS List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="pmsForm_modal" tabindex="-1" role="dialog" aria-labelledby="pmsForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addpms" id="addpms" method="POST" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New PMS Registration</h4>
										</div>
										<div class="modal-body">						 
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Customer Name</label>
														<select class="form-control col-sm-12" name="pms_cust_id" id="pms_cust_id" data-select2-id="pms_cust_id" tabindex="-1" aria-hidden="true" required="">
															<option value="" data-select2-id="2">Select</option>
															@foreach ($clients as $client)
																<option value="{{ $client['pk_user_id'] }}">{{ $client['pan_number'] }} - {{ ucfirst($client['cust_name']) }} - {{ $client['contact_no'] }}</option>
															@endforeach
														</select>
														<div class="invalid-feedback">Please select the customer</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Product Type</label>
														<select  class="form-control" name="pms_prod_type" id="pms_prod_type">
															<option value="">Select</option>
															<option value="pms">PMS</option>
															<option value="aif">AIF</option>
														</select>
														<input type="hidden" class="form-control" name="pms_id" id="pms_id" value="">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Transaction Date</label>
														<input type="date" class="form-control" name="pms_trans_date" id="pms_trans_date" value="" placeholder="Enter Transaction date" title="Please select transact date" alt="Transaction date">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Transaction Type</label>
														<select  class="form-control" name="pms_trans_type" id="pms_trans_type">
															<option value="">Select</option>
															<option value="buy">Buy</option>
															<option value="sell">Sell</option>
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory mandatory_label">Transaction Amount</label>
														<input type="text" class="form-control" name="pms_trans_amt" id="pms_trans_amt" value="" placeholder="Enter Transaction Amount" title="Please enter transaction amount" alt="Transaction Amount">
													</div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Upload Documents</label>
														<input type="file" name="pms_document[]" multiple="multiple" class="form-control" required>
													</div>
												</div>
												<div class="col-md-6" id="pms_document">

												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="savePMS" id="savePMS">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-pms">
							<thead>
								<tr>
									<th>Customer Name</th>
									<th>Product Type</th>
									<th>Transaction Type</th>
									<th>Transaction Date</th>
									<th>Transaction Amount</th>
									<th>Documents</th>
									<th style="width: 10%">Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($pmss as $pms)
								<tr>
									<td>{{ ucfirst($pms['cust_name']) }}</td>
									<td>{{ ucfirst($pms['pms_prod_type']) }}</td>
									<td>{{ ucfirst($pms['pms_trans_type']) }}</td>
									<td>{{ ucfirst($pms['pms_trans_date']) }}</td>
									<td>{{ ucfirst($pms['pms_trans_amt']) }}</td>
									@if($pms['pms_document'] =='' || $pms['pms_document'] ==null)
										<td></td>
									@else
										<td>
											@if (strpos($pms['pms_document'], '|'))
												@foreach (explode('|',$pms['pms_document']) as $file) 
													@if ($file!="")
													<a target="_blank" href="{{ url('itr/getfile/'.$pms['pms_cust_id'].'/'.$file ) }}">{{ $file }}</a>
													@endif
												@endforeach
											@else
												<a target="_blank" href="{{ url('itr/getfile/'.$pms['pms_cust_id'].'/'.$pms['pms_document'] ) }}">{{$pms['pms_document'] }}</a>
											@endif
										</td>        
									@endif
									<td>
										<div class="m-b-30">
											<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
												<div class="btn-group" role="group">
													<button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
													<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<a href="#" class="dropdown-item viewpms" data-id="{{ $pms['pms_id'] }}">View</a>
														<a href="#" class="dropdown-item editpms" data-id="{{ $pms['pms_id'] }}">Edit</a>
														<a href="#" class="dropdown-item deletepms" data-id="{{ $pms['pms_id'] }}">Delete</a>
													</div>
												</div>
											</div>
										</div>
									</td>
								</tr>
								@endforeach
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
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/pms/pms.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection