@extends('layouts.simple.master')
@section('title', 'Employee Cards')

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('breadcrumb-title')
<h3>Employee Cards</h3>
<button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">New</button>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">HR</li>
<li class="breadcrumb-item active">Employee Cards</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<!-- Vertically centered modal-->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form class="needs-validation" novalidate="" id="newEmployeeForm" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-header">						
						<h4 class="modal-title">New Employee/Partner</h4>
						<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">						 
						<div class="mb-3">
							<label class="form-label">Employee/Partner Name</label>
							<input class="form-control" placeholder="xxxxxxxx" name="full_name" value="" id="full_name">
						</div>
						<div class="mb-3">
							<label class="form-label">Employee/Partner Id</label>
							<input class="form-control" placeholder="xxxxxxxx" name="emp_no" value="" id="emp_no">
						</div>
						<div class="mb-3">
							<label class="form-label">Official Email-Address</label>
							<input class="form-control" placeholder="your-email@domain.com" name="official_email" value="" id="official_email">
						</div>
						<div class="mb-3">
							<label class="form-label">Official Mobile</label>
							<input class="form-control" placeholder="9876543210" name="official_mobile" value="" id="official_mobile">
						</div>
						<div class="mb-3">
							<label class="form-label">Password</label>
							<input class="form-control" type="password" name="password" value="" id="password">
						</div>
					</div>
					<div class="modal-footer">						
						<div class="row mt-4">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-primary btn_upload" style="display: " id="btn_upload" name="btn_upload" value="upload">Submit</button>
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<table class="display" id="export-button">
							<thead>
								<tr>
									<th>Employee Id</th>
									<th>Employee Name</th>
									<th>Designation</th>
									<th>Department</th>
									<th>Date of Joining</th>
									<th>DOB</th>
									<th>Contact No.</th>
									<th class="hidden-480" style="width: 100px;">
										<div class="btn-group">
											<button type="button" class="btn btn-default">Global&nbsp;Actions</button>
											<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<div class="dropdown-menu" role="menu">
												<a class="dropdown-item" id="send_email">Send Email</a>
												<a class="dropdown-item" id="send_sms">Send SMS</a>
											</div>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($articles as $article)
								<tr>
									<td>{{ $article['emp_no'] }}</td>
									<td>{{ ucfirst($article['full_name']) }}</td>
									<td>{{ ucfirst($article['designation']) }}</td>
									<td>{{ ucfirst($article['department']) }}</td>
									<td>{{ ucfirst($article['doj']) }}</td>
									<td>{{ ucfirst($article['dob']) }}</td>
									<td>{{ $article['personal_mobile'] }}</td>
									<td>
										<div class="m-b-30">
											<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
												<div class="btn-group" role="group">
													<button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
													<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<a href="{{ url('hr/empCard/' . $article['pk_emp_id'] . '/view') }}" class="dropdown-item">View</a>
														<a href="{{ url('hr/empCard/' . $article['pk_emp_id'] . '/edit') }}" class="dropdown-item">Edit</a>
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
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/hr/hr.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection