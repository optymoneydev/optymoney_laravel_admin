"use strict";
setTimeout(function(){
    var oTable;
    (function($) {
        "use strict";
        $(document).ajaxSend(function() {
            $(".loader-wrapper").show();
        });
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
                    $('#export-button').DataTable().destroy();
                    if(data.length>0) {
                        oTable = $('#export-button').DataTable({
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
                            "fnInitComplete": function() { $("#export-button").css("width","100%"); }
                        }).buttons().container().appendTo('#export-button_wrapper .col-md-6:eq(0)');
                        oTable = $('#export-button').DataTable();
                    } else {
                        oTable = $('#export-button').DataTable();
                    }
                },
                error: function() {
                    console.log("wrong...!!!");
                }
            });
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
            success: function(data) {
                $('#export-button').DataTable().destroy();
                if(data.length>0) {
                    oTable = $('#export-button').DataTable({
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
                        "fnInitComplete": function() { $("#export-button").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button').DataTable();
                } else {
                    oTable = $('#export-button').DataTable();
                }
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }
},350);