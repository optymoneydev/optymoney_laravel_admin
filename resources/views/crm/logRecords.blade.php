@extends('layouts.simple.master')
@section('title', 'Log Records')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/daterange-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Log Records</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">CRM</li>
<li class="breadcrumb-item active">Log Records</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-xl-3">
							<div class="form-check form-switch">
								<input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox" role="switch">
								<label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
                          	</div>
						</div>
						<div class="col-xl-3">
							<div class="theme-form">
								<div class="mb-3">
									<input class="form-control digits" type="text" name="daterange" id="daterange" value="">
								</div>
							</div>
						</div>
						<!-- <div class="col-xl-3">
							<div class="theme-form">
								<div class="mb-3">
									<select class="form-control col-sm-12" name="filter_cust_id" id="filter_cust_id" data-select2-id="filter_cust_id" tabindex="-1" aria-hidden="true">
										<option value="" data-select2-id="2">Select</option>
									</select>
								</div>
							</div>
						</div> -->
						<div class="col-xl-2">
							<div class="theme-form">
								<div class="mb-3">
									<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="searchFilter" id="searchFilter">Search</button>
								</div>
							</div>
						</div>
					</div>
					<div class="dt-ext table-responsive">
						<div id="logDetailedTable">
							<table class="display" id="logdetailed">
								<thead>
									<tr>
										<th>Time</th>
										<th>Name</th>
										<th>IP</th>
										<th>Device</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div id="logSummaryTable">
							<table class="display" id="logSummary">
								<thead>
									<tr>
										<th>Name</th>
										<th>Level</th>
										<th>Count</th>
										<th>Last Login</th>
									</tr>
								</thead>
								<tbody></tbody>
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
<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!};
</script>
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
<script src="{{asset('assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
<script src="{{asset('assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/daterange-picker/daterange-picker.custom.js')}}"></script>

<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/crm/logrecords.js')}}"></script>
@endsection