@extends('layouts.simple.master')
@section('title', 'HR')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>HR</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">HR</li>
<li class="breadcrumb-item active">Employee Assign</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<form class="needs-validation" novalidate="" name="addEmpCustMap" id="addEmpCustMap" method="POST">
						{{ csrf_field() }}
						<div class="modal-header">						
							<h4 class="modal-title">New Employee Client Assignment</h4>
						</div>
						<div class="modal-body">						 
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="mandatory mandatory_label">Employee Name</label>
										<select class="form-control col-sm-12" name="emp_id" id="emp_id" data-select2-id="emp_id" tabindex="-1" aria-hidden="true" required="">
											<option value="" data-select2-id="2">Select</option>
											@foreach ($articles as $client)
												<option value="{{ $client['pk_emp_id'] }}">{{ $client['full_name'] }}</option>
											@endforeach
										</select>
										<div class="invalid-feedback">Please select the Employee</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="mandatory mandatory_label">Customer Name</label>
										<select class="js-example-basic-multiple col-sm-12" multiple="multiple" name="cust_id[]" id="cust_id" data-select2-id="cust_id" required>
											@foreach ($clients as $client)
												<option value="{{ $client['pk_user_id'] }}">{{ $client['pan_number'] }} - {{ ucfirst($client['cust_name']) }} - {{ $client['contact_no'] }}</option>
											@endforeach
										</select>
										<div class="invalid-feedback">Please select the customer</div>
									</div>
								</div>
								
							</div>
							
						</div>
						<div class="modal-footer">						
							<div class="row mt-4">
								<div class="col-md-12 text-center">
									<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveEmpCust" id="saveEmpCust">Save</button>
									<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<table class="display" id="empCustTable">
							<thead>
								<tr>
									<th>Employee Name</th>
									<th>Customer Name</th>
									<th style="width: 10%">Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($empCustData as $emp)
								<tr>
									<td>{{ ucfirst($emp['full_name']) }}</td>
									<td>{{ ucfirst($emp['cust_name']) }}</td>
									<td>
										<div class="m-b-30">
											<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
												<div class="btn-group" role="group">
													<button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
													<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<a href="#" class="dropdown-item deleteEMPClient" data-id="{{ $emp['id'] }}">Delete</a>
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
<script src="{{asset('assets/js/hr/hr.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>

<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection