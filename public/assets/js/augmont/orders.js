var parsedJson;
$(document).ready(function() {
    $('#transactionFilter').hide();
    $('#transactionsTable').hide();
    $('#summaryTable').hide();
    
    // $(document).on('click', '.openInvoice', function () {
    //     var inv_id = $(this).attr("data-id");
    //     var inv_no = $(this).attr("data-invoice");
    //     $.ajax({
    //         type: 'GET',
    //         data: { "invoice": inv_id },
    //         url: "../augmont/buyInvoice",
    //         async: false,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    //             'Content-Type': 'application/pdf'
    //         },
    //         success: function(data) {
    //             // $('#invoiceViewTitle').text("Invoice No. : "+inv_no);
    //             // parsedJson = $.parseJSON(data);
    //             // $('#inv_number').text(parsedJson.result.data.invoiceNumber);
    //             // $('#inv_date').text(parsedJson.result.data.invoiceDate);
    //             // $('#custname').text(parsedJson.result.data.userInfo.name);
    //             // $('#address').text(parsedJson.result.data.userInfo.address+" "+parsedJson.result.data.userInfo.city+" "+parsedJson.result.data.userInfo.state);
    //             // $('#contact').text(parsedJson.result.data.userInfo.mobileNumber);
    //             // $('#email').text(parsedJson.result.data.userInfo.email);
    //             // $('#augid').text(parsedJson.result.data.userInfo.uniqueId);
    //             // $('#productList').append('<tr><td class="item"><h6 class="p-2 mb-0">Item Description</h6></td><td class="Hours"><h6 class="p-2 mb-0">HSN Code</h6></td><td class="Rate"><h6 class="p-2 mb-0">Gram</h6></td><td class="subtotal"><h6 class="p-2 mb-0">Rate/gm (INR)</h6></td><td class="subtotal"><h6 class="p-2 mb-0">Amount (INR)</h6></td></tr>');
    //             // $('#productList').append('<tr><td><label>'+parsedJson.result.data.metalType+' '+parsedJson.result.data.karat+' '+parsedJson.result.data.purity+'</label></td>'+
    //             // '<td><p class="itemtext">'+parsedJson.result.data.hsnCode+'</p></td><td><p class="itemtext">'+parsedJson.result.data.quantity+'</p></td>'+
    //             // '<td><p class="itemtext">'+parsedJson.result.data.rate+'</p></td>'+
    //             // '<td><p class="itemtext">'+parsedJson.result.data.grossAmount+'</p></td></tr>');
    //             // $('#productList').append('<tr><td><label>Net Total</label></td><td></td><td><p class="itemtext">'+parsedJson.result.data.quantity+'</p></td>'+
    //             // '<td></td><td><p class="itemtext">'+parsedJson.result.data.grossAmount+'</p></td></tr>');
    //             // parsedJson.result.data.taxes.taxSplit.forEach(function(item) {
    //             //     $('#productList').append('<tr><td><label>'+item.type+'</label></td><td></td><td></td>'+
    //             //         '<td><p class="itemtext">'+item.taxPerc+'%</p></td>'+
    //             //         '<td><p class="itemtext">'+item.taxAmount+'</p></td></tr>');
    //             // });
    //             // $('#productList').append('<tr><td><label>Total</label></td><td></td><td><p class="itemtext"></p></td><td></td><td><p class="itemtext">'+parsedJson.result.data.netAmount+'</p></td></tr>');
    //             // console.log(parsedJson);
    //             // $("#invoiceView").modal('show');
    //         },
    //         error: function() {
    //             console.log("wrong...!!!");
    //         }
    //     });
    // });
    $('#saveao').attr("disabled", true);
    $('#ao_cust_id').select2({
        dropdownParent: $('#augmontOrderForm_modal')
    });

    $('#ao_cust_id').on('change', function() {
        var ao_cust_id = this.value;
        $.ajax({
            type: 'GET',
            url: "../augmont/OrdersById/"+parseInt(ao_cust_id),
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#ao_orders').empty();
                var select = document.getElementById("ao_orders");
                var option = document.createElement("option");
                option.text = "Select Order";
                option.value = "";
                select.add(option);
                $.each(data, function(key,val){
                    var option = document.createElement("option");
                    option.text = val.razorpayOrderId + "/" + val.created_at + "/" + val.totalAmount;
                    option.value = val.id;
                    select.add(option);
                });
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    });

    $('#ao_orders').on('change', function() {
        var ao_orders = this.value;
        $.ajax({
            type: 'GET',
            url: "../augmont/OrdersByTransactionId/"+parseInt(ao_orders),
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#roi').text(data.razorpayOrderId);
                $('#rpi').text(data.razorpayId);
                $('#amt_id').text(data.merchantTransactionId);
                $('#metal').text(data.metalType);
                $('#totamt').text(data.totalAmount);
                $('#grams').text(data.quantity);
                $('#lprice').text(data.lockPrice);
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    });

    $("#verifyRPI").click(function(e){
        e.preventDefault();
        var rpi = $('#ao_transaction_id').val();
        $.ajax({
            type: 'GET',
            url: "../getSpecificPayment/"+rpi,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if(data=="captured") {
                    $('#payStat').text("Payment Captured");
                    $("#saveao").removeAttr("disabled");
                } else {
                    $('#payStat').text(data);
                    $('#saveao').attr("disabled", true);
                }
                console.log(data);
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    });

    $(document).on('click', '.getBuyInfo', function (e) {
        e.preventDefault();
        var settings = {
            "url": "/augmont/getBuyInfo/"+$(this).attr("data-mti")+"/"+$(this).attr("data-ui"),
            "method": "GET",
            "timeout": 0,
            "headers": {
            "Accept": "application/json",
            "Authorization": "Bearer "+localStorage.access_token,
            },
        };
        $.ajax(settings).done(function (response) {
            console.log(response);
        }).fail(function(data){
            console.log(data);
        });
    });

    $(document).on('click', '#searchFilter', function (e) {
        e.preventDefault();
        var arr = $('#daterange').val().split('-');
        console.log(arr);
        $.ajax({
            type: 'GET',
            data: '',
            url: "../augmont/currentRates",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                var rates = JSON.parse(data).result.data;
                var settings = {
                    "url": "/augmont/getOrdersFilter",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Accept": "application/json",
                        "Authorization": "Bearer "+localStorage.access_token,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "data": { "startDate": arr[0].trim(), "endDate" : arr[1].trim(), "customer": $('#filter_cust_id').val() }
                };
                $.ajax(settings).done(function (response) {
                    $('#server-side-datatable').DataTable().destroy();
                    $('#export-button-blogs').DataTable().destroy();
                    oTable = $('#server-side-datatable').DataTable({
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
                                text: 'New Form',
                                action: function ( e, dt, node, config ) {
                                    $('#augmontOrderForm_modal').modal("show");
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : response.orders,
                        "columns" : [
                            { data : null, render: function (data, type, row) { 
                                return formatOnlyDate(new Date(row.created_at));
                            } },
                            // { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.merchantTransactionId); } },  
                            { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.userName); } },  
                            { data : null, render: function (data, type, row) { return row.mobileNumber; } },  
                            { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.ordertype); } },  
                            { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.metalType); } }, 
                            { data : null, render: function (data, type, row) { return row.quantity; } }, 
                            { data : null, render: function (data, type, row) { 
                                if(row.ordertype=="buy") {
                                    return formatter.format(parseFloat(row.preTaxAmount+row.totalTaxAmount)); 
                                } else {
                                    return formatter.format(parseFloat(row.totalAmount));
                                }
                            } }, 
                            { data : null, render: function (data, type, row) { 
                                if(row.ordertype.toLowerCase()=="buy") {
                                    if(row.metalType.toLowerCase()=="silver") {
                                        return formatter.format(parseFloat(row.quantity)*parseFloat(rates.rates.sBuy)); 
                                    } else {
                                        if(row.metalType.toLowerCase() == "gold") {
                                            return formatter.format(parseFloat(row.quantity)*parseFloat(rates.rates.gBuy)); 
                                        } else {
                                            return "";
                                        }
                                    }
                                } else {
                                    return "";
                                }
                            } }, 
                            { data : null, render: function (data, type, row) { 
                                if(row.ordertype.toLowerCase()=="buy") {
                                    if(row.metalType.toLowerCase()=="silver") {
                                        return formatter.format((parseFloat(row.quantity)*parseFloat(rates.rates.sBuy))-parseFloat(row.totalAmount)); 
                                    } else {
                                        if(row.metalType.toLowerCase() == "gold") {
                                            return formatter.format((parseFloat(row.quantity)*parseFloat(rates.rates.gBuy))-parseFloat(row.totalAmount)); 
                                        } else {
                                            return "";
                                        }
                                    }
                                } else {
                                    return "";
                                }
                            } }, 
                            // { data : null, render: function (data, type, row) { return row.razorpayId; } }, 
                            // { data : null, render: function (data, type, row) { 
                            //     if(row.ordertype=="buy") {
                            //         return formatter.format(parseFloat(row.preTaxAmount));
                            //     } else {
                            //         return "";
                            //     }
                            //  } },
                            { data : null, render: function (data, type, row) { 
                                if(row.transactionId==null) {
                                    return "-"; 
                                } else {
                                    if(row.ordertype.toLowerCase()=="buy") {
                                        return "<a href='buyInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                    } else {
                                        if(row.ordertype.toLowerCase()=="sip") {
                                            return "<a href='buyInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                        } else {
                                            // return "<a href='sellInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                            return "";
                                        }
                                    }
                                }
                            } },
                            { data : null, render: function (data, type, row) { 
                                if(row.ordertype.toLowerCase()=="buy") {
                                    if(row.merchantTransactionId==null) {
                                        return "-"; 
                                    } else {
                                        return "<a data-mti="+row.merchantTransactionId+" data-ui="+row.uniqueId+" class='btn btn-xs btn-info pull-right getBuyInfo'>Buy Info</a>";     
                                    }
                                } else {
                                    return "";
                                }
                            } }    
                        ],
                        "fnInitComplete": function() { $("#server-side-datatable").css("width","100%"); }
                    }).buttons().container().appendTo('#server-side-datatable_wrapper .col-md-6:eq(0)');
                    oTable = $('#server-side-datatable').DataTable();
                }).fail(function(data){
                    console.log(data);
                });
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    });

    $(document).on('change', '#resultType', function (e) {
        e.preventDefault();
        if($('#resultType').val() == 2) {
            $('#transactionFilter').hide();
            $('#transactionsTable').hide();
            $('#summaryTable').show();
            currentRates(2);
        } else {
            $('#transactionFilter').show();
            $('#transactionsTable').show();
            $('#summaryTable').hide();
            currentRates(1);
        }
        // var arr = $('#daterange').val().split('-');
        // console.log(arr);
        // var settings = {
        //     "url": "/augmont/getOrdersFilter",
        //     "method": "POST",
        //     "timeout": 0,
        //     "headers": {
        //         "Accept": "application/json",
        //         "Authorization": "Bearer "+localStorage.access_token,
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     "data": { "startDate": arr[0].trim(), "endDate" : arr[1].trim(), "customer": $('#filter_cust_id').val() }
        // };
        // $.ajax(settings).done(function (response) {
        //     console.log(response);
        // }).fail(function(data){
        //     console.log(data);
        // });
    });

    $('#addao').submit(function (e) {
        var formData = new FormData($(this)[0]);
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'saveAO',
            data: formData, // serializes the form's elements.
            cache: false,
            processData: false,
            contentType: false,
            success: function(res) {
                console.log(res);
                if(res['savestatus']==200 || res['savestatus']==201 || res['savestatus']==true) {
                    $('#addao')[0].reset();
                    $('#augmontOrderForm_modal').modal('hide');
                    createNoty(res['augOrder'], 'success');
                    orders();
                } else {
                    createNoty(res['augOrder'], 'danger');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            }  
        });
    });

    function currentRates(type) {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../augmont/currentRates",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if(type==1) {
                    orders(JSON.parse(data).result.data);
                } else {
                    summary(JSON.parse(data).result.data);
                }
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }

    $('#server-side-datatable').DataTable();
    function orders(rates) {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../augmont/allOrders",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                var clients = data.clients;
                $('#server-side-datatable').DataTable().destroy();
                $('#export-button-blogs').DataTable().destroy();
                oTable = $('#server-side-datatable').DataTable({
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
                            text: 'New Form',
                            action: function ( e, dt, node, config ) {
                                $('#augmontOrderForm_modal').modal("show");
                            }
                        },
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    "data" : data.orders,
                    "columns" : [
                        { data : null, render: function (data, type, row) { 
                            return formatOnlyDate(new Date(row.created_at));
                        } },
                        // { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.merchantTransactionId); } },  
                        { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.userName); } },  
                        { data : null, render: function (data, type, row) { return row.mobileNumber; } },  
                        { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.ordertype); } },  
                        { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.metalType); } }, 
                        { data : null, render: function (data, type, row) { return row.quantity; } }, 
                        { data : null, render: function (data, type, row) { 
                            if(row.ordertype=="buy") {
                                return formatter.format(parseFloat(row.preTaxAmount+row.totalTaxAmount)); 
                            } else {
                                return formatter.format(parseFloat(row.totalAmount));
                            }
                        } }, 
                        { data : null, render: function (data, type, row) { 
                            if(row.ordertype.toLowerCase()=="buy") {
                                if(row.metalType.toLowerCase()=="silver") {
                                    return formatter.format(parseFloat(row.quantity)*parseFloat(rates.rates.sBuy)); 
                                } else {
                                    if(row.metalType.toLowerCase() == "gold") {
                                        return formatter.format(parseFloat(row.quantity)*parseFloat(rates.rates.gBuy)); 
                                    } else {
                                        return "";
                                    }
                                }
                            } else {
                                return "";
                            }
                        } }, 
                        { data : null, render: function (data, type, row) { 
                            if(row.ordertype.toLowerCase()=="buy") {
                                if(row.metalType.toLowerCase()=="silver") {
                                    return formatter.format((parseFloat(row.quantity)*parseFloat(rates.rates.sBuy))-parseFloat(row.totalAmount)); 
                                } else {
                                    if(row.metalType.toLowerCase() == "gold") {
                                        return formatter.format((parseFloat(row.quantity)*parseFloat(rates.rates.gBuy))-parseFloat(row.totalAmount)); 
                                    } else {
                                        return "";
                                    }
                                }
                            } else {
                                return "";
                            }
                        } }, 
                        // { data : null, render: function (data, type, row) { return row.razorpayId; } }, 
                        // { data : null, render: function (data, type, row) { 
                        //     if(row.ordertype=="buy") {
                        //         return formatter.format(parseFloat(row.preTaxAmount));
                        //     } else {
                        //         return "";
                        //     }
                        //  } },
                        { data : null, render: function (data, type, row) { 
                            if(row.transactionId==null) {
                                return "-"; 
                            } else {
                                if(row.ordertype.toLowerCase()=="buy") {
                                    return "<a href='buyInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                } else {
                                    if(row.ordertype.toLowerCase()=="sip") {
                                        return "<a href='buyInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                    } else {
                                        // return "<a href='sellInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                        return "";
                                    }
                                }
                            }
                        } },
                        { data : null, render: function (data, type, row) { 
                            if(row.ordertype.toLowerCase()=="buy") {
                                if(row.merchantTransactionId==null) {
                                    return "-"; 
                                } else {
                                    return "<a data-mti="+row.merchantTransactionId+" data-ui="+row.uniqueId+" class='btn btn-xs btn-info pull-right getBuyInfo'>Buy Info</a>";     
                                }
                            } else {
                                return "";
                            }
                        } }    
                    ],
                    "fnInitComplete": function() { $("#server-side-datatable").css("width","100%"); }
                }).buttons().container().appendTo('#server-side-datatable_wrapper .col-md-6:eq(0)');
                oTable = $('#server-side-datatable').DataTable();
                $('#ao_cust_id').empty();
                var select = document.getElementById("ao_cust_id");
                var option = document.createElement("option");
                option.text = "Select Customer";
                option.value = "";
                select.add(option);
                $.each(clients, function(key,val){
                    var option = document.createElement("option");
                    option.text = val.cust_name;
                    option.value = val.pk_user_id;
                    select.add(option);
                });

                $('#filter_cust_id').empty();
                var select = document.getElementById("filter_cust_id");
                var option = document.createElement("option");
                option.text = "Select Customer";
                option.value = "";
                select.add(option);
                $.each(clients, function(key,val){
                    var option = document.createElement("option");
                    option.text = val.cust_name;
                    option.value = val.pk_user_id;
                    select.add(option);
                });
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }

    $('#summaryTableData').DataTable();
    function summary(rates) {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../augmont/allClientsSummary",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#summaryTableData').DataTable().destroy();
                oTable = $('#summaryTableData').DataTable({
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
                            text: 'New Form',
                            action: function ( e, dt, node, config ) {
                                $('#augmontOrderForm_modal').modal("show");
                            }
                        },
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    "data" : data.orders,
                    "columns" : [
                        { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.username)+"-"+row.user_id; } },
                        { data : null, render: function (data, type, row) { return row.contact; } },  
                        { data : null, render: function (data, type, row) { return row.goldBalance; } },
                        { data : null, render: function (data, type, row) { 
                            var buy = parseFloat(row.goldBuy);
                            var tax = ((parseFloat(row.goldBuy)-parseFloat(row.goldSell))*3)/100;
                            return formatter.format(buy-tax);
                        } },  
                        { data : null, render: function (data, type, row) { return formatter.format(parseFloat(row.goldBuy)-parseFloat(row.goldSell)); } },  
                        { data : null, render: function (data, type, row) { return formatter.format(parseFloat(row.goldBalance)*parseFloat(rates.rates.gSell)); } },  
                        { data : null, render: function (data, type, row) { 
                            var current = parseFloat(row.goldBalance)*parseFloat(rates.rates.gSell);
                            var buy = parseFloat(row.goldBuy)-parseFloat(row.goldSell);
                            var pl = current-buy;
                            if(pl>0) {
                                return '<span class="text-success">'+formatter.format(pl)+'</span>';
                            } else {
                                return '<span class="text-danger">'+formatter.format(pl)+'</span>';
                            }
                        } }, 
                        { data : null, render: function (data, type, row) { return row.silverBalance; } }, 
                        { data : null, render: function (data, type, row) { 
                            var buy = parseFloat(row.silverBuy);
                            var tax = ((parseFloat(row.silverBuy)-parseFloat(row.silverSell))*3)/100;
                            return formatter.format(buy-tax); 
                        } },  
                        { data : null, render: function (data, type, row) { return formatter.format(parseFloat(row.silverBuy)-parseFloat(row.silverSell)); } },  
                        { data : null, render: function (data, type, row) { return formatter.format(parseFloat(row.silverBalance)*parseFloat(rates.rates.sSell)); } },  
                        { data : null, render: function (data, type, row) { 
                            var current = parseFloat(row.silverBalance)*parseFloat(rates.rates.sSell);
                            var buy = parseFloat(row.silverBuy)-parseFloat(row.silverSell);
                            var pl = current-buy;
                            if(pl>0) {
                                return '<span class="text-success">'+formatter.format(pl)+'</span>';
                            } else {
                                return '<span class="text-danger">'+formatter.format(pl)+'</span>';
                            }
                        } }, 
                    ],
                    "fnInitComplete": function() { $("#summaryTableData").css("width","100%"); }
                }).buttons().container().appendTo('#summaryTableData_wrapper .col-md-6:eq(0)');
                oTable = $('#summaryTableData').DataTable();
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }

});