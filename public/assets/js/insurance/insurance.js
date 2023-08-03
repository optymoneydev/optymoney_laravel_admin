"use strict";
setTimeout(function(){
        (function($) {
            "use strict";
            // insuranceData();
            // createNoty('Hi! This is my message', 'info');
            // createNoty('success', 'success');
            // createNoty('warning', 'warning');
            // createNoty('danger', 'danger');
            // createNoty('info', 'info');
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            var afterSeven = new Date();
            afterSeven.setDate(afterSeven.getDate() + 7)
        
            $('#ins_policy_issued_date').datepicker({
                language: 'en',
                parentEl: '#insuranceForm_modal',
                dateFormat: 'yyyy-mm-dd',
                maxDate: todayDate // Now can select only dates, which goes after today
            });

            $('#ins_policy_maturity_date').datepicker({
                language: 'en',
                parentEl: '#insuranceForm_modal',
                dateFormat: 'yyyy-mm-dd',
                minDate: todayDate // Now can select only dates, which goes after today
            });

            $('#ins_policy_next_prem_date').datepicker({
                language: 'en',
                parentEl: '#insuranceForm_modal',
                dateFormat: 'yyyy-mm-dd',
                minDate: todayDate // Now can select only dates, which goes after today
            });

            $('#ins_policy_loan_date').datepicker({
                language: 'en',
                parentEl: '#insuranceForm_modal',
                dateFormat: 'yyyy-mm-dd',
                maxDate: todayDate // Now can select only dates, which goes after today
            });

            $('#ins_cust_id').select2({
                dropdownParent: $('#insuranceForm_modal')
            });
            $('#itr_cust_id').on('change', function() {
                var selectedText = $(this).find("option:selected").text();
                const optionText = selectedText.split(" - ");
                $('#pancheck').val($.trim(optionText[0]));
            });
            
            $('#ins_prod_type').change(function () {
                var optionSelected = $(this).find("option:selected");
                var valueSelected  = optionSelected.val();
                var cust = $('#ins_cust_id').val();
                
                $('#ins_cust_id').val(cust);
                if(valueSelected=="general") {
                    generalOptions();
                } else {
                    if(valueSelected=="life") {
                        lifeOptions();
                    } else {
                        if(valueSelected=="motor") {
                            motorOptions();
                        } else {
                            if(valueSelected=="health") {
                                healthOptions();
                            } else {
                                
                            }
                        }
                    }
                }
            });
        
            $('#ins_policy_loan_taken').change(function () {
                var optionSelected = $(this).find("option:selected");
                var valueSelected  = optionSelected.val();
                console.log(valueSelected);
                if(valueSelected=="Y") {
                    $('#ins_policy_loan_date_group').show();
                } else {
                    if(valueSelected=="N") {
                        $('#ins_policy_loan_date_group').val("");
                        $('#ins_policy_loan_date_group').hide();
                    }
                }
            });
            
            $('#ins_policy_issued_date').change(function() {
                var d = $(this).val().split("-");
                var c = (parseInt(d[0])+1)+"-"+d[1]+"-"+parseInt(d[2]);
                $('#ins_policy_next_prem_date').val(c);
            });
            
            $('#addinsurance').submit(function (e) {
                var formData = new FormData($(this)[0]);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'saveInsurance',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addinsurance')[0].reset();
                            $('#insuranceForm_modal').modal('hide');
                            createNoty(res['message'], 'success');
                            insuranceData();
                        } else {
                            createNoty(res['message'], 'danger');
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
            $(document).on("click", ".editInsurance", function (e) {
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

            $('#addITRV').submit(function (e) {
                var formData = new FormData($(this)[0]);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'itrVUpload',
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

        function lifeOptions() {
            $('#ins_policy_veh_type_group').hide();
            $('#ins_policy_veh_reg_no_group').hide();
            $('#ins_policy_veh_model_group').hide();
        
            $('#ins_policy_loan_taken_group').show();
            $('#ins_policy_loan_date_group').show();
            $('#ins_policy_bal_units_group').show();
            $('#ins_policy_bal_date_group').show();
            $('#ins_policy_cur_value_group').show();
            $('#ins_policy_exp_maturity_value_group').show();
        
            $('#ins_policy_nominee_name_group').show();
            $('#ins_policy_nominee_relation_group').show();
        
            $('#ins_policy_plan_type_group').show();
            $('#ins_policy_term_years_group').show();
            $('#ins_policy_premium_pay_term_years_group').show();
            $('#ins_policy_money_back_group').show();
        }
        function generalOptions() {
            $('#ins_policy_veh_type_group').hide();
            $('#ins_policy_veh_reg_no_group').hide();
            $('#ins_policy_veh_model_group').hide();
        
            $('#ins_policy_loan_taken_group').hide();
            $('#ins_policy_loan_date_group').hide();
            $('#ins_policy_bal_units_group').hide();
            $('#ins_policy_bal_date_group').hide();
            $('#ins_policy_cur_value_group').hide();
            $('#ins_policy_money_back_group').hide();
            $('#ins_policy_exp_maturity_value_group').hide();
        
            $('#ins_policy_nominee_name_group').hide();
            $('#ins_policy_nominee_relation_group').hide();
        
            $('#ins_policy_plan_type_group').hide();
            $('#ins_policy_term_years_group').show();
            $('#ins_policy_premium_pay_term_years_group').hide();
        }
        function healthOptions() {
            $('#ins_policy_veh_type_group').hide();
            $('#ins_policy_veh_reg_no_group').hide();
            $('#ins_policy_veh_model_group').hide();
        
            $('#ins_policy_loan_taken_group').hide();
            $('#ins_policy_loan_date_group').hide();
            $('#ins_policy_bal_units_group').hide();
            $('#ins_policy_bal_date_group').hide();
            $('#ins_policy_cur_value_group').hide();
            $('#ins_policy_exp_maturity_value_group').hide();
        
            $('#ins_policy_nominee_name_group').hide();
            $('#ins_policy_nominee_relation_group').hide();
        
            $('#ins_policy_plan_type_group').hide();
            $('#ins_policy_term_years_group').show();

            $('#ins_policy_money_back_group').hide();
            $('#ins_policy_acci_death_benefit_group').hide();
            $('#ins_policy_term_years_group').hide();
        }
        function motorOptions() {
            $('#ins_policy_veh_type_group').show();
            $('#ins_policy_veh_reg_no_group').show();
            $('#ins_policy_veh_model_group').show();
            
            $('#ins_policy_loan_taken_group').hide();
            $('#ins_policy_loan_date_group').hide();
            $('#ins_policy_bal_units_group').hide();
            $('#ins_policy_bal_date_group').hide();
            $('#ins_policy_cur_value_group').hide();
            $('#ins_policy_exp_maturity_value_group').hide();
        
            $('#ins_policy_nominee_name_group').hide();
            $('#ins_policy_nominee_relation_group').hide();
        
            $('#ins_policy_plan_type_group').hide();
            $('#ins_policy_term_years_group').hide();
            $('#ins_policy_premium_pay_term_years_group').hide();
            $('#ins_policy_money_back_group').hide();
        }
        function insuranceData() {
            $.ajax({
                url: "api_insurance",
                type: "GET",
                success: function(response) {
                    // var queryArr = JSON.parse(response);
                    // console.log(JSON.parse(response));
                    $('#insurance_table').DataTable().destroy();
                    oTable = $('#insurance_table').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        "buttons": ["copy", "csv", "excel", "pdf", "print"],
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.ins_prod_type; } }, 
                            { data : null, render: function (data, type, row) { return row.cust_name; } }, 
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
                                                    temp = temp + "<p style='margin: 0rem;'><a href='https://admin.optymoney.com/itr/getfile/"+row.ins_cust_id+"/insurance/"+item+"'>"+item+"</a></p>";
                                                    temp1 = temp1 + "<p style='margin: 0rem;'>"+item+"</p>";
                                                }
                                            });
                                        } else {
                                            temp = "<a href='https://admin.optymoney.com/itr/getfile/"+row.ins_cust_id+"/insurance/"+row.ins_policy_document+"'>"+row.ins_policy_document+"</a>";
                                            temp1 = "<p style='margin: 0rem;'>"+row.ins_policy_document+"</p>";
                                        }
                                        var ulevel = atob((CryptoJS.AES.decrypt(sessionStorage.getItem("user_level"),"/")).toString(CryptoJS.enc.Utf8));
                                        if(ulevel=="superadmin") {
                                            return temp;
                                        } else {
                                            if(ulevel=="admin") {
                                                return temp;
                                            } else {
                                                if(ulevel=="manager") {
                                                    return temp;
                                                } else {
                                                    if(ulevel=="operation") {
                                                        return temp1;
                                                    } else {
                                                        return '';
                                                    }
                                                }  
                                            }    
                                        }
                                    }
                                }
                            }, 
                            { data : null, render: function (data, type, row) { 
                                var ulevel = atob((CryptoJS.AES.decrypt(sessionStorage.getItem("user_level"),"/")).toString(CryptoJS.enc.Utf8));
                                if(ulevel=="superadmin") {
                                    return '<div class="btn-group"> <button type="button" class="btn btn-warning insEdit" data-id="' + row.ins_id + '"><i class="fas fa-edit"></i></button><button type="button" class="btn btn-danger insDelete" data-id="' + row.ins_id + '"><i class="fas fa-trash"></i></button> </div>'; 
                                } else {
                                    if(ulevel=="admin") {
                                        return '<div class="btn-group"> <button type="button" class="btn btn-warning insEdit" data-id="' + row.ins_id + '"><i class="fas fa-edit"></i></button><button type="button" class="btn btn-danger insDelete" data-id="' + row.ins_id + '"><i class="fas fa-trash"></i></button> </div>'; 
                                    } else {
                                        if(ulevel=="manager") {
                                            return '<div class="btn-group"> <button type="button" class="btn btn-warning insEdit" data-id="' + row.ins_id + '"><i class="fas fa-edit"></i></button></div>'; 
                                        } else {
                                            if(ulevel=="operation") {
                                                return '';
                                            } else {
                                                return '';
                                            }
                                        }  
                                    }    
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