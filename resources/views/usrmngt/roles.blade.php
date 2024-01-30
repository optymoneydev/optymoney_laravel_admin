@extends('layouts.simple.master')
@section('title', 'Employee Roles List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Employee Roles List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Employee</li>
<li class="breadcrumb-item active">Roles List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="newForm" tabindex="-1" role="dialog" aria-labelledby="newForm" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="userRolesForm_modal" id="userRolesForm_modal" method="POST">
										{{ csrf_field() }}
										<div class="modal-header"><h4 class="modal-title" id="roleTitle">New Role</h4></div>
										<div class="modal-body">						 
											<div class="" id="kt_modal_add_role_scroll">
												<div class="mb-3">
													<label class="form-label"><span class="required">Role name</span></label>
													<input class="form-control form-control-solid" placeholder="Enter a role name" name="role_name" id="role_name" required>
													<input class="form-control form-control-solid" name="id" id="id" type="hidden">
												</div>
												<div class="mb-3">
													<label class="form-label">Role Permissions</label>
													<div class="table-responsive">
														<table class="table align-middle table-row-dashed fs-6 gy-5">
															<tbody class="text-gray-600 fw-semibold" id="menuPermissionReportUI">
																<tr>
																	<td class="text-gray-800">
																		Administrator Access
																		<span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Allows a full access to the system" data-kt-initialized="1">
																			<i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>                                                </span>
																	</td>
																	<td>
																		<label class="form-check form-check-custom form-check-solid me-9">
																			<input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all">
																			<span class="form-check-label" for="kt_roles_select_all">
																				Select all
																			</span>
																		</label>
																	</td>
																</tr>
															</tbody>
                                						</table>
                            						</div>
                        						</div>
                    						</div>
                    					</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<div class="text-center pt-15">
														<button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">Discard</button>
														<button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
															<span class="indicator-label">Submit</span>
															<!-- <span class="indicator-progress">
																Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
															</span> -->
														</button>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<table class="display" id="usrmngtRolesTable">
							<thead>
								<tr>
									<th>Role Name</th>
                                    <th>Total Users with this role</th>
                                    <th>Created By</th>
									<th>Created Date</th>
                                    <th class="hidden-480" style="width: 100px;">Actions</th>
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
<script src="{{asset('assets/js/users/usrmngt.js')}}"></script>
@endsection