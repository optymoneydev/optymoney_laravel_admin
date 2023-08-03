    var titelliste = [
        { "id": "joXp8X42", "name": "Andaman and Nicobar" },
        { "id": "o59RxqAQ", "name": "Andhra Pradesh" },
        { "id": "KR9akqW1", "name": "Arunachal Pradesh" },
        { "id": "aBqv490L", "name": "Assam" },
        { "id": "Pa7zeqvV", "name": "Bihar" },
        { "id": "Kd9BkXpM", "name": "Chandigarh" },
        { "id": "1LqK87VP", "name": "Chhattisgarh" },
        { "id": "QD9xRq5g", "name": "Dadra & Nagar Haveli" },
        { "id": "vk9G5qM3", "name": "Daman and Diu" },
        { "id": "bv9Z17l0", "name": "Delhi" },
        { "id": "0VX6A9LZ", "name": "Goa" },
        { "id": "B1qVZqPG", "name": "Gujarat" },
        { "id": "eE72O9nm", "name": "Haryana" },
        { "id": "awXwn9lA", "name": "Himachal Pradesh" },
        { "id": "xEq3d7Lo", "name": "Jammu and Kashmir" },
        { "id": "Do7Wjq3d", "name": "Jharkhand" },
        { "id": "eyqMQqYd", "name": "Karnataka" },
        { "id": "62Xg57W0", "name": "Kerala" },
        { "id": "gyqOP7mY", "name": "Lakshadweep" },
        { "id": "JyX5zqMW", "name": "Madhya Pradesh" },
        { "id": "ep9kJ7Px", "name": "Maharashtra" },
        { "id": "J271B9aj", "name": "Manipur" },
        { "id": "Be9AP72w", "name": "Meghalaya" },
        { "id": "ZVXe5Xov", "name": "Mizoram" },
        { "id": "BJXdYqYZ", "name": "Nagaland" },
        { "id": "AR7YPqDj", "name": "Orissa" },
        { "id": "LQ78NXmy", "name": "Puducherry" },
        { "id": "WV906qDv", "name": "Punjab" },
        { "id": "PJ7nDXlY", "name": "Rajasthan" },
        { "id": "YO9jE73B", "name": "Sikkim" },
        { "id": "mVqoM9DM", "name": "Tamil Nadu" },
        { "id": "zy94Vq4k", "name": "Telangana" },
        { "id": "1GXDR72L", "name": "Tripura" },
        { "id": "eN9bY7Do", "name": "Uttarakhand" },
        { "id": "Q27L87bD", "name": "Uttar Pradesh" },
        { "id": "wk9PrqnK", "name": "West Bengal" },
        { "id": "lk7J5qPr", "name": "Ladakh" }
    ];

    $('#state').empty();
    var select = document.getElementById("state");
    var option = document.createElement("option");
    option.text = "Select State";
    option.value = "";
    select.add(option);
    for (var i = 0; i < titelliste.length; i++) {
        var option = document.createElement("option");
        option.text = titelliste[i].name;
        option.value = titelliste[i].id;
        select.add(option);
    }

    $('#state').on('change', function() {
        var state = this.value;
        $.ajax({
            type: 'POST',
            data: { "state": state },
            url: "../augmont/getCity",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                var data_list = JSON.parse(data);
                var city_list = data_list.result.data;
                for (var i = 0; i < city_list.length; i++) {
                    var select = document.getElementById("city");
                    var option = document.createElement("option");
                    option.text = city_list[i].name;
                    option.value = city_list[i].id;
                    select.add(option);
                }
            },
            error: function() {
                console.log("wrong...!!!");
            }
        });
    });
    