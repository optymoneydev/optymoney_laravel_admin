"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            "use strict";
            smsTemplatesData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#addsmsTemplate').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: "POST",
                    url: 'saveSMSTemplate',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addsmsTemplate')[0].reset();
                            $('#export-button-smsTemplate').modal('hide');
                            createNoty(res['message'], 'success');
                            smsTemplatesData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });
            
            $("#new_smsTemplate").click(function(){
                $('#addsmsTemplate')[0].reset();
            });

            $(document).on("click", ".smsTemplateEdit", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "smsTemplateById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // var res = JSON.parse(response);
                        $('#smsTemplateForm_modal').modal('show');
                        $.each(response, function (key, val) {
                            $('#' + key).val(val);
                        });
                    }
                 });
            });
            $(document).on("click", ".smsTemplateDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deletesmsTemplateById",
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
                            $('#smsTemplateForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            smsTemplatesData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    }
                 });
            });
            $(document).on("click", ".smsTemplateView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "smsTemplateById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#smsTemplateView_modal').modal('show');
                        $('#smsTemplateViewData').empty();
                        $('#smsTemplateViewContent').html(response.sms_content);
                        $('#smsTemplateViewTitle').html(response.sms_name);
                    }
                });
            });	

        })(jQuery);

        function smsTemplatesData() {
            $.ajax({
                url: "getSMSTemplates",
                type: "GET",
                success: function(response) {
                    var res = jQuery.parseJSON(response.sms);
                    var data = [];
                    $.each(res, function(key, item) {
                        data.push(item);
                    });
                    var smsType = response.smsType;
                    if(smsType.length!=0) {
                        $.each(smsType, function( key, value ) {
                            $('#sms_typeList').append('<option value="'+key+'">')
                        });
                    }
                    $('#export-button-smsTemplate').DataTable().destroy();
                    oTable = $('#export-button-smsTemplate').DataTable({
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
                                text: 'New SMS Template',
                                action: function ( e, dt, node, config ) {
                                    $('#smsTemplateForm_modal').modal("show");
                                    $('#addsmsTemplate')[0].reset();
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.sms_name; } }, 
                            { data : null, render: function (data, type, row) { return row.sms_type; } }, 
                            { data : null, render: function (data, type, row) { return row.sms_status; } }, 
                            { data : null, render: function (data, type, row) { return row.sms_created_date; } }, 
                            { data : null, render: function (data, type, row) { return row.sms_created_by; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-primary smsTemplateView" data-id="' + row.sms_id + '"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-warning smsTemplateEdit" data-id="' + row.sms_id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger smsTemplateDelete" data-id="' + row.sms_id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-smsTemplate").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-smsTemplate_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-smsTemplate').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);