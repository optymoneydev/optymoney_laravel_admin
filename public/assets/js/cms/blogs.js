"use strict";
setTimeout(function(){
        var oTable;
        var defaultContentData = $('#blogContent_sample').html();
        var blogContent_sample = document.getElementById('blogContent_sample');
        blogContent_sample.setAttribute('contenteditable', true);

        CKEDITOR.inline('blogContent_sample', {
            // Allow some non-standard markup that we used in the introduction.
            extraAllowedContent: 'a(documentation);abbr[title];code',
            removePlugins: 'stylescombo',
            // extraPlugins: 'sourcedialog',
            removeButtons: 'PasteFromWord',
            // Show toolbar on startup (optional).
            startupFocus: true
        });
        (function($) {

            "use strict";
            blogsData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#addblog').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                console.log(formData);
                let result = $('#blogContent_sample').html().replace(/'/g, "&#39");
        
                formData.append('post_content', result);
                $.ajax({
                    type: "POST",
                    url: 'saveBlog',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addblog')[0].reset();
                            $('#blogForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            blogData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });
            
            $("#new_blog").click(function(){
                $('#blogContent_sample').html(defaultContentData);
                $('#addblog')[0].reset();
            });
            // $('#new_blog')
            $(document).on("click", ".blogEdit", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "blogById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // var res = JSON.parse(response);
                        $('#blogForm_modal').modal('show');

                        $.each(response, function (key, val) {
                            if(key=="post_content") {
                                $('#blogContent_sample').empty();
                                $('#blogContent_sample').html(val);
                            } else {
                                if(key=="id") {
                                    $('#id').val(val);
                                } else {
                                    if(key=="coverimage") {
                                        $('#coverExistingPic').attr("src","https://admin.optymoney.com/uploads/blogs/"+$('#id').val()+"/"+val);
                                    } else {
                                        if(key == "thumbnailimage") {
                                            $('#thumbnailExistingPic').attr("src","https://admin.optymoney.com/uploads/blogs/"+$('#id').val()+"/"+val);
                                        } else {
                                            if(key == "iconimage") {
                                                $('#iconExistingPic').attr("src","https://admin.optymoney.com/uploads/blogs/"+$('#id').val()+"/"+val);
                                            } else {
                                                if(key=="status") {
                                                    $('#status option[value="'+val+'"]').attr("selected", "selected");
                                                } else {
                                                    $('#' + key).val(val);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            });
            $(document).on("click", ".blogDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deleteBlogById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        res = response;
                        if (res == true) {
                            alert("Deleted Successfully");
                            // refreshTable($(this).attr("data-dbtable"));
                        } else {
                            alert("Deletion Failed. Please try again.");
                        }
                    }
                 });
            });
            $(document).on("click", ".blogView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "blogById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#blogView_modal').modal('show');
                        $('#blogViewData').empty();
                        $('#blogViewContent').html(response.post_content);
                        $('#blogViewTitle').html(response.title);
                    }
                });
            });	

        })(jQuery);

        function blogsData() {
            $.ajax({
                url: "getBlogs",
                type: "GET",
                success: function(response) {
                    var res = jQuery.parseJSON(response.blogs);
                    var data = [];
                    $.each(res, function(key, item) {
                        data.push(item);
                    });
                    var blogs_category = response.blogsCategory;
                    if(blogs_category.length!=0) {
                        $.each(blogs_category, function( key, value ) {
                            $('#blogs_categoryList').append('<option value="'+key+'">')
                        });
                    }
                    $('#export-button-blogs').DataTable().destroy();
                    oTable = $('#export-button-blogs').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        order: false,
                        buttons: [
                            {
                                text: 'New Blog',
                                action: function ( e, dt, node, config ) {
                                    $('#blogForm_modal').modal("show");
                                    $('#content').html(defaultContentData);
                                    $('#addblog')[0].reset();
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) {
                                 return row.title; 
                                } 
                            }, 
                            { data : null, render: function (data, type, row) { return row.post_category; } }, 
                            { data : null, render: function (data, type, row) { return row.status; } }, 
                            { data : null, render: function (data, type, row) { return row.post_date; } }, 
                            { data : null, render: function (data, type, row) { return row.post_created_by; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-primary blogView" data-id="' + row.id + '"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-warning blogEdit" data-id="' + row.id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger blogDelete" data-id="' + row.id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-blogs").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-blogs_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-blogs').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);