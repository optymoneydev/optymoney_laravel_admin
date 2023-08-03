"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            $('#export-button-help').DataTable( {
                dom: 'Bfrtip',
                order: false,
                buttons: [
                    {
                        text: 'New Form',
                        action: function ( e, dt, node, config ) {
                            $('#helpForm_modal').modal("show");
                        }
                    },
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            } );

            "use strict";
            helpData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#addhelp').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append('help_answer', $('#help_answer').html().replace(/'/g, "&#39"));
                formData.append('help_keywords', $('#help_keywords').html().replace(/'/g, "&#39"));
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'saveHelp',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addhelp')[0].reset();
                            $('#helpForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            helpData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $(document).on("click", ".helpEdit", function (e) {
                e.preventDefault();
                var objData = {};
                var custid = "";
                objData["help_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "helpById",
                    type: "POST",
                    data: { "help_id": $(this).attr("data-id") },
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#helpForm_modal').modal('show');
                        $.each(response, function (key, val) {
                            if(key == 'help_answer') {
                                $('#' + key).html(val);    
                            } else {
                                if(key == 'help_keywords') {
                                    $('#' + key).html(val);    
                                } else {
                                    $('#' + key).val(val);
                                }
                            }
                        });
                    }
                 });
            });

            $(document).on("click", ".helpDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["help_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deletehelpById",
                    type: "POST",
                    data: { "help_id": $(this).attr("data-id") },
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        res = response;
                        if (res == 1) {
                            createNoty(res['message'], 'success');
                            helpData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    }
                 });
            });

            $("#help_category").change(function(){
                console.log($(this).val());
                alert("The text has been changed."+$(this).val());
            });

        })(jQuery);

        function helpData() {
            $.ajax({
                url: "getHelp",
                type: "GET",
                success: function(response) {
                    var res = jQuery.parseJSON(response.help);
                    var data = [];
                    $.each(res, function(key, item) {
                        data.push(item);
                        console.log(item);
                    });
                    var help_category = response.helpCategory;
                    var help_sub_category = response.helpSubCategory;
                    if(help_category.length!=0) {
                        $.each(help_category, function( key, value ) {
                            console.log(value);
                        });
                    }
                    if(help_sub_category.length!=0) {
                        $.each(help_category, function( key, value ) {
                            console.log(value);
                        });
                    }
                    $('#export-button-help').DataTable().destroy();
                    oTable = $('#export-button-help').DataTable({
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
                                text: 'New Form',
                                action: function ( e, dt, node, config ) {
                                    $('#helpForm_modal').modal("show");
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
                                 return row.help_category; 
                                } 
                            }, 
                            { data : null, render: function (data, type, row) { return row.help_sub_category; } }, 
                            { data : null, render: function (data, type, row) { return row.help_question; } }, 
                            { data : null, render: function (data, type, row) { return row.help_answer; } }, 
                            { data : null, render: function (data, type, row) { return row.help_created_by; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-warning helpEdit" data-id="' + row.help_id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger helpDelete" data-id="' + row.help_id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-help").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-help_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-help').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);