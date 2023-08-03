$(document).ready(function() {
  $('#newbankDetails').hide();
    var pathname = window.location.pathname;
    var url = "createOrder";
    if(pathname=="/authentication/profileAddressUpdate") {
      var url = window.location.origin+"/augmont/createOrder";
    }
    $("#proceedToPay").click(function(){
        var form = $('#saveOrderForm')[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            dataType: 'json',
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                "Accept": "application/json"
            },
            success: function (data) {
              console.log(data);
                data.config = {
                    display: {
                      hide: [
                        {
                          method: 'paylater'
                        }, {
                            method: 'wallet'
                        }
                      ],
                      preferences: {
                        show_default_blocks: true,
                      },
                    },
                  };
                var rzp1 = new Razorpay(data);
                console.log("SUCCESS : ", data);
                rzp1.on('payment.failed', function (response){
                    alert(response.error.code+"<br>"+response.error.description+"<br>"+response.error.source+"<br>"+response.error.step+"<br>"+response.error.reason+"<br>"+response.error.metadata.order_id+"<br>"+response.error.metadata.payment_id);
                });
                rzp1.open();
            },
            error: function (e) {
              window.location.href = e.responseText;
                console.log("ERROR : ", e);
            }
        });
    });

    $("#proceedToPay_SIP").click(function(){
      var form = $('#saveOrderForm')[0];
      var data = new FormData(form);
      if($(location).attr('pathname').search("augmont")==-1) {
        var url = "augmont/createSipOrder";
      } else {
        var url = "createSipOrder";
      }

      $.ajax({
          type: "POST",
          dataType: 'json',
          enctype: 'multipart/form-data',
          url: url,
          data: data,
          processData: false,
          contentType: false,
          cache: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              "Accept": "application/json"
          },
          success: function (data) {
            console.log(data);
            // data.handler = function(response) {
            //   alert(response.razorpay_payment_id),
            //   alert(response.razorpay_subscription_id),
            //   alert(response.razorpay_signature);
            // };
              var rzp1 = new Razorpay(data);
              console.log("SUCCESS : ", data);
              rzp1.on('payment.failed', function (response){
                  alert(response.error.code+"<br>"+response.error.description+"<br>"+response.error.source+"<br>"+response.error.step+"<br>"+response.error.reason+"<br>"+response.error.metadata.order_id+"<br>"+response.error.metadata.payment_id);
              });
              rzp1.open();
          },
          error: function (e) {
            window.location.href = e.responseText;
              console.log("ERROR : ", e);
          }
      });
  });

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