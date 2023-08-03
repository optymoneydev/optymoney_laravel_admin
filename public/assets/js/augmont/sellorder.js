$(document).ready(function() {
    $('#newbankDetails').hide();
    $('#urlIframe').hide();
    bankAccounts();

    $('input:radio[name="userbank"]').change(function(){
        if ($(this).is(':checked') && $(this).val() == 'newBank') {
            $('#newbankDetails').show();
        } else {
            $('#bank_name').val('');
            $('#acc_no').val('');
            $('#ifsc_code').val('');
            $('#newbankDetails').hide();
        }
    });
    
    $("#proceedToSell").click(function(){
        var form = $('#saveSellOrderForm')[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "saveSellOrder",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                var displayRes = "";
                if(data.augstatusCode==422) {
                    if(data.hasOwnProperty('errors')){
                        $.each(data.errors, function(key,val){
                            if(key.includes("userBank")){
                                displayRes = displayRes + "<br>Bank Account Issues<br>";
                            } else{
                                displayRes = displayRes + "<br>" + key + "<br>";
                            }
                            $.each(val, function(index,jsonObject){
                                displayRes = displayRes + "<br>" + jsonObject.message;
                            });
                        });
                        $('#sellStatusMsg').html('<div class="alert alert-danger dark alert-dismissible fade show" role="alert"><strong>'+displayRes+'</strong><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button></div>'); 
                    }
                } else {
                    if(data.augstatusCode==200) {
                        $('#saveOrderCard').hide();
                        
                        $('#sellStatusMsg').html('<div class="alert alert-success dark alert-dismissible fade show" role="alert"><strong>'+data.augResponse.message+'</strong><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button></div>');
                    } else {
                        if(data.augstatusCode==1002) {
                            $('#sellStatusMsg').html('<div class="alert alert-success dark alert-dismissible fade show" role="alert"><strong>'+data.message+'</strong><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button></div>');
                        }
                    }
                }
            },
            error: function (e) {
                console.log("ERROR : ", e);
            }
        });
    });

    function bankAccounts() {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../users/allUserBanks",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
                $("#banksList").empty();
                $.each(data, function(i, obj) {
                    $("#banksList").append('<div class="input-container"><input id="'+obj.acc_no+'" value="'+obj.pk_bank_detail_id+'" class="radio-button" type="radio" name="userbank" required="required" /><div class="radio-tile"><div class="icon walk-icon">'+obj.bank_name+'</div><label for="walk" class="radio-tile-label">'+obj.acc_no+'</label></div></div>');
                });
                $("#banksList").append('<div class="input-container"><input id="newBank" value="newBank" class="radio-button" type="radio" name="userbank" required="required" /><div class="radio-tile"><div class="icon walk-icon"><img loading="lazy" class="images" alt="undefined" src="../assets/images/media/icons/net-banking.svg"></div><label for="walk" class="radio-tile-label">Add New</label></div></div>');
                
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }
});