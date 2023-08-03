@extends('layouts.simple.master')
@section('title', 'Email Formats')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Email Formats</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Email Formats</li>
<li class="breadcrumb-item active">Email Formats List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="emailFormatForm_modal" tabindex="-1" role="dialog" aria-labelledby="emailFormatForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addEmailFormat" id="addEmailFormat" method="POST">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New Email Format Registration</h4>
										</div>
										<div class="modal-body">
											<div class="card">
												<div class="card-body" id="emailFormatContent_sample">
													<!DOCTYPE html>
													<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
														<head>
															<title></title>
															<meta charset="utf-8" />
															<meta content="width=device-width, initial-scale=1.0" name="viewport" />
															<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
															<style>
																* { box-sizing: border-box; }
																th.column { padding: 0 }
																a[x-apple-data-detectors] { color: inherit !important; text-decoration: inherit !important; }
																#MessageViewBody a { color: inherit; text-decoration: none; }
																p { line-height: inherit }
																@media (max-width:660px) {
																	.icons-inner { text-align: center; }
																	.icons-inner td { margin: 0 auto; }
																	.row-content { width: 100% !important; }
																	.image_block img.big { width: auto !important; }
																	.mobile_hide { display: none; }
																	.stack .column { width: 100%; display: block; }
																	.mobile_hide { min-height: 0; max-height: 0; max-width: 0; overflow: hidden; font-size: 0px; }
																}
															</style>
														</head>
														<body style="background-color: #f1f4f8; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
															<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f1f4f8;" width="100%">
																<tbody>
																	<tr>
																		<td>
																			<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="margin-top: 20px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="75%">
																				<tbody>
																					<tr>
																						<th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px;" width="100%">
																							<table border="0" cellpadding="20" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
																								<tr>
																									<td>
																										<div align="left" style="line-height:10px">
																											<div id="columns">
																												<div id="column1">
																													<div contenteditable="true">
																														<p>
																															<img alt="Image" src="https://admin.optymoney.com/assets/images/logo/logo.png" style="display: block; height: auto; border: 0; max-width: 100%;" title="Image"/>
																														</p>
																													</div>
																												</div>
																											</div>
																										</div>
																									</td>
																								</tr>
																							</table>
																						</th>
																					</tr>
																				</tbody>
																			</table>
																			<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="75%">
																				<tbody>
																					<tr>
																						<th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px;" width="100%">
																							<table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
																								<tr>
																									<td style="padding-bottom:10px;padding-left:40px;padding-right:40px;padding-top:20px;">
																										<div style="font-family: sans-serif">
																											<div style="font-size: 12px; color: #555555; line-height: 1.2; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
																												<div id="header">
																													<div id="headerLeft">
																														<h2 id="sampleTitle" contenteditable="true" style="font-size:46px;color:#003188; text-align: center; "> Newsletter Subscription </h2>
																													</div>
																													<div style="text-decoration:none;display:inline-block;color:#6d89bc;width:auto;font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;">
																														<span style="font-size:16px;display:inline-block;letter-spacing:normal;">
																															<span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">
																																<strong>Dear <--NAME--></strong>
																															</span>
																														</span>
																														<p>&nbsp;</p>
																													</div>
																													<div id="headerRight" style="font-size:16px;color:#6d89bc; ">
																														<div contenteditable="true">
																															<p>Greetings from Optymoney, the one stop platform for all your personal finance needs </p>
																															<p>Thanks for being a "OPTYMONEY Digest" newsletter subscriber!</p>
																															<p>It would give you a wrap-up of the weekly update on the market 
																															and a glance <br>of the tax and other related updates impacting your 
																															personal finances. <br>Thank you for taking our quick survey!</p>
																														</div>
																													</div>
																												</div>
																											</div>
																										</div>
																									</td>
																								</tr>
																							</table>
																							<table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
																								<tr>
																									<td style="padding-bottom:10px;padding-left:40px;padding-right:40px;padding-top:20px;">
																										<div style="font-family: sans-serif">
																											<div style="font-size: 12px; color: #555555; line-height: 1.2; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
																												<div id="headerRight" style="font-size:16px;color:#6d89bc; ">
																													<div contenteditable="true">
																														<p style="margin: 0; font-size: 16px; "> <span style="font-size:24px;color:#003188;"><strong>We are curious to here from you</strong></span></p>
																													</div>
																												</div>
																												
																											</div>
																										</div>
																									</td>
																								</tr>
																							</table>
																							<table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
																								<tr>
																									<td style="padding-bottom:10px;padding-left:40px;padding-right:40px;padding-top:10px;">
																										<div style="font-family: sans-serif">
																											<div style="font-size: 12px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; line-height: 1.5;">
																												<div id="headerRight" style="font-size:16px;color:#6d89bc; ">
																													<div contenteditable="true">
																														<p style="margin: 0;  mso-line-height-alt: 24px;">
																															<span style="font-size:16px;"> <span style="color:#6d89bc;">Please feel free to write with your comments or suggestions to hello@optymoney.com </span></span>
																														</p>
																														<p style="margin: 0;  mso-line-height-alt: 24px;">
																															<span style="font-size:16px;"> <span style="color:#6d89bc;">We are always eager to assist you. </span></span>
																														</p>
																													</div>
																												</div>
																											</div>
																										</div>
																									</td>
																								</tr>
																							</table>
																						</th>
																					</tr>
																				</tbody>
																			</table>
																			<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="75%">
																				<tbody>
																					<tr>
																						<th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 1px solid #E5EAF3; padding-top: 0px; padding-bottom: 0px;" width="100%">
																							<table border="0" cellpadding="10" cellspacing="0" class="social_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
																								<tr>
																									<td>
																										<table align="center" border="0" cellpadding="0" cellspacing="0" class="social-table" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="184px">
																											<tr>
																												<td style="padding:0 7px 0 7px;">
																													<a href="https://web.whatsapp.com/send?phone=+917411011280" target="_blank" class="new nav-link theme-layout nav-link-bg whatsapp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
																												</td>
																												<td style="padding:0 7px 0 7px;">
																													<a href="https://bit.ly/optytwitter" target="_blank" class="new nav-link theme-layout nav-link-bg twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
																												</td>
																												<td style="padding:0 7px 0 7px;">
																													<a href="https://bit.ly/optyfb" target="_blank" class="new nav-link theme-layout nav-link-bg facebook"><i class="fa fa-facebook-f" aria-hidden="true"></i></a>
																												</td>
																												<td style="padding:0 7px 0 7px;">
																													<a href="https://bit.ly/optyinsta" target="_blank" class="new nav-link theme-layout nav-link-bg instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
																												</td>
																												<td style="padding:0 7px 0 7px;">
																													<a href="https://bit.ly/optylinkedin" target="_blank" class="new nav-link theme-layout nav-link-bg linkedin"><i class="fa fa-linkedin-in" aria-hidden="true"></i></a>
																												</td>
																											</tr>
																										</table>
																									</td>
																								</tr>
																							</table>
																						</th>
																					</tr>
																				</tbody>
																			</table>
																			<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="75%">
																				<tbody>
																					<tr>
																						<th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 1px solid #E5EAF3; padding-top: 0px; padding-bottom: 0px;" width="100%">
																							<table border="0" cellpadding="10" cellspacing="0" class="social_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
																								<tr>
																									<td style="text-align:center;">
																										<table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
																											<!--<![endif]-->
																											<tr>
																												<td style="font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:15px;color:#9d9d9d;vertical-align:middle;letter-spacing:undefined;text-align:center;">
																													<span>Donot reply to this mail as it is an auto generated email. </span>
																												</td>
																											</tr>
																											<tr>
																												<td style="font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:15px;color:#9d9d9d;vertical-align:middle;letter-spacing:undefined;text-align:center;">
																													<span>Disclimer</span>
																												</td>
																											</tr>
																											<tr>
																												<td style="font-family:Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:10px;color:#9d9d9d;vertical-align:middle;letter-spacing:undefined;text-align:center;">
																													<span>This message (including any attachments) may contain confidential, proprietary,
																													privileged and/or private information. The information is intended <br>
																													to be for the use of the individual or entity designated above.
																													If you are not the intended recipient of this message, please notify <br>
																													the sender immediately, and delete the message and any attachments. Any disclosure,
																													reproduction, distribution or other use of this message or any attachments by <br>
																													an individual or an entity other than the intended recipient is prohibited.</span>
																												</td>
																											</tr>
																										</table>
																									</td>
																								</tr>
																							</table>
																						</th>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table><!-- End -->
														</body>
													</html>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Email Format Name</label>
														<input type="text" class="form-control" name="emailformat_name" id="emailformat_name" value="" placeholder="Enter Email Format Name" title="Please enter Email Format Name" alt="Email Format Name">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Email Format Type</label>
														<input list="emailformat_typeList" type="text" class="form-control" name="emailformat_type" value="" id="emailformat_type"  placeholder="Please enter post category">
														<datalist id="emailformat_typeList"></datalist>
														<input type="hidden" class="form-control" name="emailformat_id" id="emailformat_id" value="">
													</div>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Email Format Status</label>
														<select  class="form-control" name="emailformat_status" id="emailformat_status">
															<option value="pending">Pending</option>
															<option value="approved">Approved</option>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="mandatory mandatory_label">Email Template</label>
														<select  class="form-control" name="emailformat_template_choose" id="emailformat_template_choose">
															<option value="yes">Yes</option>
															<option value="no">No</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row mb-5">
												<div class="col-md-12">
													<small class="text-danger">Add "Dear <--NAME-->" before the content inside the text area</small>
													<div id="email_content" contenteditable="true">
														<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at vulputate urna, sed dignissim arcu. Aliquam at ligula imperdiet, faucibus ante a, interdum enim. Sed in mauris a lectus lobortis condimentum. Sed in nunc magna. Quisque massa urna, cursus vitae commodo eget, rhoncus nec erat. Sed sapien turpis, elementum sit amet elit vitae, elementum gravida eros. In ornare tempus nibh ut lobortis. Nam venenatis justo ex, vitae vulputate neque laoreet a.</p>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveEmailFormat" id="saveEmailFormat">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="emailFormatView_modal" tabindex="-1" role="dialog" aria-labelledby="emailFormatView_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="emailFormatViewTitle"></h5>
										<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body" id="emailFormatViewContent">
										
									</div>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-emailformat">
							<thead>
								<tr>
									<th>Name</th>
									<th>Type</th>
									<th>Status</th>
									<th>Created Date</th>
									<th style="width: 10%">Created By</th>
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
<!-- <script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/editor/ckeditor/styles.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script> -->
<script src="https://cdn.ckeditor.com/4.21.0/standard-all/ckeditor.js"></script>	
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/cms/emailFormats.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection