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
    states();
    usersData();
        (function($) {
            "use strict";
            // if($('#deprtJS').text() == "partner") {
                
            // }
            $(document).ajaxSend(function() {
                $(".loader-wrapper").show();
            });
            $(document).on('click', '.re_evaluate_cams', function (e) {
                e.preventDefault();
                console.log($(this).attr('data-pan'));
                var settings = {
                    "url": "https://admin.optymoney.com/api/mf/cams/camsByPAN",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                      "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({
                      "0": $(this).attr('data-pan')
                    }),
                  };
                  
                  $.ajax(settings).done(function (response) {
                    console.log(response);
                    $(".loader-wrapper").hide();
                  });
            });

            $(document).on('click', '.re_evaluate_karvy', function (e) {
                e.preventDefault();
                console.log($(this).attr('data-pan'));
                var settings = {
                    "url": "https://admin.optymoney.com/api/mf/karvy/karvyByPAN",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                      "Content-Type": "application/json"
                    },
                    "data": JSON.stringify({
                      "0": $(this).attr('data-pan')
                    }),
                  };
                  
                  $.ajax(settings).done(function (response) {
                    console.log(response);
                  });
            });
        })(jQuery);
        function states() {
            var titelliste = [
                { "id": "joXp8X42", "name": "Andaman and Nicobar" },
                { "id": "o59RxqAQ", "name": "Andhra Pradesh" },
                { "id": "KR9akqW1", "name": "Arunachal Pradesh" },
                { "id": "aBqv490L", "name": "Assam" },
                { "id": "Pa7zeqvV", "name": "Bihar" },
                { "id": "Kd9BkXpM", "name": "Chandigarh" },
                { "id": "1LqK87VP", "name": "Chhattisgarh" },
                { "id": "QD9xRq5g", "name": "Dadra & Nagar Haveli" },
                { "id": "vk9G5qM3", "name": "Daman and Diu" },
                { "id": "bv9Z17l0", "name": "Delhi" },
                { "id": "0VX6A9LZ", "name": "Goa" },
                { "id": "B1qVZqPG", "name": "Gujarat" },
                { "id": "eE72O9nm", "name": "Haryana" },
                { "id": "awXwn9lA", "name": "Himachal Pradesh" },
                { "id": "xEq3d7Lo", "name": "Jammu and Kashmir" },
                { "id": "Do7Wjq3d", "name": "Jharkhand" },
                { "id": "eyqMQqYd", "name": "Karnataka" },
                { "id": "62Xg57W0", "name": "Kerala" },
                { "id": "gyqOP7mY", "name": "Lakshadweep" },
                { "id": "JyX5zqMW", "name": "Madhya Pradesh" },
                { "id": "ep9kJ7Px", "name": "Maharashtra" },
                { "id": "J271B9aj", "name": "Manipur" },
                { "id": "Be9AP72w", "name": "Meghalaya" },
                { "id": "ZVXe5Xov", "name": "Mizoram" },
                { "id": "BJXdYqYZ", "name": "Nagaland" },
                { "id": "AR7YPqDj", "name": "Orissa" },
                { "id": "LQ78NXmy", "name": "Puducherry" },
                { "id": "WV906qDv", "name": "Punjab" },
                { "id": "PJ7nDXlY", "name": "Rajasthan" },
                { "id": "YO9jE73B", "name": "Sikkim" },
                { "id": "mVqoM9DM", "name": "Tamil Nadu" },
                { "id": "zy94Vq4k", "name": "Telangana" },
                { "id": "1GXDR72L", "name": "Tripura" },
                { "id": "eN9bY7Do", "name": "Uttarakhand" },
                { "id": "Q27L87bD", "name": "Uttar Pradesh" },
                { "id": "wk9PrqnK", "name": "West Bengal" },
                { "id": "lk7J5qPr", "name": "Ladakh" }
            ];
            $('#state').empty();
            var select = document.getElementById("state");
                var option = document.createElement("option");
                option.text = "Select State";
                option.value = "";
                select.add(option);
            for (var i = 0; i < titelliste.length; i++) {
                var select = document.getElementById("state");
                var option = document.createElement("option");
                option.text = titelliste[i].name;
                option.value = titelliste[i].id;
                select.add(option);
            }
        }

        function usersData() {
            
            $.ajax({
                url: APP_URL+"/crm/empclients",
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
                        "buttons": [
                            {
                                text: 'New Form',
                                action: function ( e, dt, node, config ) {
                                    $('#userDisplayForm_modal').modal("show");
                                }
                            },
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        dom: 'Bfrtip',
                        order: [[6, 'desc']],
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.cust_name; } }, 
                            { data : null, render: function (data, type, row) { return row.login_id; } }, 
                            { data : null, render: function (data, type, row) { return row.pan_number; } }, 
                            { data : null, render: function (data, type, row) { return row.contact_no; } }, 
                            { data : null, render: function (data, type, row) { return row.user_status; } }, 
                            { data : null, render: function (data, type, row) { return row.created_from; } }, 
                            { data : "created_date", render: function (data, type, row, meta) { 
                                if (type === "sort" || type === 'type') {
                                    return data;
                                }
                                if (type === "display") {
                                    return formatOnlyDate(new Date(data));
                                }
                                return data;
                            } },
                            { data : null, render: function (data, type,row) { 
                                return '<div class="m-b-30"><div class="btn-group" role="group" aria-label="Button group with nested dropdown"><div class="btn-group" role="group"><button class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a href="clientCard/'+row.fr_user_id+'/view" class="dropdown-item">View</a><a href="#" data-pan="'+row.pan_number+'" class="dropdown-item re_evaluate_cams">Re Evaluate CAMS</a><a href="#" data-pan="'+row.pan_number+'" class="dropdown-item re_evaluate_karvy">Re Evaluate KARVY</a></div></div></div></div>'; 
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

$('#userForm_modal').submit(function (e) {
    var formData = new FormData($(this)[0]);
    var stateName = $( "#state option:selected" ).text();
    var cityName = $( "#city option:selected" ).text();
    formData.append("stateName", stateName);
    formData.append("cityName", cityName);
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: 'saveUser',
        data: formData, // serializes the form's elements.
        cache: false,
        processData: false,
        contentType: false,
        success: function(res) {
            console.log(res);
            if(res['status_code']==200 || res['status_code']==201) {
                $('#userForm_modal')[0].reset();
                $('#userDisplayForm_modal').modal('hide');
                createNoty(res['message'], 'success');
                faqData();
            } else {
                createNoty(response['message'], 'danger');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
        }  
    });
});


$('#state').on('change', function() {
    var state = this.value;
    var settings = {
        "url": "https://admin.optymoney.com/api/customer/getCity",
        "method": "POST",
        "headers": {
            "Accept": "application/json",
        },
        "data": { "state": state },
        "async": false
    };
    $.ajax(settings).done(function (data) {
        var data_list = JSON.parse(data);
        var city_list = data_list.result.data;
        for (var i = 0; i < city_list.length; i++) {
            var select = document.getElementById("city");
            var option = document.createElement("option");
            option.text = city_list[i].name;
            option.value = city_list[i].id;
            select.add(option);
        }
    }).fail(function(data){
        errorcode = 0;
        console.log(data);
    });
});