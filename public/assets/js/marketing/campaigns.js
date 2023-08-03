"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            $('#export-button-campaign').DataTable( {
                dom: 'Bfrtip',
                order: false,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            } );

            "use strict";
            campaignData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);

        })(jQuery);

        function campaignData() {
            $.ajax({
                url: "getCampaigns",
                type: "GET",
                success: function(response) {
                    // var res = jQuery.parseJSON(response);
                    var data = [];
                    $.each(response, function(key, item) {
                        data.push(item);
                    });
                    $('#export-button-campaign').DataTable().destroy();
                    oTable = $('#export-button-campaign').DataTable({
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
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.cust_name; } }, 
                            { data : null, render: function (data, type, row) { return row.login_id; } }, 
                            { data : null, render: function (data, type, row) { return row.contact_no; } }, 
                            { data : null, render: function (data, type, row) { return row.par_myphoto; } }
                        ],
                        "fnInitComplete": function() { $("#export-button-campaign").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-campaign_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-campaign').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);