"use strict";
setTimeout(function(){
    var oTable;
    investmentInterestData();
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
        
        function investmentInterestData() {
            
            $.ajax({
                url: APP_URL+"/crm/investmentInterestData",
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
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        dom: 'Bfrtip',
                        "data" : response,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return row.cust_name; } }, 
                            { data : null, render: function (data, type, row) { return row.email; } }, 
                            { data : null, render: function (data, type, row) { return row.contact_no; } }, 
                            { data : null, render: function (data, type, row) { return row.interestOn; } }, 
                            { data : "created_date", render: function (data, type, row, meta) { 
                                if (type === "sort" || type === 'type') {
                                    return data;
                                }
                                if (type === "display") {
                                    return formatOnlyDate(new Date(data));
                                }
                                return data;
                            } },
                        ],
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