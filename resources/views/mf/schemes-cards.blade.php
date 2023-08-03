@extends('layouts.simple.master')
@section('title', 'Schemes')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Schemes</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Schemes</li>
<li class="breadcrumb-item active">Scheme List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
<div class="select2-drpdwn">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="schemesView_modal" tabindex="-1" role="dialog" aria-labelledby="schemesView_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">						
										<h4 class="modal-title" id="schemesViewTitle"></h4>
									</div>
									<div class="modal-body">
										<div class="row mb-3">
											<div class="col-md-6">
												<p>RTA Agent: <b class="green">CAMS</b></p>
												<p>Scheme Category: <b class="">EQUITY</b></p>
												<p>Minimum Purchase Amount:<b class="green">5000</b></p>
												<p>Maximum Purchase Amount: <b class="green">199999</b></p>
												<hr>
												<table id="schemeOfferList" class="display table table-border table-responsive" style="width:100%">
													<thead >
														<tr>
															<th style="width: 100px;">Offer Name</th>                                                               
															<th style="width: 200px;">Priority</th>
															<th style="width: 400px;">Actions</th>
														</tr>
													</thead>
													<tbody>
														
													</tbody>
												</table>
												<hr>
												<form method="POST" id="schOfferInfo">
													<h4 class="modal-title">Offer Information</h4>
													<div class="row mb-3">
														<div class='col-md-12'>
															<div class="form-group">
																<label class="form-label">Offer Name<span class="required">*</span></label>
																<select class="wide required form-control" name="offer_id" id="offer_id">
																	<option value="">Select Offer</option>
																</select>
																<input type="hidden" class="form-control" value="" name="pk_sch_offer_id" id="pk_sch_offer_id">
																<input type="hidden" class="form-control" value="" name="sch_id" id="sch_id">
															</div>
														</div>
													</div>
													<div class="row mb-3">
														<div class='col-md-12'>
															<div class="form-group">
																<label class="form-label">Priority<span class="required">*</span></label>
																<input type="text" class="form-control" name="priority" id="priority" value="">
															</div>
														</div>
													</div>
													<hr>
													<div class="row mt-4">
														<div class="col-md-12 text-center">
															<button type="submit" name="update_info" class="btn btn-primary" id="updateSchOffer">Update</button>
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-6">
												<form id="form_5">
													<div class="row mb-3">
														<div class="form-group">
															<label class="mandatory mandatory_label">Options</label>
															<input list="sch_options_List" type="text" class="form-control" name="sch_options" value="" id="sch_options"  placeholder="Please enter or select option">
															<datalist id="sch_options_List">
																<option value="Risk"></option>
																<option value="Sub Category"></option>
																<option value="Data Analytics"></option>
																<option value="Popularity"></option>
																<option value="Fund Size"></option>
																<option value="Recommended"></option>
															</datalist>
														</div>
													</div>
													<div class="row mb-3">
														<div class="form-group">
															<label class="mandatory mandatory_label">Value</label>
															<input list="sch_val_List" type="text" class="form-control" name="sch_val" value="" id="sch_val"  placeholder="Please enter or select value">
															<datalist id="sch_val_List">
																
															</datalist>
														</div>
													</div>
													<div class="row mb-3">
														<div class="form-group">
															<label class="mandatory mandatory_label">Popularity</label>
															<select class="form-control" name="sch_popularity">
																<option selected="selected">Select Popularity</option>
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
													<div class="row mb-3">
														<div class="form-group">
															<label class="mandatory mandatory_label">Fund Size</label>
															<input class="form-control" type="test" name="sch_fund_size" value="">
															<div class="mb-2">
																<div class="col-form-label">Select2 multi select</div>
																<select class="js-programmatic-enable col-sm-12" multiple="multiple" id="abc">
																	<option value="AL">Alabama</option>
																	<option value="WY">Wyoming</option>
																	<option value="WY">Coming</option>
																	<option value="WY">Hanry Die</option>
																	<option value="WY">John Doe</option>
																</select>
															</div>
														</div>
													</div>
													<div class="row mb-3">
														<div class="form-group">
															<label class="mandatory mandatory_label">Recommended</label>
															<select class="form-control" name="recommand">
																<option value="No">No</option>
																<option value="Yes" selected="">Yes</option>
															</select>
														</div>
													</div>
													<hr>
													<div class="row mb-3">
														<div class="col-md-6 offset-md-3">
															<a class="btn btn-danger btn-sm update_master" data-id="5" name="update_rec">Update</a>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
									<div class="modal-footer">						
											
									</div>
								</div>
							</div>
						</div>
						<table class="display"  id="export-button-schemes">
							<thead>
								<tr>
									<th>Unique No.</th>
									<th>Scheme Code</th>
									<th>Scheme Name</th>
									<th>Scheme Type</th>
									<!-- <th>Asset Value</th>
									<th>Repurchase Price</th> 
									<th>Sale Price</th>
									<th>ISIN</th>
									<th>Scheme Plan</th>
									<th>Puchase Status</th>
									<th>Name</th>
									<th>Content</th>
									<th>Created Date</th> -->
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
<script src="{{asset('assets/js/mf/schemes.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
<script type="text/javascript">
  $(function () {
    
    var table = $('#export-button-schemes').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getSchemes') }}",
		dom: 'Bfrtip',
		buttons: [
			// {
			// 	text: 'New SMS Template',
			// 	action: function ( e, dt, node, config ) {
			// 		$('#smsTemplateForm_modal').modal("show");
			// 		$('#addsmsTemplate')[0].reset();
			// 	}
			// },
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdfHtml5'
		],
        columns: [
            {data: 'pk_nav_id', name: 'pk_nav_id'},
            {data: 'scheme_code', name: 'scheme_code'},
            {data: 'scheme_name', name: 'scheme_name'},
			{data: 'scheme_type', name: 'scheme_type'},
            {data: 'action', name: 'action'},
        ],
		"fnInitComplete": function() { $("#export-button-schemes").css("width","100%"); }
    }).buttons().container().appendTo('#export-button-schemes_wrapper .col-md-6:eq(0)');
    
  });
</script>
@endsection