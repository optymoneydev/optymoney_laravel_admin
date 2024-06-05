@extends('layouts.simple.master')
@section('title', 'News Letters')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>News Letters</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">News Letters</li>
<li class="breadcrumb-item active">News Letters List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-6">
			<div class="card">
				<div class="card-body">
					<form class="needs-validation" novalidate="" name="addnewsletter" id="addnewsletter" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="modal-header">						
							<h4 class="modal-title">Newsletter</h4>
						</div>
						<div class="modal-body">						 
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12 mb-2">
										<div class="form-group">
											<label class="mandatory mandatory_label">Month and year(Month Year - ex: <small class="text-primary">April 2024</small>)</label>
											<input type="text" class="form-control" name="datetitle" id="datetitle" value="" placeholder="Please enter Month and year" alt="Please enter month and year">
										</div>
									</div>
									<div class="col-md-12 mb-2">
										<div class="form-group">
											<label class="mandatory mandatory_label">Title</label>
											<input type="text" class="form-control" name="title" id="title" value="" placeholder="Please enter post title" alt="Please enter post title">
											<input type="hidden" class="form-control" name="id" id="id" value="">
										</div>
									</div>
									<div class="col-md-12 mb-2">
										<div class="form-group">
											<label class="mandatory mandatory_label">PDF Document</label>
											<input type="file" class="form-control" name="pdfDocument" id="pdfDocument" value="" title="PDF Document" alt="PDF Document">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">						
							<div class="row mt-4">
								<div class="col-md-12 text-center">
									<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveNewsLetter" id="saveNewsLetter">Save</button>
									<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<table class="display" id="newslettersTable">
						<thead>
							<tr>
								<th style="width: 200px">Title</th>
								<th>Month Year</th>
								<th>File</th>
								<th style="width: 10%">Action</th>
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
<script src="{{asset('assets/js/cms/newsletter.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection