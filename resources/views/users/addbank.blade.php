@extends('layouts.simple.master')
@section('title', 'Datatables Server Side')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>User Bank Accounts</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Augmont</li>
<li class="breadcrumb-item active">Banks</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Server Side Processing start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Banks</h5>
					<span>List of banks.</span>
				</div>
				<div class="card-body">
					<div class="table-responsive">
                  <table class="display datatables" id="server-side-datatable">
							<thead>
								<tr>
									<th>Bank Name</th>
									<th>Account Number</th>
                           			<th>IFSC code</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Bank Name</th>
									<th>Account Number</th>
                           			<th>IFSC code</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- Server Side Processing end-->
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/users/banks.js')}}"></script>
@endsection