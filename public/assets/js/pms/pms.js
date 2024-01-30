"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            $('#export-button-pms').DataTable( {
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
                ]
            } );

            "use strict";
            pmsData();
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $('#pms_trans_date').datepicker({
                language: 'en',
                parentEl: '#pmsForm_modal',
                dateFormat: 'yyyy-mm-dd',
                maxDate: todayDate // Now can select only dates, which goes after today
            });

            $('#pms_cust_id').select2({
                dropdownParent: $('#pmsForm_modal')
            });
            
            $('#addpms').submit(function (e) {
                var formData = new FormData($(this)[0]);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'savePMS',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addpms')[0].reset();
                            $('#pmsForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            pmsData();
                        } else {
                            createNoty(response['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            // $("#addinsurance").validate({
            //     rules: {
            //         ins_cust_id: {required: true},
            //         ins_prod_type:{required: true},
            //         ins_comp_name: {required: true},
            //         ins_comp_branch: {required: true},
            //         ins_policy_name: {required: true},
            //         ins_policy_no: {required: true},
            //         ins_policy_issued_date:{required: true},
            //         ins_policy_maturity_date: {required: true},
            //         ins_policy_prem_amt: { required: true, number: true },
            //         ins_policy_sa_amt: {required: true, number: true },
            //         ins_policy_term_years: {required: function(){ if($("select[name=ins_prod_type]").val() == "life"){ return true; } else { return false; } } , number: true},
            //         ins_policy_pay_mode:{required: true},
            //         ins_policy_next_Prem_date: {required: true},
            //         ins_policy_plan_type: {required: true},
            //         ins_policy_money_back: {required: false },
            //         ins_policy_acci_death_benefit: {required: false },
            //         ins_policy_status:{required: true},
            //         ins_policy_nominee_name: {required: true},
            //         ins_policy_nominee_relation: {required: true},
            //         ins_policy_veh_type: { required: function(){ if($("select[name=ins_prod_type]").val() == "motor"){ return true; } else { return false; } } },
            //         ins_policy_veh_reg_no: {required: function(){ if($("select[name=ins_prod_type]").val() == "motor"){ return true; } else { return false; } } },
            //         ins_policy_veh_model:{required: function(){ if($("select[name=ins_prod_type]").val() == "motor"){ return true; } else { return false; } } },
            //         ins_policy_loan_taken: {required: false },
            //         ins_policy_loan_date: {required: false },
            //         ins_policy_bal_units: {required: false, number: true },
            //         ins_policy_bal_date: {required: false },
            //         ins_policy_cur_value:{required: false },
            //         ins_policy_exp_maturity_value: {required: false },
            //         ins_policy_remarks: {maxlength : 200 }
            //     },
            //     messages: {
            //         ins_cust_id: {required: "Please select customer"},
            //         ins_prod_type:{required: "Please select product type"},
            //         ins_comp_name: {required: "Please enter company name"},
            //         ins_comp_branch: {required: "Please enter branch"},
            //         ins_policy_name: {required: "Please enter policy name"},
            //         ins_policy_no: {required: "Please enter policy number"},
            //         ins_policy_issued_date:{required: "Please select policy issued date"},
            //         ins_policy_maturity_date: {required: "Please select policy maturity date"},
            //         ins_policy_prem_amt: { required: "Please enter premium amount", number: "Please enter digits only" },
            //         ins_policy_sa_amt: {required: "Please enter sum assured amount", number: "Please enter digits only" },
            //         ins_policy_term_years: {required: "Please enter term years", number: "Please enter digits only"},
            //         ins_policy_pay_mode:{required: "Please select payment mode"},
            //         ins_policy_next_Prem_date: {required: "Please select next premium date"},
            //         ins_policy_plan_type: {required: "Please select plan type"},
            //         ins_policy_money_back: {required: "Please select money back availability" },
            //         ins_policy_acci_death_benefit: {required: "Please select accidental benefit availablity"},
            //         ins_policy_status:{required: "Please select the status"},
            //         ins_policy_nominee_name: {required: "Please enter nominee name"},
            //         ins_policy_nominee_relation: {required: "Please select nominee relation"},
            //         ins_policy_veh_type: {required: "Please select vehicle type" },
            //         ins_policy_veh_reg_no: {required: "Please enter vehicle registration"},
            //         ins_policy_veh_model:{required: "Please enter vehicle model"},
            //         ins_policy_loan_taken: {required: "Please select loan taken "},
            //         ins_policy_loan_date: {required: "Please select loan date"},
            //         ins_policy_bal_units: {required: "Please enter balance available units", number: "Please enter digits only" },
            //         ins_policy_bal_date: {required: "Please select balance units as on date"},
            //         ins_policy_cur_value:{required: "Please enter current value"},
            //         ins_policy_exp_maturity_value: {required: "Please enter maturity value"},
            //         ins_policy_remarks: {maxlength : "Please enter below 200 charecters" }
            //     },
            //     errorPlacement: function(label, element) {
            //         label.addClass('mt-2 text-danger');
            //         label.insertAfter(element);
            //     },
            //     highlight: function(element, errorClass) {
            //         $(element).parent().addClass('has-danger');
            //         $(element).addClass('form-control-danger');
            //     }
            // });
            $(document).on("click", ".editPms", function (e) {
                e.preventDefault();
                var objData = {};
                var custid = "";
                objData["ins_id"] = $(this).attr("data-id");
                $.ajax({
                    url: "insuranceById",
                    type: "POST",
                    data: { "ins_id": $(this).attr("data-id") },
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#insuranceForm_modal').modal('show');
                        $.each(response, function (key, val) {
                            if(key=="ins_prod_type") {
                                $('#ins_prod_type').val(val);
                                if(val=="general") {
                                    generalOptions();
                                } else {
                                    if(val=="life") {
                                        lifeOptions();
                                    } else {
                                        if(val=="motor") {
                                            motorOptions();
                                        } else {
                                            if(val=="health") {
                                                healthOptions();
                                            } else {
                                                
                                            }
                                        }
                                    }
                                }
                            } else {
                                if(key=="ins_cust_id") {
                                    custid = val;
                                    $("#ins_cust_id").select2().val(val).trigger("change");
                                } else {
                                    if(key=="ins_policy_document") {
                                        var temp = "";
                                        var temp1 = "";
                                        if(val.includes("|")) {
                                            const myArr = val.split("|");
                                            myArr.forEach(function(item) {
                                                temp = temp + "<p style='margin: 0rem;'><a href='https://admin.optymoney.com/__uploaded.files/insurance/"+custid+"/"+item+"'>"+item+"</a></p>";
                                                temp1 = temp1 + "<p style='margin: 0rem;'>"+item+"</p>";
                                            });
                                        } else {
                                            temp = "<a href='https://admin.optymoney.com/__uploaded.files/insurance/"+custid+"/"+val+"'>"+val+"</a>";
                                            temp1 = "<p style='margin: 0rem;'>"+val+"</p>";
                                        }
                                        $('#insuranceDocuments').html(temp);
                                        // $('#insuranceDocuments').html(temp1);
                                    } else {
                                        $('#' + key).val(val);
                                    }
                                }
                            }
                        });
                    }
                 });
            });

            $(document).on("click", ".insDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["ins_id"] = $(this).attr("data-id");
                $.ajax({
                    url: ajax_url+"deleteINS",
                    type: "POST",
                    data: objData,
                    async: false,
                    beforeSend: function () { $('.ajax-loader').css("visibility", "visible"); },
                    complete: function () { $('.ajax-loader').css("visibility", "hidden"); },
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

            $('#updateHelpdeskStatus').submit(function (e) {
                var formData = new FormData($(this)[0]);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updateHelpdeskStatus',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        console.log(response);
                        // if(res['status']==1) {
                        //     $('#uploadStatus').html("<div class='alert alert-success' role='alert'>"+res['msg']+"</div>");
                        // } else {
                        //     $('#uploadStatus').html("<div class='alert alert-danger' role='alert'>"+res['msg']+"</div>");
                        // }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });
        })(jQuery);

        function pmsData() {
            $.ajax({
                url: "api_pms",
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
                        buttons: buttons,
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.cust_name; } }, 
                            { data : null, render: function (data, type, row) { return row.pms_prod_type; } }, 
                            { data : null, render: function (data, type, row) { return row.pms_trans_type; } }, 
                            { data : null, render: function (data, type, row) { return row.pms_trans_date; } }, 
                            { data : null, render: function (data, type, row) { return parseFloat(row.pms_trans_amt).toFixed(3); } }, 
                            { data : null, width: "20%", render: function (data, type, row) { 
                                    if(row.pms_document=="" || row.pms_document==null) { return ""; } 
                                    else { 
                                        var temp = "";
                                        var temp1 = "";
                                        var str = row.pms_document;
                                        if(str.includes("|")) {
                                            const myArr = str.split("|");
                                            myArr.forEach(function(item) {
                                                temp = temp + "<p style='margin: 0rem;'><a target='_blank' href='https://admin.optymoney.com/itr/getfile/"+row.pms_cust_id+"/"+item+"'>"+item+"</a></p>";
                                                temp1 = temp1 + "<p style='margin: 0rem;'>"+item+"</p>";
                                            });
                                        } else {
                                            temp = "<a target='_blank' href='https://admin.optymoney.com/itr/getfile/"+row.pms_cust_id+"/"+row.pms_document+"'>"+row.pms_document+"</a>"; 
                                            temp1 = "<p style='margin: 0rem;'>"+row.pms_document+"</p>";
                                        }
                                    }
                                    return temp;
                                }
                            }, 
                            { data : null, render: function (data, type,row) { 
                                var viewDiv = '<div class="m-b-30"><div class="btn-group" role="group" aria-label="Button group with nested dropdown"><div class="btn-group" role="group"><button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                                // if(viewOption) {
                                //     viewDiv = viewDiv+'<a href="clientCard/'+row.fr_user_id+'/view" class="dropdown-item">View</a>';
                                // }
                                if(editOption) {
                                    viewDiv = viewDiv+'<a href="" class="dropdown-item editInsurance" data-id="' + row.pms_id + '">Edit</a>';
                                    // viewDiv = viewDiv+'<button type="button" class="btn btn-warning insEdit" data-id="' + row.ins_id + '">Edit</button>';
                                }
                                if(deleteOption) {
                                    viewDiv = viewDiv+'<a href="" class="dropdown-item deleteInsurance" data-id="' + row.pms_id + '">Delete</a>';
                                    // viewDiv = viewDiv+'<button type="button" class="btn btn-danger insDelete" data-id="' + row.ins_id + '">Delete</button>';
                                }
                                viewDiv = viewDiv+'</div></div></div></div>';
                                if(viewOption == false && editOption == false) {
                                    return ""; 
                                } else {
                                    return viewDiv; 
                                }
                            }
                        }],
                        "fnInitComplete": function() { $("#insurance_table").css("width","100%"); }
                    }).buttons().container().appendTo('#insurance_table_wrapper .col-md-6:eq(0)');
                    oTable = $('#insurance_table').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);