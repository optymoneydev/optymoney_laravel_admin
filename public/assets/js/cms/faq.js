"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            $('#export-button-faq').DataTable( {
                dom: 'Bfrtip',
                order: false,
                buttons: [
                    {
                        text: 'New Form',
                        action: function ( e, dt, node, config ) {
                            $('#newForm').modal("show");
                        }
                    },
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            } );

            "use strict";
            faqData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#addfaq').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append('faq_answer', $('#faq_answer').html().replace(/'/g, "&#39"));
                formData.append('faq_keywords', $('#faq_keywords').html().replace(/'/g, "&#39"));
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'saveFaq',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addfaq')[0].reset();
                            $('#newForm').modal('hide');
                            createNoty(res['message'], 'success');
                            faqData();
                        } else {
                            createNoty(response['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $(document).on("click", ".faqEdit", function (e) {
                e.preventDefault();
                var objData = {};
                var custid = "";
                objData["faq_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "faqById",
                    type: "POST",
                    data: { "faq_id": $(this).attr("data-id") },
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#newForm').modal('show');
                        $.each(response, function (key, val) {
                            if(key == 'faq_answer') {
                                $('#' + key).html(val);    
                            } else {
                                if(key == 'faq_keywords') {
                                    $('#' + key).html(val);    
                                } else {
                                    $('#' + key).val(val);
                                }
                            }
                        });
                    }
                 });
            });

            $(document).on("click", ".faqDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["faq_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deletefaqById",
                    type: "POST",
                    data: { "faq_id": $(this).attr("data-id") },
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        res = response;
                        if (res == 1) {
                            createNoty(res['message'], 'success');
                            faqData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    }
                 });
            });

        })(jQuery);

        function faqData() {
            $.ajax({
                url: "getFaqs",
                type: "GET",
                success: function(response) {
                    var res = JSON.parse(response);
                    var data = [];
                    $.each(res, function(key, item) {
                        data.push(item);
                    });
                    $('#export-button-faq').DataTable().destroy();
                    oTable = $('#export-button-faq').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": false,
                        "buttons": [
                            {
                                text: 'New FAQ',
                                action: function ( e, dt, node, config ) {
                                    $('#newForm').modal("show");
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        // "columnDefs": [
                        //     { width: 300, targets: 1 }
                        // ],
                        "fixedColumns": true,
                        "columns" : [
                            { data : null, "width": "10%", render: function (data, type, row) { return row.faq_category; } }, 
                            { data : null, render: function ( data, type, row ) { 
                                    return '<div class="text-wrap">'+row.faq_question+'</div>';
                                }
                            }, 
                            // { data : null, render: function (data, type, row) { return row.faq_answer; } }, 
                            { data : null, render: function (data, type, row) { return row.faq_keywords; } }, 
                            { data : null, "width": "10%", render: function (data, type, row) { return row.faq_created_by; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-warning faqEdit" data-id="' + row.faq_id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger faqDelete" data-id="' + row.faq_id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-faq").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-faq_wrapper .col-md-6:eq(0)');
                    // oTable = $('#export-button-faq').DataTable();
                }
            });
        }
    }
,350);