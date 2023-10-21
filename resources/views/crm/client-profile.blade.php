@extends('layouts.simple.master')
@section('title', 'Client Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/rating.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/photoswipe.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Client Profile</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Clients</li>
<li class="breadcrumb-item active">Client Profile</li>
<p>{{  now()->toDateTimeString() }}</p>
@endsection

@section('content')
<div class="container-fluid">
	<div>
		<div class="row product-page-main p-0">
			<div class="col-xl-4 xl-cs-65 box-col-12">
				<div class="card xl-none">
					<div class="ecommerce-widget card-body">
						<div class="row">
							<div class="col-4 col-sm-4">
								<h6>Mutual Funds</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<span>Total Investment</span>
								<h5 class="total-num" id="totInv"></h5>
							</div>
							<div class="col-4">
								<span>Current Value</span>
								<h5 class="total-num" id="curInv"></h5>
							</div>
							<div class="col-4" id="profitData">
								<span>Profit</span>
								<h5 class="total-num font-success" id="profitVal"></h5>
							</div>
							<div class="col-4" id="lossData">
								<span>Loss</span>
								<h5 class="total-num font-danger" id="lossVal"></h5>
							</div>
						</div>
					</div>
				</div>
				<div class="card xl-none">
					<div class="ecommerce-widget card-body">
						<div class="row">
							<div class="col-12 col-sm-12">
								<h6>Gold Investments</h6>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-12">
								@if($client->augid !='')         
									<h6 id="augmontIdTitle">Augmont Id : {{ $client->augid }}</h6>
								@else
									<a href="#" id="createAugmontAccountLink">Create Account</a>
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<span>Total Gold (in Grams)</span>
								<h5 class="total-num" id="totGold"></h5>
							</div>
							<div class="col-6">
								<span>Total Silver (in Grams)</span>
								<h5 class="total-num" id="totSilver"></h5>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-5 xl-100 box-col-6">
				<div class="card">
					<div class="card-body">
						<div class="product-page-details">
							<h3>{{ $client->cust_name }}</h3>
							<input type="text" value="{{ $client->pk_user_id }}" id="clientId">
						</div>
						<table class="product-page-width">
							<tbody>
								<tr>
									<td> <b>Email &nbsp;&nbsp;&nbsp;:</b></td>
									<td>{{ $client->login_id }}</td>
								</tr>
								<tr>
									<td> <b>Date of Birth &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
									<td class="txt-success">{{ $client->dob }}</td>
								</tr>
								<tr>
									<td> <b>Mobile &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
									<td><span>India +91 {{ $client->contact }} / {{ $client->contact_no }}</span></td>
								</tr>
								<tr>
									<td> <b>City &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
									<td>{{ $client->city }}</td>
								</tr>
								<tr>
									<td> <b>Gender &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
									<td><span>{{ ucfirst($client->sex) }}</span></td>
								</tr>
								<tr>
									<td> <b>Father Name</b></td>
									<td><span>{{ ucfirst($client->father_name) }}</span></td>
								</tr>
								<tr>
									<td> <b>Profession</b>
									<td><span>{{ ucfirst($client->profession) }}</span></td>
								</tr>
							</tbody>
						</table>
						<ul class="product-color">
							<li class="bg-primary"></li>
							<li class="bg-secondary"></li>
							<li class="bg-success"></li>
							<li class="bg-info"></li>
							<li class="bg-warning"></li>
						</ul>
						<hr>
						<p>
							<h6 class="product-title">Present Address</h6>
							<span>{{ $client->address1 }}</span>, <br>
							<span>{{ ucfirst($client->address2) }}</span>, <br>
							<span>{{ ucfirst($client->city) }}</span>, <br>
							<span>{{ ucfirst($client->state) }}</span> - 
							<span>{{ $client->pincode }}</span>
						</p>
						<hr>
						<p>
							<h6 class="product-title">Correspondance Address</h6>
							<span>{{ $client->cor_addr1 }}</span>, <br>
							<span>{{ ucfirst($client->cor_addr2) }}</span>, <br>
							<span>{{ ucfirst($client->cor_city) }}</span>, <br>
							<span>{{ ucfirst($client->cor_state) }}</span> -
							<span>{{ $client->cor_zip }}</span>
						</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 xl-cs-35 box-col-6">
				<div class="card">
					<div class="card-body">
						<!-- side-bar colleps block stat-->
						<div class="filter-block">
							<h4>Documents</h4>
							<ul>
								<li>
									<h6><i class="fa fa-location-arrow"></i>   PAN</h6>
									<span>{{ $client->pan_number }}</span>
									@if($client->pan_upload !='')         
										<span><a target="_blank" href="{{ url('itr/getfile/'.$client->pk_user_id.'/'.$client->pan_upload) }}"> Download</a></span>
									@endif
								</li>
								<li>
									<h6><i class="fa fa-calendar"></i>   Aadhaar Number</h6>
									<span>{{ $client->aadhaar_no }}</span>
									@if($client->aadhar_upload !='')         
										<span><a target="_blank" href="{{ url('itr/getfile/'.$client->pk_user_id.'/'.$client->aadhar_upload) }}"> Download</a></span>
									@endif
								</li>
								<li>
									<h6><i class="fa fa-location-arrow"></i>   Signature</h6>
									<span>{{ $client->signature }}</span>
									@if($client->signature !='')
										<span><a target="_blank" href="{{ url('itr/getfile/'.$client->pk_user_id.'/'.$client->signature) }}"> Download</a></span>
									@endif
								</li>
								<li>
									<h6><i class="fa fa-calendar"></i>   Cancelled Cheque</h6>
									<span>{{ $client->cancelledcheque }}</span>
									@if($client->cancelledcheque !='')
										<span><a target="_blank" href="{{ url('itr/getfile/'.$client->pk_user_id.'/'.$client->cancelledcheque) }}"> Download</a></span>
									@endif
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<div class="collection-filter-block">
							<ul class="pro-services">
								<li>
									<div class="media">
										<i data-feather="truck"></i>
										<div class="media-body">
											<h5>Nominee Details</h5>
											<p>
												<b>Nominee Name</b>
												<span>{{ $client->nominee_name }}</span>
												<b>Nominee Relation</b>
												<span>{{ $client->r_of_nominee_w_app }}</span>
												<b>Nominee Date of Birth</b>
												<span>{{ $client->nominee_dob }}</span>
											</p>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				<!-- silde-bar colleps block end here-->
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-xl-12 xl-100">
		<div class="card">
			<div class="card-body">
				<ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
					<li class="nav-item"><a class="nav-link active" id="pills-personal-tab" data-bs-toggle="pill" href="#pills-personal" role="tab" aria-controls="pills-personal" aria-selected="true"><i class="icofont icofont-ui-home"></i>Personal Details</a></li>
					<li class="nav-item"><a class="nav-link" id="pills-wealth-tab" data-bs-toggle="pill" href="#pills-wealth" role="tab" aria-controls="pills-wealth" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>Wealth</a></li>
					<li class="nav-item"><a class="nav-link" id="pills-will-tab" data-bs-toggle="pill" href="#pills-will" role="tab" aria-controls="pills-will" aria-selected="false"><i class="icofont icofont-contacts"></i>Will</a></li>
					<li class="nav-item"><a class="nav-link" id="pills-tax-tab" data-bs-toggle="pill" href="#pills-tax" role="tab" aria-controls="pills-tax" aria-selected="true"><i class="icofont icofont-ui-home"></i>Tax</a></li>
					<li class="nav-item"><a class="nav-link" id="pills-insurance-tab" data-bs-toggle="pill" href="#pills-insurance" role="tab" aria-controls="pills-insurance" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>Insurance</a></li>
					<li class="nav-item"><a class="nav-link" id="pills-pms-tab" data-bs-toggle="pill" href="#pills-pms" role="tab" aria-controls="pills-pms" aria-selected="false"><i class="icofont icofont-contacts"></i>PMS</a></li>
					<li class="nav-item"><a class="nav-link" id="pills-gold-tab" data-bs-toggle="pill" href="#pills-gold" role="tab" aria-controls="pills-gold" aria-selected="false"><i class="icofont icofont-contacts"></i>Gold</a></li>
				</ul>
				<div class="tab-content" id="pills-warningtabContent">
					<div class="tab-pane fade show active" id="pills-personal" role="tabpanel" aria-labelledby="pills-personal-tab">
						<div class="row mb-5">
							<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
							
								<p>
									<h6 class="product-title">Personal Bank Details</h6>
									<hr>
									<table class="product-page-width">
										<tbody>
											@if (count($client->bank) > 0)
											<tr>
												<td> <b>Bank Name &nbsp;&nbsp;&nbsp;:</b></td>
												<td>{{ $client->bank[0]->bank_name }}</td>
											</tr>
											<tr>
												<td> <b>Account Number &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td class="txt-success">{{ $client->bank[0]->acc_no }}</td>
											</tr>
											<tr>
												<td> <b>Branch &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td><span>{{ $client->bank[0]->branch_name }}</span></td>
											</tr>
											<tr>
												<td> <b>IFSC Number &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td>{{ $client->bank[0]->ifsc_code }}</td>
											</tr>
											@else
      											<tr><td>No Bank Details</td></tr>
											@endif
										</tbody>
									</table>
								</p>
							</div>
							<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
								<p>
									<h6 class="product-title">Official Details</h6>
									<hr>
									<table class="product-page-width">
										<tbody>
											<tr>
												<td> <b>BSE Id &nbsp;&nbsp;&nbsp;:</b></td>
												<td>{{ $client->bse_id }}</td>
											</tr>
											<tr>
												<td> <b>Tax Status &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td class="txt-success">{{ ucfirst($client->taxstatus) }}</td>
											</tr>
											<tr>
												<td> <b>KYC Onboarding Id &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td><span>{{ $client->kyc_onboarding_id }}</span></td>
											</tr>
											<tr>
												<td> <b>KYC Status &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td>{{ $client->kyc_status }}</td>
											</tr>
										</tbody>
									</table>
								</p>
							</div>
							<div class="col-sm-3 col-lg-3 order-sm-2 order-xl-2">
								<p>
									<h6 class="product-title">&nbsp;</h6>
									<hr>
									<table class="product-page-width">
										<tbody>
											<tr>
												<td> <b>NSDL KYC Response &nbsp;&nbsp;&nbsp;:</b></td>
												<td>{{ $client->nsdl_kyc_res }}</td>
											</tr>
											<tr>
												<td> <b>UCC Submission &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td class="txt-success">{{ $client->ucc_submission }}</td>
											</tr>
											<tr>
												<td> <b>UCC Form URL &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;</b></td>
												<td><span><a target="_blank" href="{{ url('itr/getfile/'.$client->pk_user_id.'/'.$client->ucc_form_filename) }}">Download Form</a></span></td>
											</tr>
										</tbody>
									</table>
								</p>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="pills-wealth" role="tabpanel" aria-labelledby="pills-wealth-tab">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-striped table-bordered dt-responsive" id='transaction_list'>
									<thead>
										<tr>
											<th style="width: 300px">Scheme&nbsp;Name</th>
											<th>Folio</th>
											<th>Scheme&nbsp;Type</th>
											<th>Purchase</th>
											<th>Unit</th>
											<th>Current&nbsp;Value</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="pills-will" role="tabpanel" aria-labelledby="pills-will-tab">
						<p class="mb-0 m-t-30"></p>
					</div>
					<div class="tab-pane fade" id="pills-tax" role="tabpanel" aria-labelledby="pills-tax-tab">
						<div class="row">
							<div class="col-md-6">
								<p>User Uploaded Documents</p>
								<div>    
									<table class="table table-striped table-bordered dt-responsive" id='tax_list'>
										<thead class="btn-primary-rect"> 
											<tr>
												<th>Name </th>
												<th>Document</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div class="col-md-6">
								<p>Admin Uploaded Documents</p>
								<div>    
									<table class="table table-striped table-bordered dt-responsive" id='tax_list1'>
										<thead class="btn-primary-rect"> 
											<tr>
												<th>Assessment Year</th>
												<th>Document</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="pills-insurance" role="tabpanel" aria-labelledby="pills-insurance-tab">
						<table class="display" id="export-button-insurance">
							<thead>
								<tr>
									<th>Product Type</th>
									<th>Policy Name</th>
									<th>Issued Date</th>
									<th>Maturity Date</th>
									<th>Premium Amount</th>
									<th>Next Premium Date</th>
									<th>Documents</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="pills-pms" role="tabpanel" aria-labelledby="pills-pms-tab">
						<table class="display" id="export-button-pms">
							<thead>
								<tr>
									<th>Product Type</th>
									<th>Transaction Type</th>
									<th>Transaction Date</th>
									<th>Transaction Amount</th>
									<th>Documents</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="pills-gold" role="tabpanel" aria-labelledby="pills-gold-tab">
						<table class="display" id="export-button-gold">
							<thead>
								<tr>
									<th>Transaction Date</th>
									<th>Transaction Id</th>
									<th>Order Type</th>
									<th>Metal Type</th>
                           			<th>Gold/Silver Grams</th>
									<th>Purchase/Sell Amount</th>
									<th>Payment Id</th>
									<th>Invoice</th>
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
	<div class="user-profile">
		<div class="row">
			<!-- user profile first-style start-->
			<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="pswp__bg"></div>
				<div class="pswp__scroll-wrap">
					<div class="pswp__container">
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
					</div>
					<div class="pswp__ui pswp__ui--hidden">
						<div class="pswp__top-bar">
							<div class="pswp__counter"></div>
							<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
							<button class="pswp__button pswp__button--share" title="Share"></button>
							<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
							<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
							<div class="pswp__preloader">
								<div class="pswp__preloader__icn">
									<div class="pswp__preloader__cut">
										<div class="pswp__preloader__donut"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
							<div class="pswp__share-tooltip"></div>
						</div>
						<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
						<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
						<div class="pswp__caption">
							<div class="pswp__caption__center"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mftransactionModal" tabindex="-1" role="dialog" aria-labelledby="mftransactionModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="scheme_title">Modal title</h5>
					<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table id="detailsMFTable">
						<thead class="btn-primary-rect"> 
							<tr>
								<th>Date</th>
								<th>Trans No</th>
								<th>Type</th>
								<th>Units</th>
								<th>NAV</th>
								<th>Amount</th>
								<!-- <th>Id</th>-->
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!}
</script>
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
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.js')}}"></script>
<script src="{{asset('assets/js/crm/client.js')}}"></script>

<script src="{{asset('assets/js/sidebar-menu.js')}}"></script>
<!-- <script src="{{asset('assets/js/rating/jquery.barrating.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/rating/rating-script.js')}}"></script> -->
<script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
<script src="{{asset('assets/js/ecommerce.js')}}"></script>
@endsection