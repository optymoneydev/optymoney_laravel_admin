"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            "use strict";
            couponsData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#addCoupon').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: "POST",
                    url: 'saveCoupon',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addCoupon')[0].reset();
                            $('#couponForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            couponsData();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });
            
            $(document).on("click", ".couponEdit", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "couponById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#couponForm_modal').modal('show');
                        $.each(response, function (key, val) {
                            $('#' + key).val(val);
                        });
                    }
                 });
            });
            $(document).on("click", ".couponDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["cou_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deleteCouponById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        res = response;
                        if (res == true) {
                            alert("Deleted Successfully");
                            // refreshTable($(this).attr("data-dbtable"));
                        } else {
                            alert("Deletion Failed. Please try again.");
                        }
                    }
                 });
            });
            $(document).on("click", ".blogView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "blogById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#blogView_modal').modal('show');
                        $('#blogViewData').empty();
                        $('#blogViewContent').html(response.post_content);
                        $('#blogViewTitle').html(response.title);
                    }
                });
            });	

        })(jQuery);

        function couponsData() {
            $.ajax({
                url: "getCoupons",
                type: "GET",
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    var data = [];
                    $.each(res, function(key, item) {
                        data.push(item);
                    });
                    $('#export-button-coupon').DataTable().destroy();
                    oTable = $('#export-button-coupon').DataTable({
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
                                text: 'New Coupon',
                                action: function ( e, dt, node, config ) {
                                    $('#couponForm_modal').modal("show");
                                    $('#addCoupon')[0].reset();
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : data,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.cou_name; } }, 
                            { data : null, render: function (data, type, row) { return row.cou_quantity; } }, 
                            { data : null, render: function (data, type, row) { return row.cou_partner_cmpny; } }, 
                            { data : null, render: function (data, type, row) { return row.cou_validity; } }, 
                            { data : null, render: function (data, type, row) { return row.cou_code; } }, 
                            { data : null, render: function (data, type, row) { return row.cou_created_date; } }, 
                            { data : null, render: function (data, type, row) { return row.cou_created_by; } },
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-primary couponView" data-id="' + row.cou_id + '"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-warning couponEdit" data-id="' + row.cou_id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger couponDelete" data-id="' + row.cou_id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-coupon").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-coupon_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-coupon').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);