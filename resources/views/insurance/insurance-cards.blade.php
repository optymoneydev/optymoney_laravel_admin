@extends('layouts.simple.master')
@section('title', 'Insurance')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Insurance</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Insurance</li>
<li class="breadcrumb-item active">Insurance List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="insuranceForm_modal" tabindex="-1" role="dialog" aria-labelledby="insuranceForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addinsurance" id="addinsurance" method="POST" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New Insurance Registration</h4>
										</div>
										<div class="modal-body">						 
											<div class="row">
												<div class="col-md-4 mb-4">
													<div class="form-group">
														<label class="mandatory mandatory_label">Customer Name</label>
														<select class="form-control col-sm-12" name="ins_cust_id" id="ins_cust_id" data-select2-id="ins_cust_id" tabindex="-1" aria-hidden="true" required="">
															<option value="" data-select2-id="2">Select</option>
															@foreach ($clients as $client)
																<option value="{{ $client['pk_user_id'] }}">{{ $client['pan_number'] }} - {{ ucfirst($client['cust_name']) }} - {{ $client['contact_no'] }}</option>
															@endforeach
														</select>
														<div class="invalid-feedback">Please select the customer</div>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<div class="form-group" id="ins_prod_type_group">
														<label class="mandatory mandatory_label">Product Type</label>
														<select class="form-control" name="ins_prod_type" id="ins_prod_type">
															<option value="">Select</option>
															<option value="general">General Insurance</option>
															<option value="life">Life Insurance</option>
															<option value="motor">Motor Insurance</option>
															<option value="health">Health Insurance</option>
														</select>
														<input type="hidden" class="form-control" name="ins_id" id="ins_id" value="">
													</div>
												</div>
												<div class="col-md-4" id="ins_comp_name_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Insurance Company</label>
														<input type="text" class="form-control" name="ins_comp_name" id="ins_comp_name" value="" placeholder="Enter Insurance Company" title="Please enter insurance company name" alt="Insurance Company Name">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_comp_branch_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Branch</label>
														<input type="text" class="form-control" name="ins_comp_branch" id="ins_comp_branch" value="" placeholder="Enter Branch" title="Please enter branch" alt="Branch">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_name_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Policy Name</label>
														<input type="text" class="form-control" name="ins_policy_name" id="ins_policy_name" value="" placeholder="Enter Policy Name" title="Please enter policy name" alt="Policy Name">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_no_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Policy Number</label>
														<input type="text" class="form-control" name="ins_policy_no" id="ins_policy_no" value="" placeholder="Enter Policy Number" title="Please enter policy number" alt="Policy Number">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_issued_data_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Policy Issued Date</label>
														<input type="text" class="form-control digits" name="ins_policy_issued_date" id="ins_policy_issued_date" value="" placeholder="Enter Policy Issued Date" title="Please enter policy issued date" alt="Policy Issued Date">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_maturity_date_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Policy Maturity/Expiry Date</label>
														<input type="text" class="form-control digits" name="ins_policy_maturity_date" id="ins_policy_maturity_date" value="" placeholder="Enter Policy Maturity Date" title="Please enter policy maturity date" alt="Policy Maturity Date">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_prem_amt_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Policy Premium Amount</label>
														<input type="text" class="form-control" name="ins_policy_prem_amt" id="ins_policy_prem_amt" value="" placeholder="Enter Policy Premium Amount" title="Please enter policy premium amount" alt="Policy Premium Amount">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_sa_amt_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Policy Sum Assured</label>
														<input type="text" class="form-control" name="ins_policy_sa_amt" id="ins_policy_sa_amt" value="" placeholder="Enter Policy Sum Assured Amount" title="Please enter policy sum assured amount" alt="Policy Sum Assured Amount">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_term_years_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Terms (Yrs - only for Life Insurance)</label>
														<input type="text" class="form-control" name="ins_policy_term_years" id="ins_policy_term_years" value="0" placeholder="Enter Policy Term Years" title="Please enter policy term years" alt="Policy Term Years">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_premium_pay_term_years_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Premium Pay Terms (Yrs - only for Life Insurance)</label>
														<input type="text" class="form-control" name="ins_policy_premium_pay_term_years" id="ins_policy_premium_pay_term_years_group" value="0" placeholder="Enter Policy Premium Pay Term Years" title="Please enter policy premium pay term years" alt="Policy Premium Pay Term Years">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_pay_mode_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Mode of Payment</label>
														<select  class="form-control" name="ins_policy_pay_mode" id="ins_policy_pay_mode">
															<option value="">Select</option>
															<option value="1">Monthly</option>
															<option value="3">Quarterly</option>
															<option value="6">Half Yearly</option>
															<option value="12">Yearly</option>
															<option value="13">Single</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_next_prem_date_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Next Premium Pay Date</label>
														<input type="text" class="form-control digits" name="ins_policy_next_prem_date" id="ins_policy_next_prem_date" value="" placeholder="Enter Policy Next Premium Pay Date" title="Please enter policy next premium pay date" alt="Policy Next Premium Pay Date">
													</div>
												</div>
												<div class="col-md-4 mb-4"  id="ins_policy_plan_type_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Plan Type</label>
														<select  class="form-control" name="ins_policy_plan_type" id="ins_policy_plan_type">
															<option value="">Select</option>
															<option value="SANCHAYPLUS">SANCHAY PLUS</option>
															<option value="TERM">Term</option>
															<option value="ULIP">ULIP</option>
															<option value="ULPP">ULPP</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4"  id="ins_policy_money_back_group">
													<div class="form-group">
														<label class="mandatory">Money Back</label>
														<select  class="form-control" name="ins_policy_money_back" id="ins_policy_money_back">
															<option value="">Select</option>
															<option value="Y">Yes</option>
															<option value="N">No</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4"  id="ins_policy_acci_death_benefit_group">
													<div class="form-group">
														<label class="mandatory">Accidental Death Benefit</label>
														<select  class="form-control" name="ins_policy_acci_death_benefit" id="ins_policy_acci_death_benefit">
															<option value="">Select</option>
															<option value="Y">Yes</option>
															<option value="N">No</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_status_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Status</label>
														<select  class="form-control" name="ins_policy_status" id="ins_policy_status">
															<option value="">Select</option>
															<option value="active">Active</option>
															<option value="lapsed">Lapsed</option>
															<option value="matured">Matured</option>
															<option value="cancelled">Cancelled</option>
															<option value="surrendered">Surrendered</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_nominee_name_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Nominee Name</label>
														<input type="text" class="form-control" name="ins_policy_nominee_name" id="ins_policy_nominee_name" value="" placeholder="Enter Policy Nominee Name" title="Please enter nominee name" alt="Policy Nominee Name">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_nominee_relation_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Nominee relation</label>
														<select  class="form-control" name="ins_policy_nominee_relation" id="ins_policy_nominee_relation">
															<option value="">Select</option>
															<option value="father">Father</option>
															<option value="mother">Mother</option>
															<option value="spouse">Spouse</option>
															<option value="child">Child</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_veh_type_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Vehicle Type</label>
														<select  class="form-control" name="ins_policy_veh_type" id="ins_policy_veh_type">
															<option value="">Select</option>
															<option value="2">2 Wheeler</option>
															<option value="3">3 Wheeler</option>
															<option value="4">4 Wheeler</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_veh_reg_no_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Vehicle Registration Number</label>
														<input type="text" class="form-control" name="ins_policy_veh_reg_no" id="ins_policy_veh_reg_no" value="" placeholder="Enter Vehicle Registration Number" title="Please enter vehicle registration number" alt="Vehicle Registration Number">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_veh_model_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Vehicle Make and Model</label>
														<input type="text" class="form-control" name="ins_policy_veh_model" id="ins_policy_veh_model" value="" placeholder="Enter Vehicle Model" title="Please enter vehicle model" alt="Vehicle Model">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_loan_taken_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Loan Taken</label>
														<select  class="form-control" name="ins_policy_loan_taken" id="ins_policy_loan_taken">
															<option value="">Select</option>
															<option value="Y">Yes</option>
															<option value="N">No</option>
														</select>
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_loan_date_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Loan Date</label>
														<input type="text" class="form-control digits" name="ins_policy_loan_date" id="ins_policy_loan_date" value="" placeholder="Enter Loan Date" title="Please enter loan date" alt="Policy Loan Date">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_bal_units_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Balance Units</label>
														<input type="text" class="form-control" name="ins_policy_bal_units" id="ins_policy_bal_units" value="" placeholder="Enter Balance Units" title="Please enter balance units" alt="Balance Units">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_bal_date_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Balance Units As on Date</label>
														<input type="date" class="form-control" name="ins_policy_bal_date" id="ins_policy_bal_date" value="" placeholder="Enter Date" title="Please enter date" alt="As on Date">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_cur_value_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Current Value</label>
														<input type="text" class="form-control" name="ins_policy_cur_value" id="ins_policy_cur_value" value="" placeholder="Enter Current Value" title="Please enter current value" alt="Current Value">
													</div>
												</div>
												<div class="col-md-4 mb-4" id="ins_policy_exp_maturity_value_group">
													<div class="form-group">
														<label class="mandatory mandatory_label">Expected Maturity Value</label>
														<input type="text" class="form-control" name="ins_policy_exp_maturity_value" id="ins_policy_exp_maturity_value" value="" placeholder="Enter Expected Maturity Value" title="Please enter expected maturity value" alt="Expected Maturity Value">
													</div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-6 mb-4">
													<div class="form-group">
														<label>Upload Documents</label>
														<input type="file" name="ins_policy_document[]" multiple="multiple" class="form-control" id="ins_policy_document">
													</div>
												</div>
												<div class="col-md-6 mb-4" id="insuranceDocuments">

												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-12 mb-4">
													<div class="form-group">
														<label class="mandatory">Remarks</label>
														<input type="text" class="form-control" name="ins_policy_remarks" id="ins_policy_remarks" value="" placeholder="Enter Remarks" title="Please enter remarks" alt="Remarks">
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveInsurance" id="saveInsurance">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-insurance">
							<thead>
								<tr>
									<th>Product Type</th>
									<th>Customer Name</th>
									<th>Policy Name</th>
									<th style="width: 10%">Issued Date</th>
									<th style="width: 10%">Maturity Date</th>
									<th style="width: 10%">Premium Amount</th>
									<th style="width: 10%">Next Premium Date</th>
									<th style="width: 10%">Documents</th>
									<th style="width: 10%">Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($insurances as $insurance)
								<tr>
									<td>{{ ucfirst($insurance['ins_prod_type']) }}</td>
									<td>{{ ucfirst($insurance['cust_name']) }}</td>
									<td>{{ ucfirst($insurance['ins_policy_name']) }}</td>
									<td>{{ ucfirst($insurance['ins_policy_issued_date']) }}</td>
									<td>{{ ucfirst($insurance['ins_policy_maturity_date']) }}</td>
									<td>{{ ucfirst($insurance['ins_policy_prem_amt']) }}</td>
									<td>{{ ucfirst($insurance['ins_policy_next_prem_date']) }}</td>
									@if($insurance['ins_policy_document'] =='' || $insurance['ins_policy_document'] ==null)
										<td></td>
									@else
										<td>
											@if (str_contains($insurance['ins_policy_document'], '|'))
												@foreach (explode('|',$insurance['ins_policy_document']) as $file) 
													@if ($file!="")
													<a target="_blank" href="{{ url('itr/getfile/'.$insurance['ins_cust_id'].'/insurance/'.$file ) }}">{{ $file }}</a>
													@endif
												@endforeach
											@else
												<a target="_blank" href="{{ url('itr/getfile/'.$insurance['ins_cust_id'].'/insurance/'.$insurance['ins_policy_document'] ) }}">{{$insurance['ins_policy_document'] }}</a>
											@endif
										</td>        
									@endif
									<td>
										<div class="m-b-30">
											<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
												<div class="btn-group" role="group">
													<button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
													<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<a href="#" class="dropdown-item viewInsurance" data-id="{{ $insurance['ins_id'] }}">View</a>
														<a href="#" class="dropdown-item editInsurance" data-id="{{ $insurance['ins_id'] }}">Edit</a>
														<a href="#" class="dropdown-item deleteInsurance" data-id="{{ $insurance['ins_id'] }}">Delete</a>
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
<script src="{{asset('assets/js/insurance/insurance.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection