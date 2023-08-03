@extends('layouts.simple.master')
@section('title', 'SMS Templates')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>SMS Templates</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">SMS Template</li>
<li class="breadcrumb-item active">SMS Templates List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="smsTemplateForm_modal" tabindex="-1" role="dialog" aria-labelledby="smsTemplateForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addsmsTemplate" id="addsmsTemplate" method="POST" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New SMS Template</h4>
										</div>
										<div class="modal-body">						 
											<div class="row mb-3">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">SMS Name</label>
														<input type="text" class="form-control" name="sms_name" id="sms_name" value="" placeholder="Enter SMS Name" title="Please enter SMS Name" alt="SMS Name">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">SMS Template Id</label>
														<input type="text" class="form-control" name="sms_template_id" id="sms_template_id" value="" placeholder="Enter SMS Template Id" title="Please enter SMS Template Id which was approved" alt="SMS Template Id">
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">SMS Type</label>
														<input list="sms_typeList" type="text" class="form-control" name="sms_type" value="" id="sms_type"  placeholder="Please enter SMS Type">
														<datalist id="sms_typeList"></datalist>
														<input type="hidden" class="form-control" name="sms_id" id="sms_id" value="">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">SMS Status</label>
														<select  class="form-control" name="sms_status" id="sms_status">
															<option value="pending">Pending</option>
															<option value="approved">Approved</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory mandatory_label">SMS Content</label>
														<textarea class="form-control" name="sms_content" id="sms_content" value="" placeholder="Enter SMS Content" title="Please enter SMS content" alt="SMS Content"></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="savesmsTemplate" id="savesmsTemplate">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="smsTemplateView_modal" tabindex="-1" role="dialog" aria-labelledby="smsTemplateView_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="smsTemplateViewTitle"></h5>
										<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body" id="smsTemplateViewContent">
										
									</div>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-smsTemplate">
							<thead>
								<tr>
									<th>Name</th>
									<th>Content</th>
									<th>Created Date</th>
									<th style="width: 10%">Created By</th>
									<th style="width: 10%">Status</th>
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
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/cms/smstemplate.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection