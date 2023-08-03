"use strict";
setTimeout(function(){
    var oTable;
    var iTableCounter = 1;
    var oInnerTable;
    var detailsTableHtml;
    var siteurl;
    var emailFormats = {};
    var bulkemail_table = $('#export-button-bulkemails').DataTable({"lengthChange": false});
    (function($) {
            
        emailformatData();
    
        $("#bm_email_send").click(function (e) {
            e.preventDefault();
            var temp = new FormData();
            $("#sendBulkEmail input").each(function() {
                if(this.type=="file") {
                    temp.append(this.name, this.files[0]);
                } else {
                    temp.append(this.name, this.value);
                }
            });
            $("#sendBulkEmail select").each(function() {
                temp.append(this.name, this.value);
            });
            temp.append("bm_emails", $('#bm_emails').val());
            temp.append("bm_content", $('#emailContentData').html());
            temp.append("action", "bulkemail");

            $.ajax({
                type: "POST",
                url: 'sendBulkEmails',
                data: temp, // serializes the form's elements.
                cache: false,
                processData: false,
                contentType: false,
                success: function(res) {
                    bulkemail_table.rows().remove().draw();
                    if(res.status_code == 201) {
                        JSON.parse(res.failureMails).forEach(function(item) {
                            bulkemail_table.row.add( [item.split("-")[0], "FAILURE"] ).draw();
                        });
                        JSON.parse(res.successMails).forEach(function(item) {
                            bulkemail_table.row.add( [item.split("-")[0], "SUCCESS"] ).draw();
                        });
                    } else {
                        alert("Failed Sending. Try again")
                    }
                    
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                }  
            });
        });
        $("#clearBulkEmail").click(function (e) {
            e.preventDefault();
            bulkemail_table.clear().draw();
        });
        $('#bm_emailformat').change(function () {
            $('#emailContentData').empty();
            var optionSelected = $(this).find("option:selected");
            $('#emailContentData').html(emailFormats[optionSelected.val()]);
            CKEDITOR.inlineAll();
        });
    })(jQuery);

    function emailformatData() {
        $.ajax({
            url: "../cms/getEmailFormat",
            type: "GET",
            success: function(response) {
                var queryArr = JSON.parse(response.emailsformats);
                $('#bm_emailformat').empty().append('<option selected="selected" value="0">Select Format</option>');
                $.each(JSON.parse(response.emailsformats), function (key, val) {
                    if(val.emailformat_template_choose=="no") {
                    emailFormats[val.emailformat_id] = val.emailformat_content_manual;
                    } else {
                    emailFormats[val.emailformat_id] = val.emailformat_content;
                    }
                    $('#bm_emailformat').append(new Option(val.emailformat_name, val.emailformat_id));
                });
            }
        });
    }
}, 350);