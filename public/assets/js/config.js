var primary = localStorage.getItem("primary") || '#7366ff';
var secondary = localStorage.getItem("secondary") || '#f73164';

if(localStorage.getItem("empMenu")=="") {
    getEmployeeRole();
} else {
    var roles = JSON.parse(localStorage.getItem("empMenu"));
    var mainMenuName = $('.sidebar-submenu li a.active').closest('.sidebar-list').find('.sidebar-title.active').text().toString().replace(/\t/g, '').replace(/\n/g, '');
    var subMenuName = $('.sidebar-submenu li a.active').text();
    var menuRole = roles[mainMenuName][subMenuName].split(',');
    if(jQuery.inArray("create", menuRole) != -1) {
        var buttons = [{text: 'New Form', action: function ( e, dt, node, config ) { $('#newForm').modal("show");} },'copyHtml5','excelHtml5','csvHtml5','pdfHtml5'];
    } else {
        var buttons = ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5'];
    }
    var editOption = false;
    if(jQuery.inArray("write", menuRole) != -1) {
        editOption = true;
    } else {
        editOption = false;
    }

    var viewOption = false;
    if(jQuery.inArray("read", menuRole) != -1) {
        viewOption = true;
    } else {
        viewOption = false;
    }

    var deleteOption = false;
    if(jQuery.inArray("delete", menuRole) != -1) {
        deleteOption = true;
    } else {
        deleteOption = false;
    }
}
menuUpdate();
window.CubaAdminConfig = {
	// Theme Primary Color
	primary: primary,
	// theme secondary color
	secondary: secondary,
};

const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'INR',
    minimumFractionDigits: 2
});



function createNoty(message, type) {
    var html = '<div class="alert alert-' + type + ' alert-dismissable page-alert">';    
    html += '<button type="button" class="close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>';
    html += message;
    html += '</div>';    
    $(html).hide().prependTo('#noty-holder').slideDown();
};

function formatDate(dateVal) {
    var newDate = new Date(dateVal);

    var sMonth = padValue(newDate.getMonth() + 1);
    var sDay = padValue(newDate.getDate());
    var sYear = newDate.getFullYear();
    var sHour = newDate.getHours();
    var sMinute = padValue(newDate.getMinutes());
    var sAMPM = "AM";

    var iHourCheck = parseInt(sHour);

    if (iHourCheck > 12) {
        sAMPM = "PM";
        sHour = iHourCheck - 12;
    }
    else if (iHourCheck === 0) {
        sHour = "12";
    }

    sHour = padValue(sHour);

    return sMonth + "-" + sDay + "-" + sYear + " " + sHour + ":" + sMinute + " " + sAMPM;
}

function formatOnlyDate(dateVal) {
    var newDate = new Date(dateVal);

    var sMonth = padValue(newDate.getMonth() + 1);
    var sDay = padValue(newDate.getDate());
    var sYear = newDate.getFullYear();
    var sHour = newDate.getHours();
    var sMinute = padValue(newDate.getMinutes());
    var sAMPM = "AM";

    var iHourCheck = parseInt(sHour);

    if (iHourCheck > 12) {
        sAMPM = "PM";
        sHour = iHourCheck - 12;
    }
    else if (iHourCheck === 0) {
        sHour = "12";
    }

    sHour = padValue(sHour);

    return sMonth + "-" + sDay + "-" + sYear;
}

function padValue(value) {
    return (value < 10) ? "0" + value : value;
}

function capitalizeFirstLetter(string) {
    if(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    } else {
        return "";
    }
}

function menuUpdate() {
    var menuitems = JSON.parse(localStorage.getItem("empMenu"));
    $(".sidebar-list").each(function() {
        if($(this).find('ul').length>0) {
            var menu = $(this).find('a .lan-3').text();
            if(menuitems.hasOwnProperty(menu)) {
                console.log("yes");
            } else {
                $(this).hide();
            }
        }
    });
    $('.sidebar-submenu li').each(function() {
        var parent = $(this).parent().parent().find('a .lan-3').text();
        var child = $(this).text().replace(/\t/g, '').replace(/\n/g, '');
        if(menuitems[parent].hasOwnProperty(child)) {

        } else {
            $(this).hide();
        }
    });
}

function getEmployeeRole() {
    $.ajax({
        type: 'GET',
        data: '',
        url: "../hr/getEmployeeRole",
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            var l = data.roles.split(",");
            var mainmenu = [];
            jsonObj = {};
            var x = {};
            l.forEach(function(item) {
                var i = item.split("_");
                let myArray = [];
                if (jsonObj.hasOwnProperty(i[0].replace(/-/g, ' '))) {
                    if(jsonObj[i[0].replace(/-/g, ' ')].hasOwnProperty(i[1].replace(/-/g, ' '))) {
                        myArray = jsonObj[i[0].replace(/-/g, ' ')][i[1].replace(/-/g, ' ')].split(', ');
                        myArray.push(i[2]);
                        x[i[1].replace(/-/g, ' ')] = myArray.toString();
                    } else {
                        myArray.push(i[2]);
                        x[i[1].replace(/-/g, ' ')] = myArray.toString();
                    }
                } else {
                    myArray.push(i[2]);
                    x = {};
                    x[i[1].replace(/-/g, ' ')] = myArray.toString();
                }
                jsonObj[i[0].replace(/-/g, ' ')] = x;
            });
            localStorage.setItem("empMenu", JSON.stringify(jsonObj));
            menuUpdate();
        },
        error: function() {
            console.log("wrong...!!!");
        }
    });
}