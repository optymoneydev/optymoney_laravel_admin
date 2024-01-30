"use strict";
setTimeout(function(){
    var oTable;
    (function($) {
        "use strict";
        $('#logSummaryTable').hide();
        logrecords();
        $(document).on('click', '#searchFilter', function (e) {
            e.preventDefault();
            var arr = $('#daterange').val().split('-');
            console.log(arr);
            $.ajax({
                type: 'POST',
                "data": { "startDate": arr[0].trim(), "endDate" : arr[1].trim() },
                url: "../../../crm/showLoginLogByDates",
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('.loader-wrapper').css("display", 'none');
                    $('#logdetailed').DataTable().clear().draw();
                    $('#logdetailed').DataTable().destroy();
                    if(data.length>0) {
                        oTable = $('#logdetailed').DataTable({
                            "paging": true,
                            "searching": true,
                            "info": true,
                            "responsive": true, 
                            "lengthChange": false, 
                            "autoWidth": true,
                            "scrollX": true,
                            dom: 'Bfrtip',
                            order:[[0,"desc"]],
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            "data" : data,
                            "columns" : [
                                { data : null, render: function (data, type, row) { 
                                    return formatDate(new Date(row.date));
                                } },
                                { data : null, render: function (data, type, row) { 
                                    if(row.content.user) {
                                        return capitalizeFirstLetter(row.content.user.cust_name);
                                    } else {
                                        return "";
                                    }
                                } },
                                { data : null, render: function (data, type, row) { 
                                    if(row.content.ip) {
                                        return row.content.ip;
                                    } else {
                                        return "";
                                    }
                                } },  
                                { data : null, render: function (data, type, row) { 
                                    if(row.content.device) {
                                        return capitalizeFirstLetter(row.content.device)+", "+capitalizeFirstLetter(row.content.browser); 
                                    } else {
                                        return "";
                                    }
                                } }, 
                            ],
                            "fnInitComplete": function() { $("#logdetailed").css("width","100%"); }
                        }).buttons().container().appendTo('#logdetailed_wrapper .col-md-6:eq(0)');
                        oTable = $('#logdetailed').DataTable();
                    } else {
                        $('.loader-wrapper').css("display", 'none');
                        oTable = $('#logdetailed').DataTable();
                    }
                },
                error: function() {
                    console.log("wrong...!!!");
                }
            });
        });

        $("#flexSwitchCheckDefault").change(function() {
            if(this.checked) {
                $('#logSummaryTable').show();
                $('#logDetailedTable').hide();
                logsummary();
            } else {
                $('#logSummaryTable').hide();
                $('#logDetailedTable').show();
                logrecords();
            }
        });
    })(jQuery);

    function logrecords() {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../../../crm/showLoginLog",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $(".loader-wrapper").show();
            },
            complete: function(){
                $(".loader-wrapper").hide();
            },
            success: function(data) {
                $('#logdetailed').DataTable().destroy();
                if(data.length>0) {
                    oTable = $('#logdetailed').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        order:[[0,"desc"]],
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { 
                                return formatDate(new Date(row.date));
                            } },
                            { data : null, render: function (data, type, row) { 
                                if(row.content.user) {
                                    return capitalizeFirstLetter(row.content.user.cust_name);
                                } else {
                                    return "";
                                }
                            } },
                            { data : null, render: function (data, type, row) { 
                                if(row.content.ip) {
                                    return row.content.ip;
                                } else {
                                    return "";
                                }
                            } },  
                            { data : null, render: function (data, type, row) { 
                                if(row.content.device) {
                                    return capitalizeFirstLetter(row.content.device)+", "+capitalizeFirstLetter(row.content.browser); 
                                } else {
                                    return "";
                                }
                            } }, 
                        ],
                        "fnInitComplete": function() { $("#logdetailed").css("width","100%"); }
                    }).buttons().container().appendTo('#logdetailed_wrapper .col-md-6:eq(0)');
                    oTable = $('#logdetailed').DataTable();
                } else {
                    oTable = $('#logdetailed').DataTable();
                }
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }

    function logsummary() {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../../../crm/showLoginLogCount",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $(".loader-wrapper").show();
            },
            complete: function(){
                $(".loader-wrapper").hide();
            },
            success: function(data) {
                $('#logSummary').DataTable().destroy();
                if(data.length>0) {
                    oTable = $('#logSummary').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        order:[[0,"desc"]],
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { 
                                if(row.content.user) {
                                    return capitalizeFirstLetter(row.content.user.cust_name);
                                } else {
                                    return "";
                                }
                            } },
                            { data : null, render: function (data, type, row) { 
                                return "client";
                            } },
                            
                            { data : null, render: function (data, type, row) { 
                                return row.count;
                            } },  
                            { data : null, render: function (data, type, row) { 
                                return formatDate(new Date(row.date));
                            } }, 
                        ],
                        "fnInitComplete": function() { $("#logSummary").css("width","100%"); }
                    }).buttons().container().appendTo('#logSummary_wrapper .col-md-6:eq(0)');
                    oTable = $('#logSummary').DataTable();
                } else {
                    oTable = $('#logSummary').DataTable();
                }
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }
},350);