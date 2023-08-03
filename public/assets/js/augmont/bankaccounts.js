var parsedJson;
$(document).ready(function() {

    bankAccounts();
    
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
                
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }
});