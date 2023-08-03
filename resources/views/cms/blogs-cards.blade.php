@extends('layouts.simple.master')
@section('title', 'Blog')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Blog</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Blog</li>
<li class="breadcrumb-item active">Blog List</li>
@endsection

@section('content')
<div id="noty-holder"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="dt-ext table-responsive">
						<div class="modal fade" id="blogForm_modal" role="dialog" aria-labelledby="blogForm_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<form class="needs-validation" novalidate="" name="addblog" id="addblog" method="POST">
										{{ csrf_field() }}
										<div class="modal-header">						
											<h4 class="modal-title">New Blog</h4>
										</div>
										<div class="modal-body">						 
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Category</label>
															<input list="blogs_categoryList" type="text" class="form-control" name="post_category" value="" id="post_category"  placeholder="Please enter post category">
															<datalist id="blogs_categoryList"></datalist>
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Title</label>
															<input type="text" class="form-control" name="title" id="title" value="" placeholder="Please enter post title" alt="Please enter post title">
															<input type="hidden" class="form-control" name="id" id="id" value="">
															<input type="hidden" class="form-control" name="post_meta_id" id="post_meta_id" value="">
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Author</label>
															<input type="text" class="form-control" name="post_author" id="post_author" value="" placeholder="Please enter author name" title="author name" alt="author name">
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Status</label>
															<select class="form-control" name="status" id="status">
																<option value="">Select</option>
																<option value="Draft">Draft</option>
																<option value="Publish">Publish</option>
															</select>
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Cover Image (1600x640)(jpg/png)</label>
															<input type="file" class="form-control" name="coverimage" id="coverimage" value="" title="Cover Image" alt="Cover Image">
															<br><img src="" id="coverExistingPic" style="width: -webkit-fill-available;">
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Thumbnail (1000x666)(jpg/png)</label>
															<input type="file" class="form-control" name="thumbnailimage" id="thumbnailimage" value="" title="Thumbnail Image" alt="Thumbnail Image">
															<br><img src="" id="thumbnailExistingPic" style="width: -webkit-fill-available;">
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Icon (256x256)(jpg/png)</label>
															<input type="file" class="form-control" name="iconimage" id="iconimage" value="" title="Icon Image" alt="Icon Image">
															<br>
															<img src="" id="iconExistingPic" style="width: -webkit-fill-available;">
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Keywords</label>
															<textarea class="form-control" id="post_keywords" name="post_keywords" rows="4" cols="100%"></textarea>
															
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">alt attribute</label>
															<textarea id="alt_attr" class="form-control" required="required" name="alt_attr" rows="4" cols="100%"></textarea>
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Meta-Keywords</label>
															<textarea id="meta_keywords" class="form-control" required="required" name="meta_keywords" rows="4" cols="100%"></textarea>
														</div>
													</div>
													<div class="col-md-6 mb-2">
														<div class="form-group">
															<label class="mandatory mandatory_label">Meta-description</label>
															<textarea id="meta_description" class="form-control" required="required" name="meta_description" rows="4" cols="100%"></textarea>
														</div>
													</div>
												</div>
											</div>
											<hr class="mb-5">
											<div class="col-md-12">
												<div id="blogContent_sample">
														<h1>Inline Editor <a class="documentation" href="https://ckeditor.com/docs/ckeditor4/latest/guide/dev_inline.html">Documentation</a></h1>

														<p>
														<strong>Inline Editing</strong> allows you to edit any element on the page in-place. Inline editor provides a real <abbr title="What You See is What You Get">WYSIWYG</abbr> experience "out of the box", because unlike in <a href="./classic.html">classic editor</a>, there is no <code>&lt;iframe&gt;</code> element surrounding the editing area. The CSS styles used for editor content are exactly the same as on the target page where this content is rendered!
														</p>

														<h2 class="editor-title">Inline Editing Enabled by Code</h2>

														<p>
														Press the "Start editing" button below. An editor will be created using the
														<code><a href="https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR.html#method-inline">CKEDITOR.inline()</a></code> method with
														<code><a href="https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html#cfg-startupFocus">config.startupFocus</a></code>
														set to <code>true</code>.
														</p>
													
												</div>
											</div>
										</div>
										<div class="modal-footer">						
											<div class="row mt-4">
												<div class="col-md-12 text-center">
													<button class="btn btn-danger btn-sm" style="color:white" type="submit" name="saveBlog" id="saveBlog">Save</button>
													<input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="blogView_modal" tabindex="-1" role="dialog" aria-labelledby="blogView_modal" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="blogViewTitle"></h5>
										<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body" id="blogViewContent">
										
									</div>
								</div>
							</div>
						</div>
						<table class="display" id="export-button-blogs">
							<thead>
								<tr>
									<th>Post Title</th>
									<th>Category</th>
									<th>Status</th>
									<th>Posted Date</th>
									<th>Posted By</th>
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
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/styles.js')}}"></script>
<script src="{{asset('assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script>
<!-- <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script> -->
<!-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> -->

<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/height-equal.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/cms/blogs.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
@endsection