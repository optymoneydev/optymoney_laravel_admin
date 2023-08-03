"use strict";
setTimeout(function(){
    var oTable;
    var oPmsTable;
    var oInsTable;
    var iTableCounter = 1;
    var oInnerTable;
    var detailsTableHtml;
    var siteurl;
    var schemes = [];
    var schemeTypes = {};
    var amcList = [];
    var queryArr = [];
    var tempArr = {};
    var navArr = [];
    var tot_c_amnt = 0;
    var total_inv = 0;
    var tot_profit = 0;
    var fundLabels = [];
    var fundval = [];
    var fundColor = ['#9BBFE0', '#E8A09A', '#FBE29F', '#C6D68F'];
    var purchaseArr = ['P', 'DR', 'SI', 'TI'];
    var sellArr = ['R', 'SO', 'TO', 'DP'];
    var singleChar = ['P', 'R'];
    var doubleChar = ['DR', 'SI', 'TI', 'SO', 'TO', 'DP'];
    
        (function($) {
            "use strict";
            $(document).ajaxSend(function() {
                $(".loader-wrapper").show();
            });
            insuranceData();
            pmsData();
            itrData();
            mutualFunds();
            augmontOrders();
            $(document).on('click', '.re_evaluate', function (e) {
                e.preventDefault();
                var data = {"pan":$(this).attr("data-pan"), "scheme":$(this).attr("data-scheme")};
                $.ajax({
                    url: APP_URL+"/api/mf/reevaluate",
                    method: "POST",
                    headers: {
                        "content-type": "application/json",
                        "cache-control": "no-cache"
                    },
                    beforeSend: function() {
                        $(".loader-wrapper").show();
                    },
                    complete: function(){
                        $(".loader-wrapper").hide();
                    },
                    processData: false,
                    data: JSON.stringify(data),
                    success: function(response) {
                        createNoty("Updated successfully", 'success');
                        location.reload(true);
                    }
                });
            });
            $(document).on('click', '.transacData', function(e) {
                e.preventDefault();
                var data = {"pan":$(this).attr("data-pan"), "scheme_code":$(this).attr("data-id"), "folio_no":$(this).attr("data-val"), "amc":$(this).attr("data-amc")};
                console.log(data);
                $.ajax({
                    url: "/mf/getTransactionsById",
                    method: "POST",
                    data: data,
                    async: false,
                    beforeSend: function() {
                        $(".loader-wrapper").show();
                    },
                    complete: function(){
                        $(".loader-wrapper").hide();
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#detailsMFTable').DataTable().destroy();
                        console.log(response);
                        $('#mftransactionModal').modal('show');
                        if(response.schemetype == "cams") {
                            $('#scheme_title').html(response.data[0].scheme);
                            oTable = $('#detailsMFTable').removeAttr('width').DataTable({
                                "paging": true,
                                "searching": true,
                                "ordering": true,
                                "info": true,
                                "responsive": true, 
                                "lengthChange": false, 
                                "autoWidth": false,
                                "data" : response.data,
                                columnDefs: [
                                    { width: 200, targets: 0 }
                                ],
                                "fixedColumns": true,
                                "columns" : [
                                    { data : null, render: function ( data, type, row ) { return row.traddate; } }, 
                                    { data : null, render: function ( data, type, row ) { return row.trxnno; } }, 
                                    { data : null, render: function (data, type,row) { return row.trxntype; } }, 
                                    { data : null, render: function (data, type,row) { return row.units; } }, 
                                    { data : null, render: function (data, type,row) { return formatter.format(parseFloat(row.purprice).toFixed(3)); } }, 
                                    { data : null, render: function (data, type,row) { return formatter.format(parseFloat(row.amount)*parseFloat(1)); } }, 
                                ],
                                "fnInitComplete": function() {
                                    $("#detailsMFTable").css("width","100%");
                                }
                            }).buttons().container().appendTo('#detailsMFTable_wrapper .col-md-6:eq(0)');
                        } else {
                            if(response.schemetype == "karvy") {
                                $('#scheme_title').html(response.data[0].funddesc);
                                oTable = $('#detailsMFTable').removeAttr('width').DataTable({
                                    "paging": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "responsive": true, 
                                    "lengthChange": false, 
                                    "autoWidth": false,
                                    "data" : response.data,
                                    columnDefs: [
                                        { width: 200, targets: 0 }
                                    ],
                                    "fixedColumns": true,
                                    "columns" : [
                                        { data : null, render: function ( data, type, row ) { return row.td_trdt; } }, 
                                        { data : null, render: function ( data, type, row ) { return row.td_trno; } }, 
                                        { data : null, render: function (data, type,row) { return row.td_purred; } }, 
                                        { data : null, render: function (data, type,row) { return row.td_units; } }, 
                                        { data : null, render: function (data, type,row) { return formatter.format(parseFloat(row.td_pop).toFixed(3)); } }, 
                                        { data : null, render: function (data, type,row) { return formatter.format(parseFloat(row.td_amt)*parseFloat(1)); } }, 
                                    ],
                                    "fnInitComplete": function() {
                                        $("#detailsMFTable").css("width","100%");
                                    }
                                }).buttons().container().appendTo('#detailsMFTable_wrapper .col-md-6:eq(0)');
                            } else {
                                oTable = $('#detailsMFTable').removeAttr('width').DataTable();
                            }
                        }
                    }
                });
            });
        })(jQuery);

        function itrData() {
            var userDocs = [];
            var adminDocs = {};
            var adminDocs1 = [];
            $.ajax({
                url: APP_URL+"/docs/getDocsByUser/"+$('#clientId').val(),
                type: "GET",
                async: false,
                success: function(response) {
                    $.each(response,function(key,val) {
                        if(key=="itr") {
                            $.each(val,function(key1, val1){
                                var item = {};
                                item ["type"] = "itr";
                                item ["name"] = val1.itr_pan;
                                item ["link"] = 'https://admin.optymoney.com/itr/getfile/'+$('#clientId').val()+'/'+val1.itr_v;
                                item ["assessment"] = val1.asses_year;
                                adminDocs[val1.asses_year] = item;
                            });
                        } else {
                            if(key=="docs") {
                                $.each(val,function(key2, val2){
                                    var incStr = val2.file.includes("|");  
                                    if(incStr) {
                                        var res2 = val2.file.split("|");
                                        $.each(res2,function(i){
                                            if(res2[i] != "") {
                                                var item1 = {};
                                                item1 ["type"] = "doc";
                                                item1 ["name"] = res2[i];
                                                item1 ["link"] = 'https://optymoney.com/itr/getfile/'+$('#clientId').val()+'/'+res2[i];
                                                userDocs.push(item1);
                                            }
                                        });
                                    } else {
                                        var item1 = {};
                                        item1 ["type"] = "doc";
                                        item1 ["name"] = val2.file;
                                        item1 ["link"] = 'https://optymoney.com/itr/getfile/'+$('#clientId').val()+'/'+val2.file;
                                        userDocs.push(item1);
                                    }
                                });
                            } else {
                                if(key=="goldupload") {
                                    $.each(val,function(key2, val2){
                                        if(val2.panAttachment != "") {
                                            var item2 = {};
                                            item2 ["type"] = "doc";
                                            item2 ["name"] = "PAN Card";
                                            item2 ["link"] = "https://gold.optymoney.com/uploads/"+val2.panAttachment;
                                            userDocs.push(item2);
                                        }
                                        if(val2.aadharAttachment != "") {
                                            var item3 = {};
                                            item3 ["type"] = "doc";
                                            item3 ["name"] = "Aadhaar Card";
                                            item3 ["link"] = "https://gold.optymoney.com/uploads/"+val2.aadharAttachment;
                                            userDocs.push(item3);
                                        }
                                    });
                                }
                            }
                        }
                    });
                    $.each(adminDocs,function(key1, val1){
                        adminDocs1.push(val1);
                    });
                    $("#tax_list").dataTable().fnDestroy();
                    $("#tax_list1").dataTable().fnDestroy();
                    $('#tax_list1').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "data" : adminDocs1,
                        "columns" : [
                            { data : null, render: function (data, type, row) { 
                                return row.assessment; 
                            } }, 
                            { data : null, render: function (data, type, row) { return '<a target="_blank" href="'+row.link+'"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Acknowledgement</a>'; } } 
                        ],
                        "fnInitComplete": function() { $("#tax_list1").css("width","100%"); }
                    });
                    $('#tax_list').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "data" : userDocs,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.name; } }, 
                            { data : null, render: function (data, type, row) { return '<a target="_blank" href="'+row.link+'"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download</a>'; } } 
                        ],
                        "fnInitComplete": function() { $("#tax_list").css("width","100%"); }
                    });
                }
            });
        }

        function insuranceData() {
            $.ajax({
                url: APP_URL+"/insurance/getInsuranceByUser/"+$('#clientId').val(),
                type: "GET",
                success: function(response) {
                    var queryArr = response;
                    $('#export-button-insurance').DataTable().destroy();
                    oTable = $('#export-button-insurance').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        "buttons": ["copy", "csv", "excel", "pdf", "print"],
                        "data" : queryArr,
                        "columns" : [
                            { data : null, render: function (data, type, row) { 
                                return row.ins_prod_type; 
                            } }, 
                            { data : null, render: function (data, type, row) { return row.ins_policy_name; } }, 
                            { data : null, render: function (data, type, row) { return row.ins_policy_issued_date; } }, 
                            { data : null, render: function (data, type, row) { return row.ins_policy_maturity_date; } }, 
                            { data : null, render: function (data, type, row) { return row.ins_policy_prem_amt; } }, 
                            { data : null, render: function (data, type, row) { return row.ins_policy_next_prem_date; } }, 
                            { data : null, render: function (data, type, row) { 
                                    if(row.ins_policy_document=="" || row.ins_policy_document==null) { return ""; }
                                    else {
                                        var temp = "";
                                        var temp1 = "";
                                        var str = row.ins_policy_document;
                                        if(str.includes("|")) {
                                            const myArr = str.split("|");
                                            myArr.forEach(function(item) {
                                                if(item!="") {
                                                    temp = temp + "<p style='margin: 0rem;'><a title='"+item+"' href='https://admin.optymoney.com/itr/getfile/"+row.ins_cust_id+"/insurance/"+item+"'>Download</a></p>";
                                                    temp1 = temp1  + "<p style='margin: 0rem;'>"+item+"</p>";
                                                }
                                            });
                                        } else {
                                            temp = "<a title='"+row.ins_policy_document+"' href='https://admin.optymoney.com/itr/getfile/"+row.ins_cust_id+"/insurance/"+row.ins_policy_document+"'>Download</a>";
                                            temp1 = "<p style='margin: 0rem;'>"+row.ins_policy_document+"</p>";
                                        }
                                        return temp;
                                    }
                                }
                            }, 
                            { data : null, render: function (data, type, row) { 
                                return '<div class="btn-group" role="group" aria-label="Button group with nested dropdown"><div class="btn-group" role="group"><button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a href="#" class="dropdown-item viewInsurance" data-id="$row.ins_id">View</a><a href="#" class="dropdown-item editInsurance" data-id="$row.ins_id">Edit</a><a href="#" class="dropdown-item deleteInsurance" data-id="$row.ins_id">Delete</a></div></div></div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#insurance_table").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-insurance_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-insurance').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }

        function pmsData() {
            $.ajax({
                url: APP_URL+"/pms/getPmsByUser/"+$('#clientId').val(),
                type: "GET",
                success: function(response) {
                    $('#export-button-pms').DataTable().destroy();
                    oTable = $('#export-button-pms').DataTable({
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
                                    $('#pmsForm_modal').modal("show");
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.pms_prod_type; } }, 
                            { data : null, render: function (data, type, row) { return row.pms_trans_type; } }, 
                            { data : null, render: function (data, type, row) { return row.pms_trans_date; } }, 
                            { data : null, render: function (data, type, row) { return parseFloat(row.pms_trans_amt).toFixed(3); } }, 
                            { data : null, render: function (data, type, row) { 
                                    if(row.pms_document=="" || row.pms_document==null) { return ""; } 
                                    else { 
                                        var temp = "";
                                        var temp1 = "";
                                        var str = row.pms_document;
                                        if(str.includes("|")) {
                                            const myArr = str.split("|");
                                            myArr.forEach(function(item) {
                                                temp = temp + "<p style='margin: 0rem;'><a href='https://admin.optymoney.com/__uploaded.files/pms/"+row.pms_cust_id+"/"+item+"'>"+item+"</a></p>";
                                                temp1 = temp1 + "<p style='margin: 0rem;'>"+item+"</p>";
                                            });
                                        } else {
                                            temp = "<a href='https://admin.optymoney.com/__uploaded.files/pms/"+row.pms_cust_id+"/"+row.pms_document+"'>"+row.pms_document+"</a>"; 
                                            temp1 = "<p style='margin: 0rem;'>"+row.pms_document+"</p>";
                                        }
                                    }
                                    return temp;
                                }
                            }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group" role="group" aria-label="Button group with nested dropdown"><div class="btn-group" role="group"><button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a href="#" class="dropdown-item viewPms" data-id="$row.pms_id">View</a><a href="#" class="dropdown-item editPms" data-id="$row.pms_id">Edit</a><a href="#" class="dropdown-item deletePms" data-id="$row.pms_id">Delete</a></div></div></div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#export-button-pms").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button-pms_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button-pms').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }

        function mutualFunds() {
            var total_inv=0;
            $.ajax({
                url: APP_URL+"/mf/getPortfolioByUser/"+$('#clientId').val(),
                type: "GET",
                success: function(response) {
                    var data = response;
                    var navArr = [];
                    $.each(data, function(index,dataTemp){
                        if(dataTemp.mf_con_cur_val > 0){
                            total_inv = total_inv + parseFloat(dataTemp.mf_con_tot_inv);
                            tot_c_amnt = tot_c_amnt + parseFloat(dataTemp.mf_con_tot_units)*parseFloat(dataTemp.nav.net_asset_value);
                            if(dataTemp.mf_con_tot_units>0) {
                                navArr.push(dataTemp);
                            }
                        }
                    });
                    
                    $('#transaction_list').DataTable().destroy();
                    oTable = $('#transaction_list').removeAttr('width').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": false,
                        // "scrollX": true,
                        "buttons": [
                            { extend: 'copy', exportOptions: { columns: 'th:not(:last-child)' } },
                            { extend: 'csv', exportOptions: { columns: 'th:not(:last-child)' } },
                            { extend: 'pdf', exportOptions: { columns: 'th:not(:last-child)' } },
                            { extend: 'print', exportOptions: { columns: 'th:not(:last-child)' } },
                            ],
                        // "buttons": ["copy", "csv", "excel", "pdf", "print"],
                        "data" : navArr,
                        columnDefs: [
                            { width: 200, targets: 0 }
                        ],
                        "fixedColumns": true,
                        "columns" : [
                            { data : null, render: function ( data, type, row ) { 
                                    if(row.nav=="" || row.nav==null) {
                                        return '<div class="text-wrap"><a href="" class="transacData" style="font-weight: bold;" data-pan="'+row.mf_con_pan+'" data-amc="'+row.mf_con_amc+'" data-id="'+row.mf_con_sch_code+'" data-val="'+row.mf_con_folio+'">'+row.mf_con_sch_name+'</a></div>';
                                    } else {
                                        return '<div class="text-wrap"><a href="" class="transacData" style="font-weight: bold;" data-pan="'+row.mf_con_pan+'" data-amc="'+row.mf_con_amc+'" data-id="'+row.mf_con_sch_code+'" data-val="'+row.mf_con_folio+'">'+row.mf_con_sch_name+'</a></div>';
                                    }
                                }
                            }, 
                            { data : null, render: function (data, type,row) { return row.mf_con_folio; } }, 
                            { data : null, render: function (data, type,row) { return row.mf_con_sch_type; } }, 
                            { data : null, render: function (data, type,row) { 
                                var stamp_duty = 0;
                                if(row.mf_con_stamp_duty) {
                                    stamp_duty = parseFloat(row.mf_con_stamp_duty);
                                } else {
                                    stamp_duty = 0;
                                }
                                return formatter.format(parseFloat(parseFloat(row.mf_con_tot_inv)+parseFloat(stamp_duty)).toFixed(2)); 
                            } }, 
                            { data : null, render: function (data, type,row) { return parseFloat(row.mf_con_tot_units).toFixed(3); } }, 
                            { data : null, render: function (data, type,row) { 
                                    if(row.nav=="" || row.nav==null) {
                                        return formatter.format(parseFloat(row.mf_con_tot_units)*parseFloat(1)); 
                                    } else {
                                        return formatter.format(parseFloat(row.mf_con_tot_units)*parseFloat(row.nav.net_asset_value)); 
                                    }
                                } 
                            }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="m-b-30">'+
                                '<div class="btn-group" role="group" aria-label="Button group with nested dropdown"><div class="btn-group" role="group"><button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a href="clientCard/'+row.fr_user_id+'/view" class="dropdown-item">View</a>'+
                                '<a href="#" data-pan="'+row.mf_con_pan+'" data-scheme="'+row.mf_con_sch_code+'" data-amc="'+row.mf_con_amc+'"class="dropdown-item re_evaluate">Re Evaluate</a></div></div></div></div>'; 
                            }}
                        ],
                        "fnInitComplete": function() {
                            $("#transaction_list").css("width","100%");
                        }
                    }).buttons().container().appendTo('#transaction_list_wrapper .col-md-6:eq(0)');
                    // oTable = $('#transaction_list').DataTable();
                    $('#totInv').text(formatter.format(total_inv));
                    $('#curInv').text(formatter.format(tot_c_amnt));
                    tot_profit = tot_c_amnt-total_inv
                    if(tot_profit>0) {
                        $('#profitData').show();
                        $('#profitVal').text(formatter.format(tot_profit));
                        $('#lossData').hide();
                    } else {
                        $('#profitData').hide();
                        $('#lossVal').text(formatter.format(tot_profit));
                        $('#lossData').show();
                    }
                    $(".loader-wrapper").hide();
                }
            });
        }

        function augmontOrders() {
            $.ajax({
                type: 'GET',
                data: '',
                url: "../../../augmont/OrdersByUsers/"+$('#clientId').val(),
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if(data.length>0) {
                        $('#totGold').text(data[0].goldBalance);
                        $('#totSilver').text(data[0].silverBalance);
                        $('#export-button-gold').DataTable().destroy();
                        oTable = $('#export-button-gold').DataTable({
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
                                { data : null, render: function (data, type, row) { 
                                    return formatDate(new Date(row.created_at));
                                } },
                                { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.merchantTransactionId); } },  
                                { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.ordertype); } },  
                                { data : null, render: function (data, type, row) { return capitalizeFirstLetter(row.metalType); } }, 
                                { data : null, render: function (data, type, row) { return row.quantity.toFixed(2); } }, 
                                { data : null, render: function (data, type, row) { 
                                    if(row.ordertype=="buy") {
                                        return formatter.format(parseFloat(row.preTaxAmount+row.totalTaxAmount)); 
                                    } else {
                                        return formatter.format(parseFloat(row.totalAmount));
                                    }
                                } }, 
                                { data : null, render: function (data, type, row) { return row.razorpayId; } }, 
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
                                        if(row.ordertype=="buy" || row.ordertype=="Buy") {
                                            return "<a href='buyInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                        } else {
                                            if(row.ordertype=="sip" || row.ordertype=="SIP") {
                                                return "<a href='buyInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                            } else {
                                                // return "<a href='sellInvoice/"+row.transactionId+"' target='_blank' class='btn btn-xs btn-info pull-right'>Download</a>"; 
                                                return "";
                                            }
                                        }
                                    }
                                } }    
                            ],
                            "fnInitComplete": function() { $("#export-button-gold").css("width","100%"); }
                        }).buttons().container().appendTo('#export-button-gold_wrapper .col-md-6:eq(0)');
                        oTable = $('#export-button-gold').DataTable();
                    } else {
                        oTable = $('#export-button-gold').DataTable();
                    }
                },
                error: function() {
                    console.log("wrong...!!!");
                }
            });
        }

    }
,350);