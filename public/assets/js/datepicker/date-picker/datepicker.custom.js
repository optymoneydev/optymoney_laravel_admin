"use strict";
(function($) {
    "use strict";
    var maxBirthdayDate = new Date();
    maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18,11,31);
    var afterSeven = new Date();
    afterSeven.setDate(afterSeven.getDate() + 7)
//Minimum and Maxium Date
    // $('#minMaxExample').datepicker({
    //     language: 'en',
    //     maxDate: maxBirthdayDate // Now can select only dates, which goes after today
    // })

    $('#dob').datepicker({
        language: 'en'
    })
    $('#panDOB').datepicker({
        language: 'en',
        maxDate: maxBirthdayDate // Now can select only dates, which goes after today
    })
    $('#nominee_dob').datepicker({
        language: 'en',
        // maxDate: maxBirthdayDate // Now can select only dates, which goes after today
    })
    $('#silverSipDate').datepicker({
        language: 'en',
        minDate: afterSeven // Now can select only dates, which goes after today
    })
    $('#goldSipDate').datepicker({
        language: 'en',
        minDate: afterSeven // Now can select only dates, which goes after today
    })
    

//Disable Days of week
    var disabledDays = [0, 6];

    $('#disabled-days').datepicker({
        language: 'en',
        onRenderCell: function (date, cellType) {
            if (cellType == 'day') {
                var day = date.getDay(),
                    isDisabled = disabledDays.indexOf(day) != -1;
                return {
                    disabled: isDisabled
                }
            }
        }
    })
})(jQuery);