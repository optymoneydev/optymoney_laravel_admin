"use strict";
setTimeout(function(){
        (function($) {
            "use strict";
            $('#itr_cust_id').select2({
                dropdownParent: $('#itrvForm_modal')
            });
            $('#itr_cust_id').on('change', function() {
                var selectedText = $(this).find("option:selected").text();
                const optionText = selectedText.split(" - ");
                $('#pancheck').val($.trim(optionText[0]));
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
                    success: function(res) {
                        if(res['status_code']==200) {
                            $('#addITRV')[0].reset();
                            $('#itrvForm_modal').modal('hide');
                            location.reload(true);
                            createNoty('ITRV Updated Successfully', 'success');
                        } else {
                            createNoty('ITRV Updation Failed. Please try again.', 'danger');
                        }
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
    }
,350);