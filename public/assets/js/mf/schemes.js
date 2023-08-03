"use strict";
setTimeout(function(){
        var oTable;
        (function($) {

            "use strict";
            getNavOffers();

            $('#abc').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                dropdownParent: $('#schemesView_modal')
            });

            $(".js-example-tags").select2({
                tags: true
              });

            $('.page-alert .close').click(function(e) {
                e.preventDefault();
                $(this).closest('.page-alert').slideUp();
            });

            var todayDate = new Date();
            var dob = new Date();
            dob.setFullYear( dob.getFullYear() - 18,11,31);
            
            $(document).on("click", ".schemeView", function (e) {
                e.preventDefault();
                var objData = {};
                objData["id"] = $(this).attr("data-id");
                $.ajax({
                    url: "getSchemeById",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        var scheme = response.scheme;
                        $('#schemesView_modal').modal('show');
                        $('#schemesViewTitle').html(scheme.scheme_name);
                        $('#sch_id').val(scheme.pk_nav_id)
                        // $('#schemesViewContent').html(response.scheme_name);
                    }
                });
            });	

            $("#sch_options").change(function(e){
                e.preventDefault();
                var objData = {};
                objData["sch_id"] = $('#sch_id').val();
                objData["options"] = $(this).val();
                $.ajax({
                    url: "getValuesByOptions",
                    type: "POST",
                    data: objData,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if(response.length!=0) {
                            $.each(response, function(i, item) {
                                $("#sch_val_List").append($("<option>").attr('value', i).text(item));
                            });
                        }
                    }
                });
            });

        })(jQuery);

        function getNavOffers() {
            $.ajax({
                url: "getNavOffers",
                type: "GET",
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if(response.length!=0) {
                        $.each(response, function(i, item) {
                            $("#sch_val_List").append($("<option>").attr('value', i).text(item));
                        });
                    }
                }
            });
        }
    }
,350);