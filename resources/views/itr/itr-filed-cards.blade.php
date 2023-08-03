@extends('layouts.simple.master')
@section('title', 'ITR V List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>ITR V List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">ITR</li>
<li class="breadcrumb-item active">ITR V List</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="itrvForm_modal" tabindex="-1" role="dialog" aria-labelledby="itrvForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" id="addITRV" method="POST" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New ITR V Upload</h4>
											<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">						 
											<div class="row mb-4">
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory mandatory_label">Customer Name</label>
														<select class="form-control col-sm-12" name="itr_cust_id" id="itr_cust_id" data-select2-id="itr_cust_id" tabindex="-1" aria-hidden="true" required="">
															<option value="" data-select2-id="2">Select</option>
															@foreach ($clients as $client)
																<option value="{{ $client['pk_user_id'] }}">{{ $client['pan_number'] }} - {{ ucfirst($client['cust_name']) }} - {{ $client['contact_no'] }}</option>
															@endforeach
														</select>
														<div class="invalid-feedback">Please select the customer</div>
													</div>
												</div>
											</div>
											<div class="row mb-4">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">PAN Number</label>
														<input type="text" class="form-control" value="" id="pancheck" name="pan" placeholder="Enter Pan Number" pattern="[A-Za-z]{3}[pP]{1}[A-Za-z]{1}[0-9]{4}[A-Za-z]{1}" required="">
														<div class="invalid-feedback">Please enter valid PAN</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Assessment Year</label>
														<select class="form-control" name="year" required="">
															<option value="2022-23">2022-23</option>
															<option value="2021-22">2021-22</option>
															<option value="2020-21">2020-21</option>
															<option value="2019-20">2019-20</option>
															<option value="2018-19">2018-19</option>
															<option value="2017-18">2017-18</option>
														</select>
														<div class="invalid-feedback">Please select the assessment year</div>
													</div>
												</div>
											</div>
											<div class="row mb-4">
												<div class="col-md-12">
													<div class="form-group">
														<label>Upload Documents</label>
														<input type="file" id="itrv_file" name="itrv_file" class="form-control" accept="application/pdf" required=""> 
													</div>
												</div>
											</div>
											<div class="row mb-4">
												<div class="col-md-12">
													<div class="form-group">
														<label>Upload Computation Documents</label>
														<input type="file" id="itrv_comp_file" name="itrv_comp_file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"> 
													</div>
												</div>
											</div>
											<div class="row mb-4">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Section 80C</label>
														<input type="number" class="form-control" value="" id="sec_80c" name="sec_80c" placeholder="Enter 80C Amount" required="">
														<div class="invalid-feedback">Please enter valid number</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Section 80D</label>
														<input type="number" class="form-control" value="" id="sec_80d" name="sec_80d" placeholder="Enter 80D Amount" required="">
														<div class="invalid-feedback">Please enter valid number</div>
													</div>
												</div>
											</div>
											<div class="row mb-4">
												<div class="col-md-12">
													<div class="form-group">
														<label class="mandatory">Remarks</label>
														<textarea class="form-control" name="itrv_remarks" id="itrv_remarks" rows="3"></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button type="submit" class="btn btn-primary btn_upload" style="display: " id="btn_upload" name="btn_upload" value="upload">Upload</button>
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-itrv">
							<thead>
								<tr>
									<th>Name</th>
                                    <th>Email</th>
                                    <th>Assessment Year</th>
                                    <th>PAN</th>
									<th>Section 80C</th>
									<th>Section 80D</th>
                                    <th>File</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($articles as $article)
								<tr>
									<td>{{ ucfirst($article['cust_name']) }}</td>
									<td>{{ ucfirst($article['login_id']) }}</td>
									<td>{{ ucfirst($article['asses_year']) }}</td>
									<td>{{ ucfirst($article['pan_number']) }}</td>
									<td>{{ ucfirst($article['sec_80c']) }}</td>
									<td>{{ ucfirst($article['sec_80d']) }}</td>
									<td>
										@if ($article['itr_v']!="")
											<a target="_blank" href="{{ url('itr/getfile/'.$article['fr_user_id'].'/'.$article['itr_v'] ) }}">ITR-V</a>    
										@endif
										@if ($article['itrv_comp_file']!="")
											<a target="_blank" href="{{ url('itr/getfile/'.$article['fr_user_id'].'/'.$article['itrv_comp_file'] ) }}">Computation</a>    
										@endif
									</td>
									<td>
										<div class="m-b-30">
											<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
												<div class="btn-group" role="group">
													<button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
													<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<a href="#" class="dropdown-item editITRV" data-id="{{ $article['id'] }}">Edit</a>
														<a href="#" class="dropdown-item deleteITRV" data-id="{{ $article['id'] }}">Delete</a>
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
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/itr/itr.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection