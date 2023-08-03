@extends('layouts.simple.master')
@section('title', 'Profile Update')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card" id="saveOrderCard">
				<div class="card-header">
					<div class="row">
                    	<div class="col-md-6">
							<h5>Profile Update</h5>
                    	</div>
                    	<div class="col-md-6">
                    	</div>
                  	</div>
				</div>
				<form class="needs-validation" novalidate="" action="{{ route('profileAddressUpdate') }}" id="profileUpdateForm" method="POST">
					{{ csrf_field() }}
					<div class="card-body">
						<div class="dg-black">
							<div class="container">
								<div class="row my-3 mx-auto">
									<div class="col px-auto aug-frame footer-border">
										<div class="form-group mb-3">
											<label for="exampleFormControlInput1">Date of Birth&nbsp;<small>(As on PAN Card)</small></label>
											<input class="form-control digits" id="dob" name="dob" type="text" required="required">
										</div>
										<div class="form-group mb-3">
											<label class="control-label">State</label>
											<select class="form-control js-example-basic-single col-sm-12" id="state" name="state" required="required">
												
											</select>
										</div>
										<div class="form-group mb-3">
											<label class="control-label">City</label>
											<select class="form-control js-example-basic-single col-sm-12" id="city" name="city" required="required">
												
											</select>
										</div>	
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer text-center">
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/form-wizard/form-wizard.js?v=1.0')}}"></script> 
<script src="{{asset('assets/js/augmontcityState.js?v=1.0')}}"></script> 
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
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