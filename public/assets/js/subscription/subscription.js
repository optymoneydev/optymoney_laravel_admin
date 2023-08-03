"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            "use strict";
            subscriptionData();

        })(jQuery);

        function subscriptionData() {
            $.ajax({
                url: "getSubscription",
                type: "GET",
                success: function(response) {
                    var data = [];
                    $.each(jQuery.parseJSON(response), function(key, item) {
                        data.push(item);
                    });
                    $('#export-button-subscription').DataTable().destroy();
                    oTable = $('#export-button-subscription').DataTable({
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
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.sub_name; } }, 
                            { data : null, render: function (data, type, row) { return row.sub_mobile; } }, 
                            { data : null, render: function (data, type, row) { return row.sub_email; } }, 
                            { data : null, render: function (data, type, row) { return row.sub_date; } }, 
                            { data : null, render: function (data, type, row) { return row.sub_message; } },
                            { data : null, render: function (data, type, row) { return row.sub_status; } }
                        ],
                        "fnInitComplete": function() { $("#export-button-ea").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-ea_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-ea').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);