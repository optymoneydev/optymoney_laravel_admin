@extends('layouts.simple.master')
@section('title', 'Edit Profile')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Edit Profile</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Users</li>
<li class="breadcrumb-item active">Edit Profile</li>
@endsection

@section('content')
<div class="container-fluid">
	<div id="noty-holder"></div>
	<div class="edit-profile">
		<div class="row">
			
			<div class="col-xl-4">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">My Profile</h4>
						<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
					</div>
					<div class="card-body">
						<form class="needs-validation" novalidate="" id="updateEmployeeForm" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
							<div class="row mb-2">
								<div class="profile-title">
									<div class="media">
										<img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/user/7.jpg')}}">
										<div class="media-body">
											<h3 class="mb-1">{{ ucfirst($employee->full_name) }}</h3>
											<p>{{ ucfirst($employee->designation) }}</p>
											<input class="form-control" name="emp_id" value="{{ $employee->pk_emp_id }}" id="emp_id">
										</div>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Employee Id</label>
								<input class="form-control" placeholder="xxxxxxxx" name="emp_no" value="{{ $employee->emp_no }}" id="emp_no">
							</div>
							<div class="mb-3">
								<label class="form-label">Official Email-Address</label>
								<input class="form-control" placeholder="your-email@domain.com" name="official_email" value="{{ $employee->official_email }}" id="official_email">
							</div>
							<div class="mb-3">
								<label class="form-label">Official Mobile</label>
								<input class="form-control" placeholder="9876543210" name="official_mobile" value="{{ $employee->official_mobile }}" id="official_mobile">
							</div>
							<!-- <div class="mb-3">
								<label class="form-label">Password</label>
								<input class="form-control" type="password" name="password" value="{{ $employee->password }}" id="password">
							</div> -->
							<div class="form-footer">
								<button class="btn btn-primary btn-block">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-xl-8">
				<form class="card needs-validation" novalidate="" id="updatePersonal" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="card-header">
						<h4 class="card-title mb-0">Personal Details</h4>
						<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">UAN</label>
									<input class="form-control" type="text" placeholder="UAN" name="uan_no" value="{{ $employee->uan_no }}" id="uan_no">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Provident Fund</label>
									<input class="form-control" type="text" placeholder="Provident Fund" name="pf_no" value="{{ $employee->pf_no }}" id="pf_no">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">ESI</label>
									<input class="form-control" type="text" placeholder="ESI" name="esi_no" value="{{ $employee->esi_no }}" id="esi_no">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Date of Birth</label>
									<input class="form-control" type="date" name="dob" value="{{ $employee->dob }}" id="dob">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Gender</label>
									<select class="form-control" name="gender" id="gender">
										<option value="">Select</option>
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Blood Group</label>
									<select class="form-control" name="blood_group" id="blood_group">
										<option value="">Select</option>
										<option value="A+ve">A +ve</option>
										<option value="A-ve">A -ve</option>
										<option value="B+ve">B +ve</option>
										<option value="B-ve">B -ve</option>
										<option value="O+ve">O +ve</option>
										<option value="O-ve">O -ve</option>
										<option value="AB+ve">AB +ve</option>
										<option value="AB-ve">AB -ve</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Marital Status</label>
									<select class="form-control" name="marital_status" id="marital_status">
										<option value="">Select</option>
										<option value="Single">Single</option>
										<option value="married">Married</option>
										<option value="Divorce">Divorce</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Father Name</label>
									<input type="text" class="form-control" name="father_name" id="father_name" value="{{ $employee->father_name }}">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Spouse Name</label>
									<input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{ $employee->spouse_name }}">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Personal Mobile</label>
									<input class="form-control" type="text" placeholder="Personal Mobile" name="personal_mobile" value="{{ $employee->personal_mobile }}" id="personal_mobile">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Personal Email</label>
									<input class="form-control" type="text" placeholder="Personal Email" name="personal_email" value="{{ $employee->personal_email }}" id="personal_email">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Alternate Contact Person</label>
									<input class="form-control" type="text" placeholder="Alternate Contact Person" name="alternate_contact_person" value="{{ $employee->alternate_contact_person }}" id="alternate_contact_person">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Alternate Contact Number</label>
									<input type="text" class="form-control" placeholder="Alternate Contact Number" name="alternate_contact_mobile" id="alternate_contact_mobile" value="{{ $employee->alternate_contact_mobile }}">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="mb-3">
									<label class="form-label">Employee Status</label>
									<select class="form-control" name="employee_status" id="employee_status">
										<option value="">Select</option>
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-end">
						<button class="btn btn-primary" type="submit">Update Profile</button>
					</div>
				</form>
			</div>
			<div class="col-xl-4">
				<form class="card needs-validation" novalidate="" id="updateDocuments" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="card-header">
						<h4 class="card-title mb-0">Document Uploads</h4>
						<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="mb-3">
								<label class="form-label">Document Type</label>
								<select class="form-control" name="employee_status" id="employee_status">
									<option value="">Select</option>
									<option value="pan">PAN</option>
									<option value="aadhaar">Aadhaar</option>
									<option value="passport">Passport</option>
									<option value="other">Other</option>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">PAN Card</label>
								<input type="text" class="form-control" placeholder="PAN" name="pan" id="pan" value="{{ $employee->pan }}">
							</div>
							<div class="mb-3">
								<label class="form-label">PAN Card Upload</label>
								<input type="file" class="form-control" name="pan_upload" id="pan_upload" value="">
								<a href="" id="pan_upload_link"></a>
							</div>
							<div class="mb-3">
								<label class="form-label">Aadhaar Card</label>
								<input type="text" class="form-control" placeholder="Aadhaar" name="aadhar" id="aadhar" value="{{ $employee->aadhar }}">
							</div>
							<div class="mb-3">
								<label class="form-label">Aadhaar Card Upload</label>
								<input type="file" class="form-control" name="aadhar_upload" id="aadhar_upload" value="">
								<a href="" id="aadhar_upload_link"></a>
							</div>
							<div class="mb-3">
								<label class="form-label">Cancelled Cheque</label>
								<input type="file" class="form-control" name="cheque_upload" id="cheque_upload" value="">
								<a href="" id="cheque_upload_link"></a>
							</div>
							<div class="mb-3">
								<label class="form-label">Passport Number</label>
								<input type="text" class="form-control" name="passport_no" id="passport_no" value="{{ $employee->passport_no }}">
							</div>
							<div class="mb-3">
								<label class="form-label">Passport Upload</label>
								<input type="file" class="form-control" name="passport_upload" id="passport_upload" value="">
								<a href="" id="passport_upload_link"></a>
							</div>
							<div class="mb-3">
								<label class="form-label">Qualification</label>
								<input type="text" class="form-control" name="qualification" id="qualification" value="{{ $employee->qualification }}">
							</div>
							<div class="mb-3">
								<label class="form-label">Qualification Upload</label>
								<input type="file" class="form-control" name="qualification_upload" id="qualification_upload" value="">
								<a href="" id="qualification_upload_link"></a>
							</div>
						</div>
					</div>
					<div class="card-footer text-end">
						<button class="btn btn-primary" type="submit">Upload Documents</button>
					</div>
				</form>
			</div>
			<div class="col-xl-8">
				<div class="row">
					<div class="col-md-12">
						<form class="card needs-validation" novalidate="" id="updateOfficial" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="card-header">
								<h4 class="card-title mb-0">Official Details</h4>
								<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Date of Joining</label>
											<input class="form-control" type="date" placeholder="Date of Joining" name="doj" value="{{ $employee->doj }}" id="doj">
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Access Card Code</label>
											<input class="form-control" type="text" placeholder="Access Code" name="access_code" value="{{ $employee->access_code }}" id="access_code">
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Department</label>
											<select class="form-control" name="department" id="department">
												<option value="">Select</option>
												<option value="marketing" {{ $employee->department == "marketing" ? "selected":"" }}>Marketing</option>
												<option value="incometax" {{ $employee->department == "incometax" ? "selected":"" }}>Income tax</option>
												<option value="mutualfunds" {{ $employee->department == "mutualfunds" ? "selected":"" }}>Mutual Funds</option>
												<option value="operations" {{ $employee->department == "operations" ? "selected":"" }}>Operations</option>
												<option value="finance" {{ $employee->department == "finance" ? "selected":"" }}>Finance</option>
												<option value="hr" {{ $employee->department == "hr" ? "selected":"" }}>HR</option>
												<option value="partner" {{ $employee->department == "partner" ? "selected":"" }}>Partner</option>
												<option value="admin">Admin</option>
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Designation</label>
											<input class="form-control" type="text" placeholder="Designation" name="designation" value="{{ $employee->designation }}" id="designation">
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Role</label>
											<select class="form-control" name="role" id="role">
												<option value="">Select</option>
												<option value="superadmin" {{ $employee->role == "superadmin" ? "selected":"" }}>Super Admin</option>
												<option value="admin" {{ $employee->role == "admin" ? "selected":"" }}>Admin</option>
												<option value="manager" {{ $employee->role == "manager" ? "selected":"" }}>Manager</option>
												<option value="operation" {{ $employee->role == "operation" ? "selected":"" }}>Operations</option>
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">D Drive Access</label>
											<select class="form-control" name="d_drive_access" id="d_drive_access">
												<option value="">Select</option>
												<option value="no" {{ $employee->d_drive_access == "no" ? "selected":"" }}>No</option>
												<option value="yes" {{ $employee->d_drive_access == "yes" ? "selected":"" }}>Yes</option>
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Laptop Name</label>
											<input class="form-control" type="text" placeholder="Laptop Name" name="laptop_name" value="{{ $employee->laptop_name }}" id="laptop_name">
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Laptop Id</label>
											<input class="form-control" type="text" placeholder="Laptop Id" name="laptop_id" value="{{ $employee->laptop_id }}" id="laptop_id">
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Id Card</label>
											<select class="form-control" name="id_card" id="id_card">
												<option value="">Select</option>
												<option value="no" {{ $employee->id_card == "no" ? "selected":"" }}>No</option>
												<option value="yes" {{ $employee->id_card == "yes" ? "selected":"" }}>Yes</option>
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Authorization Letter</label>
											<select class="form-control" name="authorization_letter" id="authorization_letter">
												<option value="">Select</option>
												<option value="no" {{ $employee->authorization_letter == "no" ? "selected":"" }}>No</option>
												<option value="yes" {{ $employee->authorization_letter == "yes" ? "selected":"" }}>Yes</option>
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Exit Data</label>
											<input type="date" class="form-control" name="exit_date" id="exit_date" value="{{ $employee->exit_date }}">
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="mb-3">
											<label class="form-label">Employee Status</label>{{ $employee->employee_status }}
											<select class="form-control" name="employee_status" id="employee_status">
												<option value="">Select</option>
												<option value="Active" {{ $employee->employee_status == "Active" ? "selected":"" }}>Active</option>
												<option value="Inactive" {{ $employee->employee_status == "Inactive" ? "selected":"" }}>Inactive</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-end">
								<button class="btn btn-primary" type="submit">Update Profile</button>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-xl-6">
						<form class="card needs-validation" novalidate="" id="updateBank" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="card-header">
								<h4 class="card-title mb-0">Bank Details</h4>
								<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-sm-8">
												<div class="media">
													<div class="media-body align-self-center">
														<h5 class="mt-0 user-name">Personal</h5>
													</div>
												</div>
											</div>
											<div class="col-sm-4 align-self-center">
												<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Bank Name</label>
													<input class="form-control" type="text" placeholder="Bank Name" name="personal_bank_name" value="{{ $employee->personal_bank_name }}" id="personal_bank_name">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Account Number</label>
													<input class="form-control" type="text" placeholder="Account Number" name="personal_bank_acno" value="{{ $employee->personal_bank_acno }}" id="personal_bank_acno">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">Name as on Bank </label>
													<input type="text" class="form-control" placeholder="Name as on Bank" name="personal_name_as_on_bank" id="personal_name_as_on_bank" value="{{ $employee->personal_name_as_on_bank }}">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">IFSC Code </label>
													<input type="text" class="form-control" placeholder="IFSC Code" name="personal_ifsc_code" id="personal_ifsc_code" value="{{ $employee->personal_ifsc_code }}">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-sm-8">
												<div class="media">
													<div class="media-body align-self-center">
														<h5 class="mt-0 user-name">Salary</h5>
													</div>
												</div>
											</div>
											<div class="col-sm-4 align-self-center">
												<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Bank Name</label>
													<input class="form-control" type="text" placeholder="Bank Name" name="salary_bank_name" value="{{ $employee->salary_bank_name }}" id="salary_bank_name">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Account Number</label>
													<input class="form-control" type="text" placeholder="Account Number" name="salary_bank_acno" value="{{ $employee->salary_bank_acno }}" id="salary_bank_acno">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">Name as on Bank </label>
													<input type="text" class="form-control" placeholder="Name as on Bank" name="salary_name_as_on_bank" id="salary_name_as_on_bank" value="{{ $employee->salary_name_as_on_bank }}">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">IFSC Code </label>
													<input type="text" class="form-control" placeholder="IFSC Code" name="salary_ifsc_code" id="salary_ifsc_code" value="{{ $employee->salary_ifsc_code }}">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-end">
								<button class="btn btn-primary" type="submit">Update Banks</button>
							</div>
						</form>
					</div>
					<div class="col-sm-12 col-xl-6">
						<form class="card needs-validation" novalidate="" id="updateAddress" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="card-header">
								<h4 class="card-title mb-0">Address</h4>
								<div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-sm-8">
												<div class="media">
													<div class="media-body align-self-center">
														<h5 class="mt-0 user-name">Present</h5>
													</div>
												</div>
											</div>
											<div class="col-sm-4 align-self-center">
												<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Address Line 1</label>
													<input class="form-control" type="text" placeholder="present_address_line1" name="present_address_line1" value="{{ $employee->present_address_line1 }}" id="doj">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Address Line 2</label>
													<input class="form-control" type="text" placeholder="present_address_line2" name="present_address_line2" value="{{ $employee->present_address_line }}" id="doj">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">City </label>
													<input type="text" class="form-control" name="present_city" id="present_city" value="{{ $employee->present_city }}">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">State </label>
													<input type="text" class="form-control" name="present_state" id="present_state" value="{{ $employee->present_state }}">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">Pincode </label>
													<input type="text" class="form-control" name="present_pincode" id="present_pincode" value="{{ $employee->present_pincode }}">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-sm-8">
												<div class="media">
													<div class="media-body align-self-center">
														<h5 class="mt-0 user-name">Permanent</h5>
													</div>
												</div>
											</div>
											<div class="col-sm-4 align-self-center">
												<!-- <div class="float-sm-end"><small>10 Hours ago</small></div> -->
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Address Line 1</label>
													<input class="form-control" type="text" placeholder="permanent_address_line1" name="permanent_address_line1" value="{{ $employee->permanent_address_line1 }}" id="doj">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="form-label">Address Line 2</label>
													<input class="form-control" type="text" placeholder="permanent_address_line2" name="permanent_address_line2" value="{{ $employee->permanent_address_line }}" id="doj">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">City </label>
													<input type="text" class="form-control" name="permanent_city" id="permanent_city" value="{{ $employee->permanent_city }}">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">State </label>
													<input type="text" class="form-control" name="permanent_state" id="permanent_state" value="{{ $employee->permanent_state }}">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="mb-3">
													<label class="mandatory ">Pincode </label>
													<input type="text" class="form-control" name="permanent_pincode" id="permanent_pincode" value="{{ $employee->permanent_pincode }}">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-end">
								<button class="btn btn-primary" type="submit">Update Address</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/hr/hr.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection