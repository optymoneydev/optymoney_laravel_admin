"use strict";
setTimeout(function(){
        (function($) {
            "use strict";
            var urlpath = window.location.pathname;
            if(window.location.href.indexOf("empCard") > -1) {
                console.log("emp cards");
            } else {
                if(window.location.href.indexOf("empCustCards") > -1) {
                    console.log("empCustCards");
                    $('#empCustTable').DataTable( {
                        dom: 'Bfrtip',
                        order: false,
                        buttons: false
                    } );
                }
            }
            if(jQuery.inArray("create", menuRole) != -1) {
                $('#newFormButton').show();
            } else {
                $('#newFormButton').hide();
            }
            rolesData();
            getEmployeesData();
            $('#newEmployeeForm').submit(function (e) {
                var formData = new FormData($(this)[0]);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'newEmployee',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            $('#exampleModalCenter').hide();
                            $('#newEmployeeForm')[0].reset();
                            $('#newForm').modal('hide');
                            getEmployeesData();
                            createNoty('Employee Created Successfully', 'success');
                        } else {
                            createNoty('Employee Creation Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#updateEmployeeForm').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append("eid", $('#emp_id').val());
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updateEmployeeForm',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            createNoty('Employee Created Successfully', 'success');
                        } else {
                            createNoty('Employee Creation Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#updatePersonal').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append("eid", $('#emp_id').val());
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updatePersonal',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            createNoty('Employee Personal Details Updated Successfully', 'success');
                        } else {
                            createNoty('Employee Personal Details Update Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#updateDocuments').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append("eid", $('#emp_id').val());
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updateDocuments',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            createNoty('Employee Documents Updated Successfully', 'success');
                        } else {
                            createNoty('Employee Documents Updated Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#updateOfficial').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append("eid", $('#emp_id').val());
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updateOfficial',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            createNoty('Employee Official Details Updated Successfully', 'success');
                        } else {
                            createNoty('Employee Official Details Update Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#updateBank').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append("eid", $('#emp_id').val());
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updateBank',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            createNoty('Employee Bank Updated Successfully', 'success');
                        } else {
                            createNoty('Employee Bank Update Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#updateAddress').submit(function (e) {
                var formData = new FormData($(this)[0]);
                formData.append("eid", $('#emp_id').val());
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'updateAddress',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            createNoty('Employee Address Updated Successfully', 'success');
                        } else {
                            createNoty('Employee Address Update Failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

            $('#addEmpCustMap').submit(function (e) {
                var formData = new FormData($(this)[0]);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'addEmpCustMap',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response==1) {
                            $('#addEmpCustMap')[0].reset();
                            createNoty('Employee assigned successfully', 'success');
                        } else {
                            createNoty('Employee assign failed', 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });

        })(jQuery);

        function rolesData() {
            $.ajax({
                url: "https://admin.optymoney.com/hr/getEmployeeRoles",
                type: "GET",
                success: function(response) {
                    $('#role').empty();
                    var select = document.getElementById("role");
                    var option = document.createElement("option");
                    option.text = "Select Role";
                    option.value = "";
                    select.add(option);
                    response.forEach(function(item) {
                        var option = document.createElement("option");
                        option.text = item.roleName;
                        option.value = item.id;
                        select.add(option);
                    });
                }
            });
        }

        function getEmployeesData() {
            $.ajax({
                url: "/hr/empCardsData",
                type: "GET",
                beforeSend: function() {
                    $(".loader-wrapper").show();
                },
                complete: function(){
                    $(".loader-wrapper").hide();
                },
                success: function(response) {
                    console.log(response);
                    $('#export-button').DataTable().destroy();
                    oTable = $('#export-button').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": true,
                        "scrollX": true,
                        "buttons": buttons,
                        dom: 'Bfrtip',
                        order: false,
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.emp_no; } }, 
                            { data : null, render: function (data, type, row) { return row.full_name; } }, 
                            { data : null, render: function (data, type, row) { return row.designation; } }, 
                            { data : null, render: function (data, type, row) { return row.department; } }, 
                            { data : null, render: function (data, type, row) { return row.doj; } }, 
                            { data : null, render: function (data, type, row) { return row.dob; } }, 
                            { data : null, render: function (data, type, row) { return row.personal_mobile; } }, 
                            { data : null, render: function (data, type,row) { 
                                var viewDiv = '<div class="m-b-30"><div class="btn-group" role="group" aria-label="Button group with nested dropdown"><div class="btn-group" role="group"><button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                                if(viewOption) {
                                    viewDiv = viewDiv+'<a href="hr/empCard'+row.pk_emp_id+'/view" class="dropdown-item">View</a>';
                                }
                                if(editOption) {
                                    viewDiv = viewDiv+'<a href="hr/empCard/"'+row.pk_emp_id+'/edit" class="dropdown-item">Edit</a>';
                                }
                                viewDiv = viewDiv+'</div></div></div></div>';
                                if(viewOption == false && editOption == false) {
                                    return ""; 
                                } else {
                                    return viewDiv; 
                                }
                            }
                            // <a href="clientCard/'+row.fr_user_id+'/edit" class="dropdown-item">Edit</a><a href="clientCard/'+row.fr_user_id+'/edit" class="dropdown-item">Delete</a>
                        }],
                        "fnInitComplete": function() { $("#export-button").css("width","100%"); }
                    }).buttons().container().appendTo('#export-button_wrapper .col-md-6:eq(0)');
                    oTable = $('#export-button').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }   
    }
,350);