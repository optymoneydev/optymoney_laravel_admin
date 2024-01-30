"use strict";
setTimeout(function(){
        var oTable;
        (function($) {
            $('#roleTitle').text("New Role");
            "use strict";
            rolesData();
            var menuArray = {};
            $(".sidebar-list").each(function() {
                if($(this).find('ul').length>0) {
                    var menu = $(this).find('a .lan-3').text();
                    // console.log("menu : "+menu);
                    var level2 = $(this).find('ul')[0];
                    if(level2.hasChildNodes('li')>0) {
                        var listItems = level2.children;
                        var submenu = [];
                        listItems.forEach(function(item) {
                            submenu.push(item.textContent.toString().replace(/\t/g, '').replace(/\n/g, ''));
                            // console.log("sub menu : "+item.textContent);
                        });
                        menuArray[menu] = submenu;
                    }
                }
            });
            $.each(menuArray, function(key, item){
                if(item.length>0) {
                    var i=0;
                    var keyName = "";
                    $.each(item, function(key1, item1){
                        if(i==0) {
                            keyName = key;
                        } else {
                            keyName = "";
                        }
                        var tr = '<tr><td class="text-gray-800">'+keyName+'</td><td class="text-gray-800">'+item1+'</td>'+
                            '<td><div class="d-flex">'+
                                    '<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">'+
                                        '<input class="form-check-input" type="checkbox" value="" name="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_read" id="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_read">'+
                                        '<span class="form-check-label">Read</span>'+
                                    '</label>'+
                                    '<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">'+
                                        '<input class="form-check-input" type="checkbox" value="" name="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_write" id="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_write">'+
                                        '<span class="form-check-label">Write</span>'+
                                    '</label>'+
                                    '<label class="form-check form-check-sm form-check-custom form-check-solid">'+
                                        '<input class="form-check-input" type="checkbox" value="" name="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_create" id="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_create">'+
                                        '<span class="form-check-label" "="">Create</span>'+
                                    '</label>'+
                                    '<label class="form-check form-check-sm form-check-custom form-check-solid">'+
                                        '<input class="form-check-input" type="checkbox" value="" name="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_create" id="'+key.replace(/ /g, '-')+'_'+item1.replace(/ /g, '-')+'_delete">'+
                                        '<span class="form-check-label" "="">Delete</span>'+
                                    '</label>'+
                                '</div></td></tr>';
                        i++;
                        $('#menuPermissionReportUI').append(tr);
                    });
                } else {
                    console.log("0 item");
                }
            });
            
            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
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

            $('#userRolesForm_modal').submit(function (e) {
                var selected = [];
                $('#userRolesForm_modal input[type=checkbox]:checked').each(function(){
                    selected.push($(this).attr('id'));
                });
                var d;
                e.preventDefault();
                if($('#id').val() == "") {
                    d = {roleName: $('#role_name').val(), menulist: selected.toString()};
                } else {
                    d = {roleName: $('#role_name').val(), menulist: selected.toString(), id: $('#id').val()};
                }
                $.ajax({
                    type: "POST",
                    url: 'saveUserRoles',
                    data: d,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response['status_code']==200 || response['status_code']==201) {
                            $('#userRolesForm_modal')[0].reset();
                            $('#newForm').modal('hide');
                            createNoty(response['message'], 'success');
                            rolesData();
                        } else {
                            createNoty(response['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $("#kt_roles_select_all").on("change", function(e) {
                console.log("selected");
                var selected = [];
                $('#userRolesForm_modal input:checkbox').not(this).prop('checked', this.checked);
            });

            $(document).on("click", ".roleEdit", function (e) {
                e.preventDefault();
                var custid = "";
                $.ajax({
                    url: "roleById",
                    type: "POST",
                    data: { "id": $(this).attr("data-id") },
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#newForm').modal('show');
                        $('#roleTitle').text("Edit Role");
                        $("#id").val(response.id);
                        $('#role_name').val(response.roleName);
                        const rolesArray = response.roles.split(",");
                        rolesArray.forEach(function(item) {
                            $('#'+item).prop('checked', true);
                        });
                    }
                 });
            });

            $(document).on("click", ".roleDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deleteRole",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () { $('.ajax-loader').css("visibility", "visible"); },
                    complete: function () { $('.ajax-loader').css("visibility", "hidden"); },
                    success: function (response) {
                        if(response['status_code']==200 || response['status_code']==201) {
                            createNoty(response['message'], 'success');
                            rolesData();
                        } else {
                            createNoty(response['message'], 'danger');
                        }
                    }
                 });
            });
        })(jQuery);

        function rolesData() {
            $.ajax({
                url: "getEmployeeRoles",
                type: "GET",
                success: function(response) {
                    $('#usrmngtRolesTable').DataTable().destroy();
                    oTable = $('#usrmngtRolesTable').DataTable({
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
                            { data : null, render: function (data, type, row) { return row.roleName; } }, 
                            { data : null, render: function (data, type, row) { return row.roles; } }, 
                            { data : null, render: function (data, type, row) { return row.created_by; } }, 
                            { data : null, render: function (data, type, row) { return row.updated_at; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group btn-group-square" role="group" aria-label="Basic example">'+
                                '<button class="btn btn-primary roleEdit" data-id="'+row.id+'" type="button">Edit</button>'+
                                '<button class="btn btn-primary roleDelete" data-id="'+row.id+'" type="button">Delete</button>';
                            }
                        }],
                        "fnInitComplete": function() { $("#usrmngtRolesTable").css("width","100%"); }
                    }).buttons().container().appendTo('#usrmngtRolesTable_wrapper .col-md-6:eq(0)');
                    // oTable = $('#usrmngtRolesTable').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);