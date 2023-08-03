@extends('layouts.simple.master')
@section('title', 'ITR Helpdesk')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>ITR Helpdesk List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">ITR</li>
<li class="breadcrumb-item active">ITR Helpdesk List</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<table class="display" id="export-button-itrhelpdesk">
							<thead>
								<tr>
									<th>Date of Submission</th>
									<th>Taxation</th>
									<th>Name</th>
									<th>Email</th>
									<th>Mobile</th>		
									<th>Description</th>
                                    <th>Documents</th>
                                    <th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($articles as $article)
								<tr>
									<td>{{ $article['upload_date'] }}</td>
									<td>{{ ucfirst($article['itr_e']) }}</td>
									<td>{{ ucfirst($article['cust_name']) }}</td>
									<td>{{ ucfirst($article['login_id']) }}</td>
									<td>{{ ucfirst($article['contact_no']) }}</td>
									<td>{{ ucfirst($article['description']) }}</td>
									<td>
										@foreach (explode('|',$article['fileitr']) as $file) 
											@if ($file!="")
												<a target="_blank" href="{{ url('itr/getfile/'.$article['user_id'].'/itrv1/'.$file ) }}">Download</a>    
											@endif
										@endforeach
										@foreach (explode('|',$article['addfileitr']) as $file) 
											@if ($file!="")
												<a target="_blank" href="{{ url('itr/getfile/'.$article['user_id'].'/itrv1/'.$file ) }}">Download</a>    
											@endif
										@endforeach
									</td>
									<td>
										<div class="m-b-30">
											<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
												<div class="btn-group" role="group">
													<button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
													<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<a href="{{ url('itr/helpdeskCard/' . $article['id'] . '/view') }}" class="dropdown-item">View</a>
														<a href="{{ url('crm/helpdeskCard/' . $article['id'] . '/edit') }}" class="dropdown-item">Edit</a>
														<!-- <a href="{{ url('crm/clientCard/' . $article['pk_user_id'] . '/edit') }}" class="dropdown-item">Delete</a> -->
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