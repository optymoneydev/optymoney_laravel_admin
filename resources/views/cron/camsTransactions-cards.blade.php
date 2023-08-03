@extends('layouts.simple.master')
@section('title', 'NAV')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>NAV</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">NAV</li>
<li class="breadcrumb-item active">NAV List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="helpForm_modal" tabindex="-1" role="dialog" aria-labelledby="helpForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addhelp" id="addhelp" method="POST" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="modal-header">
											<h4 class="modal-title">New Help</h4>
										</div>
										<div class="modal-body">						 
											<div class="row mb-5">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Category</label>
														<input list="help_categoryList" type="text" class="form-control" name="help_category" id="help_category" value="" placeholder="Enter Category" title="Please enter category" alt="Category">
														<datalist id="help_categoryList"></datalist>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Sub Category</label>
														<input list="help_sub_categoryList" type="text" class="form-control" name="help_sub_category" id="help_sub_category" value="" placeholder="Enter Sub Category" title="Please enter Sub category" alt="SubCategory">
														<datalist id="help_sub_categoryList"></datalist>
													</div>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory mandatory_label">Question</label>
														<input type="text" class="form-control" name="help_question" id="help_question" value="" placeholder="Enter Question" title="Please enter Question" alt="Question">
													</div>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory mandatory_label">Answer</label>
														<div id="help_answer" contenteditable="true">
															<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at vulputate urna, sed dignissim arcu. Aliquam at ligula imperdiet, faucibus ante a, interdum enim. Sed in mauris a lectus lobortis condimentum. Sed in nunc magna. Quisque massa urna, cursus vitae commodo eget, rhoncus nec erat. Sed sapien turpis, elementum sit amet elit vitae, elementum gravida eros. In ornare tempus nibh ut lobortis. Nam venenatis justo ex, vitae vulputate neque laoreet a.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory mandatory_label">Keywords</label>
														<div id="help_keywords" contenteditable="true">
															<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at vulputate urna, sed dignissim arcu. Aliquam at ligula imperdiet, faucibus ante a, interdum enim. Sed in mauris a lectus lobortis condimentum. Sed in nunc magna. Quisque massa urna, cursus vitae commodo eget, rhoncus nec erat. Sed sapien turpis, elementum sit amet elit vitae, elementum gravida eros. In ornare tempus nibh ut lobortis. Nam venenatis justo ex, vitae vulputate neque laoreet a.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-4 mb-4">
													<div class="form-group" id="ins_prod_type_group">
														<label class="mandatory mandatory_label">Status</label>
														<select  class="form-control" name="help_status" id="help_status">
															<option value="">Select</option>
															<option value="draft">Draft</option>
															<option value="publish">Publish</option>
														</select>
														<input type="hidden" class="form-control" name="help_id" id="help_id" value="">
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveHelp" id="saveHelp">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-camsTransactions">
							<thead>
								<tr>
									<th>Folio No.</th>
									<th>Scheme Name</th>
									<th>Investor Name</th>
									<th>PAN</th>
									<th>Amount</th>
									<th>Transaction Date</th>
									<th>Transaction Nature</th>
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
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('assets/js/cron/cron.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
<script type="text/javascript">
  	$(function () {
        var table = $('#export-button-camsTransactions').DataTable({
			processing: true,
			serverSide: true,
			ajax: "{{ route('getCamsTransactions') }}",
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			],
			columns: [
				{data: 'folio_no', name: 'folio_no'},
				{data: 'scheme', name: 'scheme'},
				{data: 'inv_name', name: 'inv_name'},
				{data: 'pan', name: 'pan'},
				{data: 'amount', name: 'amount'},
				{data: 'traddate', name: 'traddate'},
				{data: 'trxn_nature', name: 'trxn_nature'},
			],
			"fnInitComplete": function() { $("#export-button-camsTransactions").css("width","100%"); }
		}).buttons().container().appendTo('#export-button-camsTransactions_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection