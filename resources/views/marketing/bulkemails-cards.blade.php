@extends('layouts.simple.master')
@section('title', 'Bulk Email')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Bulk Email</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Bulk Email</li>
<li class="breadcrumb-item active">Bulk Email List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-8">
			<form class="needs-validation" novalidate="" data-formType="1" name="sendBulkEmail" id="sendBulkEmail" action="#" method="post" enctype="multipart/form-data" class="frmCurrent has-validation-callback">
				<div class="card">
					<div class="card-body">
						{{ csrf_field() }}
						<div class="mb-3">
							<label class="mandatory mandatory_label">CSV File </label>
							<input type="file" name="bm_csvfile" class="form-control" id="bm_csvfile" />
						</div>
						<div class="mb-3">
							<label class="mandatory mandatory_label">Receiver Email Ids(',' seperated) </label>
							<textarea class="form-control" id="bm_emails" name="bm_emails" rows="4" cols="100%"></textarea>
						</div>
						<div class="mb-3">
							<label class="mandatory mandatory_label">Subject </label>
							<input type="text" class="form-control" name="bm_subject" value="" id="bm_subject">
						</div>
						<div class="mb-3">
							<label class="mandatory mandatory_label">Sender Name </label>
							<input type="text" class="form-control" name="bm_sendername" value="" id="bm_sendername">
						</div>
						<div class="mb-3">
							<label class="mandatory mandatory_label">Send Emails from </label>
							<select class="wide required form-control" name="bm_senderemail" id="bm_senderemail">
								<option value="hello@optymoney.com">hello@optymoney.com</option>
								<option value="support@optymoney.com">support@optymoney.com</option>
								<option value="noreply@optymoney.com">noreply@optymoney.com</option>
								<option value="vtatia@optymoney.com">vtatia@optymoney.com</option>
								<option value="invest@optymoney.com">invest@optymoney.com</option>
								<option value="surendra@optymoney.com">surendra@optymoney.com</option>
								<option value="rajeshkumar@optymoney.com">rajeshkumar@optymoney.com</option>
								<option value="tax@optymoney.com">tax@optymoney.com</option>
								<option value="taxsave@optymoney.com">taxsave@optymoney.com</option>
								<option value="careers@optymoney.com">careers@optymoney.com</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="mandatory mandatory_label">Send to Existing Users </label>
							<select class="wide required form-control" name="bm_existingUsers" id="bm_existingUsers">
								<option value="No">No</option>
								<option value="Yes">Yes</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="mandatory mandatory_label">Email Format </label>
							<select class="wide required form-control" name="bm_emailformat" id="bm_emailformat">
								
							</select>
						</div>
						<div id="emailContentData"> </div>
					</div>
					<div class="card-footer">
						<button class="btn btn-danger" style="color:white" type="submit" name="bm_email_send" id="bm_email_send" >Send</button>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-4">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<table class="display" id="export-button-bulkemails">
							<thead>
								<tr>
									<th>Email Address</th>
                                    <th>Status</th>
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
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/styles.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/marketing/bulkemail.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection