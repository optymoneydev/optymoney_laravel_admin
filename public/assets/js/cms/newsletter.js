"use strict";
setTimeout(function(){
        var oTable;
        (function($) {
            "use strict";
            newsletters();
            
            $('#addnewsletter').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: "POST",
                    url: 'saveNewsLetter',
                    data: formData, // serializes the form's elements.
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if(res['status_code']==200 || res['status_code']==201) {
                            $('#addnewsletter')[0].reset();
                            createNoty(res['message'], 'success');
                            newsletters();
                        } else {
                            createNoty(res['message'], 'danger');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        // alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }  
                });
            });
            
            $(document).on("click", ".newsletterEdit", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "newsLetterById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $.each(response, function (key, val) {
                            if(key=="id") {
                                $('#id').val(val);
                            } else {
                                $('#' + key).val(val);
                            }
                        });
                    }
                });
            });
            $(document).on("click", ".newsletterDelete", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "deleteNewsLetterById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res == true || res == 1) {
                            createNoty("Deleted Successfully", 'success');
                            newsletters();
                        } else {
                            createNoty("Deletion Failed. Please try again.", 'error');
                        }
                    }
                 });
            });
            
        })(jQuery);

        function newsletters() {
            $.ajax({
                url: "getNewsLetter",
                type: "GET",
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    $('#newslettersTable').DataTable().destroy();
                    oTable = $('#newslettersTable').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true,
                        "responsive": true, 
                        "lengthChange": false, 
                        "autoWidth": false,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        order: false,
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        "data" : res,
                        "columns" : [
                            { data : null, render: function (data, type, row) { return '<span style="white-space:normal">' + row.title + "</span>"; } }, 
                            { data : null, render: function (data, type, row) { return row.datetitle; } }, 
                            { data : null, render: function (data, type, row) { return row.pdfdocument; } }, 
                            { data : null, render: function (data, type,row) { 
                                return '<div class="btn-group"><button type="button" class="btn btn-warning newsletterEdit" data-id="' + row.id + '"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger newsletterDelete" data-id="' + row.id + '"><i class="fa fa-trash"></i></button> </div>'; 
                            }
                        }],
                        "fnInitComplete": function() { $("#newslettersTable").css("width","100%"); }
                    }).buttons().container().appendTo('#enewslettersTable_wrapper .col-md-6:eq(0)');
                    oTable = $('#newslettersTable').DataTable();
                    // <button type="button" class="btn btn-info insView" data-id="' + row.ins_id + '"><i class="fas fa-eye"></i></button>
                }
            });
        }
    }
,350);