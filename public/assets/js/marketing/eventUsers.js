"use strict";
setTimeout(function(){
        var oTable;
        var defaultContentData = $('#eventContent_sample').html();
        (function($) {

            "use strict";
            eventUsersData();

        })(jQuery);

        function eventUsersData() {
            $.ajax({
                url: "getEventUsers",
                type: "GET",
                success: function(response) {
                    $('#export-button-eventUsers').DataTable().destroy();
                    oTable = $('#export-button-eventUsers').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        order: [[3, 'desc']],
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.cust_name; } }, 
                            { data : null, render: function (data, type, row) { return row.email; } }, 
                            { data : null, render: function (data, type, row) { return row.contact_no; } }, 
                            { data : null, render: function (data, type, row) { return row.event_p_code; } }, 
                            { data : null, render: function (data, type, row) { return row.user_org; } },
                            { data : null, render: function (data, type, row) { return row.event_timestamp; }
                        }],
                        "fnInitComplete": function() { $("#export-button-eventUsers").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-eventUsers_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-eventUsers').DataTable();
                }
            });
        }
    }
,350);