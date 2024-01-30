@extends('layouts.simple.master')
@section('title', 'Client List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Client List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">CRM</li>
<li class="breadcrumb-item active">Client List</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="newForm" tabindex="-1" role="dialog" aria-labelledby="newForm" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="userForm_modal" id="userForm_modal" method="POST">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New User</h4>
										</div>
										<div class="modal-body">						 
											<div id="wizard2">
												<h3>Contact Information</h3>
												<section>
													<div class="row row-sm mb-5">
														<div class="col-md-6 col-lg-6">
															<label class="form-control-label">Contact No.(Linked with PAN) <span class="tx-danger">*</span></label> <input class="form-control" id="contact" name="contact" placeholder="Enter contact" required="" type="text">
														</div>
														<div class="col-md-6 col-lg-6 mg-t-20 mg-md-t-0">
															<label class="form-control-label">Email Address: <span class="tx-danger">*</span></label> <input class="form-control" id="emailAddress" name="emailAddress" placeholder="Enter email address" required="" type="text">
														</div>
													</div>
												</section>
												<h3>Personal Information</h3>
												<section>
													<div class="row row-sm mb-5" id="createAccountForm">
														<div class="col-md-4 col-lg-4">
															<label class="form-label" >First Name</label>
															<input type="text" class="form-control" id="fname" name="fname" placeholder="First Name">
														</div>
														<div class="col-md-4 col-lg-4">
															<label class="form-label" >Last Name</label>
															<input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name">
														</div>
														<div class="col-md-4 col-lg-4">
															<label class="form-label">Password</label>
															<div class="input-group">
																<input class="form-control" id="password" name="password" type="password" required="required" placeholder="Password">
																<span class="input-group-append">
																	<button class="btn btn-secondary" type="button"><i class="fas fa-eye"></i></button>
																</span>
															</div>
														</div>
													</div>
												</section>
												<h3>PAN & Aadhaar Verification</h3>
												<section>
													<div class="row row-sm mb-5" id="validatePanAadhaar">
														<div class="col-md-4 col-lg-4">
															<label class="form-control-label">Date of Birth&nbsp;<small>(As on PAN Card)</small> <span class="tx-danger">*</span></label> <input class="form-control digits" id="dob" name="dob" type="date" >
														</div>
														<div class="col-md-4 col-lg-4">
															<label class="form-control-label">PAN <span class="tx-danger">*</span></label> <input class="form-control" id="pan" name="pan" placeholder="PAN Number" required="" type="text">
														</div>
														<div class="col-md-4 col-lg-4">
															<label class="form-control-label">Aadhaar Number <span class="tx-danger">*</span></label> <input class="form-control" id="aadhaar" name="aadhaar" placeholder="Aadhaar Number" required="" type="text">
														</div>
													</div>
												</section>
												<h3>Address Information</h3>
												<section>
													<div class="row row-sm" id="finishSteps">
														<div class="col-md-6 col-lg-6 mb-1">
															<label class="form-control-label">Address Line 1 <span class="tx-danger">*</span></label> <input class="form-control" id="address1" name="address1" type="text" placeholder="Address Line 1" required="">
														</div>
														<div class="col-md-6 col-lg-6 mb-1">
															<label class="form-control-label">Address Line 2 <span class="tx-danger">*</span></label> <input class="form-control" id="address2" name="address2" placeholder="Address Line 2" required="" type="text">
														</div>
														<div class="col-md-6 col-lg-6 mb-1">
															<label class="form-control-label">State <span class="tx-danger">*</span></label> 
															<select class="form-control mt-1" id="state" name="state" required="required"><option value="">Select State</option></select>
														</div>
														<div class="col-md-6 col-lg-6 mb-1">
															<label class="form-control-label">City <span class="tx-danger">*</span></label> 
															<select class="form-control mt-1" id="city" name="city" required="required"></select>
														</div>
													</div>
													<div class="row row-sm mb-5" id="finishSteps">
														<div class="col-md-4 col-lg-4">
															<label class="form-control-label">Nominee Name <span class="tx-danger">*</span></label> <input class="form-control" id="nominee_name" name="nominee_name" placeholder="Nominee Name" required="" type="text">
														</div>
														<div class="col-md-4 col-lg-4">
															<label class="form-control-label">Nominee Relation <span class="tx-danger">*</span></label> <input class="form-control" id="nominee_relation" name="nominee_relation" placeholder="Nominee Relation" required="" type="text">
														</div>
														<div class="col-md-4 col-lg-4">
															<label class="form-control-label">Nominee Date of Birth <span class="tx-danger">*</span></label> <input class="form-control digits" id="nominee_dob" name="nominee_dob" type="date" required>
														</div>
													</div>
												</section>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveUser" id="saveUser">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<table class="display" id="export-button">
							<thead>
								<tr>
									<th>Name</th>
                                    <th>Login Id</th>
                                    <th>PAN</th>
                                    <th>Phone No</th>
                                    <th>User Status</th>
									<th>Created From</th>
									<th>Created Date</th>
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
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/crm/client_data.js')}}"></script>
@endsection