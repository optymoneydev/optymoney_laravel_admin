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
    }
,350);