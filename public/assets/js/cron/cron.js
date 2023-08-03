"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            "use strict";
            
            $(document).on("click", ".navView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $('#priceDateTitle').html("Date: "+$(this).attr("data-id"));
                var table = $('#export-button-updateNavSchemes').DataTable({
                    processing: true,
                    serverSide: true,
                    "ajax":{
                        "url": "getNavSchemes",
                        "type": "POST",
                        "data": objData,
                        "headers": {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    columns: [
                        {data: 'fr_scheme_name', name: 'fr_scheme_name'},
                        {data: 'ISIN', name: 'ISIN'},
                        {data: 'net_asset_value', name: 'net_asset_value'},
                        {data: 'fr_unique_no', name: 'fr_unique_no'},
                    ],
                    "fnInitComplete": function() { $("#export-button-updateNavSchemes").css("width","100%"); }
                }).buttons().container().appendTo('#export-button-updateNavSchemes_wrapper .col-md-6:eq(0)');
            });	

        })(jQuery);

    }
,350);