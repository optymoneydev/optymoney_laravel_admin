@extends('layouts.simple.master')
@section('title', 'Events')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Events</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Events</li>
<li class="breadcrumb-item active">Event List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="eventForm_modal" tabindex="-1" role="dialog" aria-labelledby="eventForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addEvent" id="addEvent" method="POST">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New Event</h4>
										</div>
										<div class="modal-body">
											<div class="card mb-0">
												<div class="card-body">
													<div class="row mb-3">
														<div class="col-md-6">
															<div class="form-group">
																<label class="mandatory mandatory_label">Event Name</label>
																<input type="text" class="form-control" name="event_name" value="" id="event_name"  placeholder="Enter Event name">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="mandatory mandatory_label">Event Code</label>
																<input type="text" class="form-control" name="event_code" id="event_code" value="" placeholder="Enter Event code" title="Please enter event code" alt="Please enter post Title">
																<input type="hidden" class="form-control" name="event_id" id="event_id" value="">
															</div>
														</div>
													</div>
													<div class="row mb-3">
														<div class="col-md-6">
															<div class="form-group">
																<label class="mandatory mandatory_label">Event Date</label>
																<input type="date" class="form-control" name="event_date" id="event_date" value="" placeholder="Enter event date" title="Please enter event date" alt="Event date">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="mandatory mandatory_label">Status </label>
																<select  class="form-control" name="event_status" id="event_status">
																	<option value="">Select</option>
																	<option value="draft">Draft</option>
																	<option value="publish">Publish</option>
																</select>
															</div>
														</div>
													</div>
													<div class="row mb-3">
														<div class="col-md-6">
															<div class="form-group">
																<label class="mandatory mandatory_label">Meta-Keywords </label>
																<textarea id="event_meta_keywords" class="form-control" required="required" name="event_meta_keywords" rows="4" cols="100%"> </textarea>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="mandatory mandatory_label">Meta-description </label>
																<textarea class="form-control" id="event_meta_description"  required="required" name="event_meta_description" rows="4" cols="100%"></textarea>
															</div>
														</div>
													</div>
													<div class="row mb-3">
														<div class="col-md-12">
															<div class="form-group">
																<label class="mandatory mandatory_label">Mail Subject </label>
																<input type="text" class="form-control" name="event_subject" id="event_subject" value="" placeholder="Enter Subject" title="Please enter event mail subject" alt="Event Mail Subject">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="card">
												<div class="card-body" id="eventContent_sample">
													<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="" width="100%">
														<tbody>
															<tr>
																<td>
																	<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="75%">
																		<tbody>
																			<tr>
																				<th class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px;" width="100%">
																					<table border="0" cellpadding="0" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
																						<tr>
																							<td style="padding-bottom:10px;padding-top:20px;">
																								<div style="font-family: sans-serif">
																									<div style="font-size: 12px; color: #555555; line-height: 1.2; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;">
																										<div id="header">
																											<div id="headerRight" style="font-size:16px;color:#6d89bc; ">
																												<div contenteditable="true">
																													<p>Greetings from Optymoney, the one stop platform for all your personal finance needs </p>
																													<p>Thanks for being a "OPTYMONEY Digest" newsletter subscriber!</p>
																													<p>It would give you a wrap-up of the weekly update on the market and a glance of the tax and other related updates impacting your personal finances. Thank you for taking our quick survey!</p>
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
																							<td style="padding-bottom:10px;padding-top:20px;">
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
																							<td style="padding-bottom:10px;padding-top:10px;">
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
																</td>
															</tr>
														</tbody>
													</table><!-- End -->
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveEvent" id="saveEvent">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="eventView_modal" tabindex="-1" role="dialog" aria-labelledby="eventView_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="eventViewTitle"></h5>
										<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body" id="eventViewContent">
										
									</div>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-events">
							<thead>
								<tr>
									<th>Name</th>
									<th>Link</th>
									<th>Event Date</th>
									<th>Status</th>
									<th>Created By</th>
									<th>Created Date</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Base64/1.1.0/base64.min.js"></script>
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/styles.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/marketing/events.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection