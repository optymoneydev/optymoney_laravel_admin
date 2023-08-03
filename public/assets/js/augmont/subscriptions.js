var parsedJson;
$(document).ready(function() {

    $(document).on('click','.cancelSub',function(e) {
        $.ajax({
            type: "POST",
            url: "/augmont/stopSubscription",
            data: {"sub_id" : $(this).data("id")},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                location.reload();
                // console.log(data);
            },
            error: function (e) {
                console.log("ERROR : ", e);
            }
        });
    });
    orders();
    
    $('#server-side-datatable').DataTable();
    function orders() {
        $.ajax({
            type: 'GET',
            data: '',
            url: "../augmont/sipList",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data);
                $('#server-side-datatable').DataTable().destroy();
                oTable = $('#server-side-datatable').DataTable({
                    "paging": true,
                    "searching": true,
                    "order": [[ 0, "desc" ]],
                    "ordering": true,
                    "info": true,
                    "responsive": true, 
                    "lengthChange": false, 
                    "autoWidth": true,
                    "scrollX": true,
                    "data" : data,
                    "columns" : [
                        { data : null, render: function (data, type, row) { return row.subscription_plan; } },  
                        { data : null, render: function (data, type, row) { return row.total_count; } }, 
                        { data : null, render: function (data, type, row) { return row.paid_count; } }, 
                        { data : null, render: function (data, type, row) { return row.remaining_count; } }, 
                        { data : null, render: function (data, type, row) { 
                            var unixTimeStamp = row.charge_at;
                            var timestampInMilliSeconds = unixTimeStamp*1000;
                            var date = new Date(timestampInMilliSeconds);
                            var formattedDate = [date.getMonth() + 1, date.getDate(), date.getFullYear()].join('/');
                            // console.log(formattedDate);
                            return formattedDate;
                        } }, 
                        { data : null, render: function (data, type, row) { return  capitalizeFirstLetter(row.status); } }, 
                        { data : null, render: function (data, type, row) { 
                            if(row.status=="inactive") {
                                return "";
                            } else {
                                return "<button data-id='"+row.id+"' class='btn btn-xs btn-danger pull-right cancelSub'>STOP</button>"; 
                            }
                        } }
                    ],
                    "fnInitComplete": function() { $("#server-side-datatable").css("width","100%"); }
                });
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    }

    
  
});