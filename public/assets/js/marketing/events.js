"use strict";
setTimeout(function(){
        var oTable;
        var defaultContentData = $('#eventContent_sample').html();
        (function($) {

            "use strict";
            eventsData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#event_date').datepicker({
                language: 'en',
                parentEl: '#eventForm_modal',
                dateFormat: 'yyyy-mm-dd',
                minDate: todayDate // Now can select only dates, which goes after today
            });

            $('#addEvent').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                let result = $('#eventContent_sample').html().replace(/'/g, "&#39");
        
                formData.append('bm_content', result);
                $.ajax({
                    type: "POST",
                    url: 'saveEvent',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addEvent')[0].reset();
                            $('#eventForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            eventsData();
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
            $(document).on("click", ".eventEdit", function (e) {
                e.preventDefault();
                var objData = {};
                objData["event_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "eventById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // var res = JSON.parse(response);
                        $('#eventForm_modal').modal('show');
                        $.each(response, function (key, val) {
                            if(key=="bm_content") {
                                $('#eventContent_sample').empty();
                                $('#eventContent_sample').html(val);
                            } else {
                                if(key=="event_id") {
                                    $('#event_id').val(val);
                                } else {
                                    if(key=="event_code") {
                                        $('#event_code').val(val);
                                    } else {
                                        $('#' + key).val(val);
                                    }
                                }
                            }
                        });
                    }
                 });
            });
            $(document).on("click", ".eventDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["event_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deleteEventById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addEvent')[0].reset();
                            $('#eventForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            eventsData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    }
                 });
            });
            $(document).on("click", ".eventView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["event_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "eventById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log(response);
                        $('#eventView_modal').modal('show');
                        $('#eventViewData').empty();
                        $('#eventViewContent').html(response.event_content);
                        $('#eventViewTitle').html(response.event_name);
                    }
                });
            });	

        })(jQuery);

        function eventsData() {
            $.ajax({
                url: "getEvents",
                type: "GET",
                success: function(response) {
                    var data = [];
                    $.each(jQuery.parseJSON(response), function(key, item) {
                        data.push(item);
                    });
                    $('#export-button-events').DataTable().destroy();
                    oTable = $('#export-button-events').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        order: [[5, 'desc']],
                        buttons: [
                            {
                                text: 'New Event',
                                action: function ( e, dt, node, config ) {
                                    $('#eventForm_modal').modal("show");
                                    $('#content').html(defaultContentData);
                                    $('#addEvent')[0].reset();
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.event_name; } }, 
                            { data : null, render: function (data, type, row) { return row.event_url; } }, 
                            { data : null, render: function (data, type, row) { return row.event_date; } }, 
                            { data : null, render: function (data, type, row) { return row.event_status; } }, 
                            { data : null, render: function (data, type, row) { return row.event_created_by; } }, 
                            { data : null, render: function (data, type, row) { return row.createdDate; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-primary eventView" data-id="' + row.event_id + '"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-warning eventEdit" data-id="' + row.event_id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger eventDelete" data-id="' + row.event_id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-events").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-events_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-events').DataTable();
                }
            });
        }
    }
,350);