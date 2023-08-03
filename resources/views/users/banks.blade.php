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
	@if(isset($message))
		@if($message!="")
		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-danger">
					{{ $message ?? '' }}
				</div>
			</div>
		</div>
		@endif
	@endif
	<div class="row">
		<div class="col-xl-6 col-lg-6 col-md-12 xl-50 morning-sec box-col-12">
			<div class="card">
				<div class="card-header">
					<h5>Bank Account</h5>
				</div>
				<form class="form theme-form needs-validation" novalidate="" action="{{url('users/createBankAccount')}}" method="POST" id="bankAccount">
					{{ csrf_field() }}
                    <div class="card-body">
                      	<div class="row">
                        	<div class="col">
                          		<div class="mb-3">
									<input class="form-control" type="text" id="bank_name" name="bank_name" required placeholder="Bank Name" data-bs-original-title="" title="">
                          		</div>
							</div>
                      	</div>
                      	<div class="row">
                        	<div class="col">
                          		<div class="mb-3">
									<input class="form-control" type="text" id="acc_no" name="acc_no" required placeholder="Account Number" data-bs-original-title="" title="">
                          		</div>
                        	</div>
                      	</div>
                      	<div class="row">
                        	<div class="col">
                          		<div class="mb-3">
                            		<input class="form-control" type="text" id="ifsc_code" name="ifsc_code" required placeholder="IFSC Code" data-bs-original-title="" title="">
                          		</div>
							</div>
                      	</div>
					</div>
					<div class="card-footer text-end">
						<button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Submit</button>
						<input class="btn btn-light" type="reset" value="Cancel" data-bs-original-title="" title="">
					</div>
				</form>
			</div>
		</div>
		<div class="col-xl-6 col-lg-6 col-md-12 xl-50 morning-sec box-col-12">
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
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/users/banks.js')}}"></script>
<script>
                        (function() {
                        'use strict';
                        window.addEventListener('load', function() {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.getElementsByClassName('needs-validation');
                        // Loop over them and prevent submission
                        var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                        }, false);
                        });
                        }, false);
                        })();
                        
                     </script>
@endsection