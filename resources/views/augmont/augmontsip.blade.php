@extends('layouts.simple.master')
@section('title', 'Datatables Server Side')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Augmont Orders</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Augmont</li>
<li class="breadcrumb-item active">Subscription</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Server Side Processing start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Subscriptions</h5>
					<span>List of all subscriptions.</span>
				</div>
				<div class="card-body">
					<div class="table-responsive">
                  		<table class="display datatables" id="server-side-datatable">
							<thead>
								<tr>
									<th>Subscription</th>
									<th>Total Count</th>
                           			<th>Paid Count</th>
									<th>Remaining</th>
									<th>Next Due Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
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
<script src="{{asset('assets/js/augmont/subscriptions.js?v=1.0')}}"></script>
@endsection