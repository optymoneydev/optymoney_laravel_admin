@extends('layouts.simple.master')
@section('title', 'KYC')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>KYC</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Augmont</li>
<li class="breadcrumb-item active">KYC</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card" id="saveOrderCard">
				<div class="card-header">
					<div class="row">
                    	<div class="col-md-6">
							<h5>KYC</h5>
                    	</div>
                    	<div class="col-md-6">
                    	</div>
                  	</div>
				</div>
				{{Session::get('url')}}
				@if(!session()->has('url'))
					<form class="needs-validation" novalidate="" action="{{ route('kycfileUpload') }}" id="kycForm" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="card-body">
							<div class="dg-black">
								<div class="container">
									<div class="row my-3 mx-auto">
										<div class="col px-auto aug-frame footer-border">
											<div class="row ">
												<div class="col-12">
													<div class="position-relative"></div>
												</div>
											</div>
											<div class="form-group mb-3">
												<label class="form-label" for="validationDefault01">Name as on PAN</label>
												<input class="form-control" id="panName" name="panName" type="text" placeholder="Name" required="" data-bs-original-title="" title="">
												<div class="invalid-feedback">Please enter valid input.</div>
											</div>
											<div class="form-group mb-3">
												<label class="form-label" for="validationDefault01">Date of Birth</label>
												<input class="form-control digits" id="panDOB" name="panDOB" type="text" placeholder="Date of birth" required="" >
												<!-- <input class="form-control" id="panDOB" name="panDOB" type="date" placeholder="Date of birth" required="" data-bs-original-title="" title=""> -->
												<div class="invalid-feedback">Please select date of birth.</div>
											</div>
											<div class="form-group mb-3">
												<label class="form-label" for="validationDefault01">PAN Number <br><small>Ex: ABCDE1234F</small></label>
												<input class="form-control" id="panNumber" name="panNumber" type="text" placeholder="ABCDE1234F" required="" data-bs-original-title="" title="" >
												<div class="invalid-feedback">Please enter valid PAN Number</div>
											</div>
											<div class="form-group mb-3">
												<label class="form-label" for="validationDefault01">Upload PAN Card<br><small>(Allow only jpg and png formats. File must be lessthan 4MB)</small></label>
												<input class="form-control" type="file" id="panFile" name="panFile" aria-label="file example" required="" data-bs-original-title="" title="" accept="application/pdf, image/*">
												<div class="invalid-feedback">Please choose jpg/png/gif/pdf with lessthan 4MB file size</div>
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
				@else
				<iframe src="{{ Session::get('url') }}" id="myIframe">Your browser isn't compatible</iframe>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/form-wizard/form-wizard.js?v=1.0')}}"></script> 
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

   	$('#panFile').on('change', function() {
		var fileName = this.files[0].name;
		var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png" || extFile=="pdf"){
			if((this.files[0].size / 1024 / 1024) >4) {
				var isValid = false;
			} else {
				var isValid = true;
			}
        }else{
            var isValid = false;
        }
		
	});

	var iframe = document.getElementById("myIframe");
    
    // Adjusting the iframe height onload event
    iframe.onload = function(){
        iframe.style.height = (iframe.clientHeight-50) + 'em';
    }
</script>
@endsection