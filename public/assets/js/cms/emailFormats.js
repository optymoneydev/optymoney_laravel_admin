"use strict";
setTimeout(function(){
        var oTable;
        var defaultContentData = $('#emailFormatContent_sample').html();
        (function($) {

            "use strict";
            emailFormatsData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#addEmailFormat').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                console.log(formData);
                let result = $('#emailFormatContent_sample').html().replace(/'/g, "&#39");
        
                formData.append('emailformat_content', result);
                $.ajax({
                    type: "POST",
                    url: 'saveEmailFormats',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addemailFormat')[0].reset();
                            $('#newForm').modal('hide');
                            createNoty(res['message'], 'success');
                            emailFormatsData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });
            
            $("#new_emailFormat").click(function(){
                $('#emailFormatContent_sample').html(defaultContentData);
                $('#addemailFormat')[0].reset();
            });
            // $('#new_emailFormat')
            $(document).on("click", ".emailFormatEdit", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "emailFormatById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // var res = JSON.parse(response);
                        $('#newForm').modal('show');
                        $.each(response, function (key, val) {
                            if(key=="emailformat_content") {
                                $('#emailFormatContent_sample').empty();
                                $('#emailFormatContent_sample').html(val);
                            } else {
                                if(key=="emailformat_id") {
                                    $('#emailformat_id').val(val);
                                } else {
                                    $('#' + key).val(val);
                                }
                            }
                        });
                    }
                 });
            });
            $(document).on("click", ".emailFormatDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deleteEmailFormatById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addblog')[0].reset();
                            $('#blogForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            blogData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    }
                 });
            });
            $(document).on("click", ".emailFormatView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "emailFormatById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#emailFormatView_modal').modal('show');
                        $('#emailFormatViewData').empty();
                        $('#emailFormatViewContent').html(response.emailformat_content);
                        $('#emailFormatViewTitle').html(response.emailformat_name);
                    }
                });
            });	

        })(jQuery);

        function emailFormatsData() {
            $.ajax({
                url: "getEmailFormat",
                type: "GET",
                success: function(response) {
                    var res = jQuery.parseJSON(response.emailsformats);
                    var data = [];
                    $.each(res, function(key, item) {
                        data.push(item);
                    });
                    var emailFormatsType = response.emailFormatsType;
                    if(emailFormatsType.length!=0) {
                        $.each(emailFormatsType, function( key, value ) {
                            $('#emailformat_typeList').append('<option value="'+key+'">')
                        });
                    }
                    $('#export-button-emailformat').DataTable().destroy();
                    oTable = $('#export-button-emailformat').DataTable({
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
                                text: 'New Email Format',
                                action: function ( e, dt, node, config ) {
                                    $('#newForm').modal("show");
                                    $('#content').html(defaultContentData);
                                    $('#addemailFormat')[0].reset();
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
                                 return row.emailformat_name; 
                                } 
                            }, 
                            { data : null, render: function (data, type, row) { return row.emailformat_type; } }, 
                            { data : null, render: function (data, type, row) { return row.emailformat_status; } }, 
                            { data : null, render: function (data, type, row) { return row.emailformat_created_date; } }, 
                            { data : null, render: function (data, type, row) { return row.emailformat_created_by; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-primary emailFormatView" data-id="' + row.emailformat_id + '"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-warning emailFormatEdit" data-id="' + row.emailformat_id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger emailFormatDelete" data-id="' + row.emailformat_id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-emailformat").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-emailformat_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-emailformat').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);