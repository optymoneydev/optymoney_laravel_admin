"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            "use strict";
            eaData();

        })(jQuery);

        function eaData() {
            $.ajax({
                url: "getExpertAssistance",
                type: "GET",
                success: function(response) {
                    var data = [];
                    $.each(jQuery.parseJSON(response), function(key, item) {
                        data.push(item);
                    });
                    $('#export-button-ea').DataTable().destroy();
                    oTable = $('#export-button-ea').DataTable({
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
                            { data : null, render: function (data, type, row) { return row.ea_name; } }, 
                            { data : null, render: function (data, type, row) { return row.ea_mobile; } }, 
                            { data : null, render: function (data, type, row) { return row.ea_email; } }, 
                            { data : null, render: function (data, type, row) { return row.ea_date; } }, 
                            { data : null, render: function (data, type, row) { return row.ea_expected; } }
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