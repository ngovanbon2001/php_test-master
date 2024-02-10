<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <form id="chooseDishForm" method="POST" action="{{ route('order') }}">
        @csrf
        <div class="tab" id="tab-meal">
            <div class="row row-group">
                <div class="control-group col-md-6">
                    <label class="control-label">Please Select a meal <span style="color: red;">*</span></label>
                    <div class="controls">
                        <select id="meal" class="form-select" name="meal">
                            <option value="">------</option>
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="dinner">Dinner</option>
                        </select>
                    </div> <!-- /controls -->
                </div> <!-- /control-group -->
            </div>
            &emsp14;
            <div class="row row-group">
                <div class="control-group col-md-6">
                    <label class="control-label">Please Enter Number of people <span style="color: red;">*</span></label>
                    <div class="controls">
                        <input type="number" class="form-control" id="noPeople" name="noPeople" value="{!! old('noPeople') !!}">
                        @error ('name')
                        <label class="error">{{ $message }}</label>
                        @enderror
                    </div> <!-- /controls -->
                </div> <!-- /control-group -->
            </div>
            &emsp14;
        </div>


        <div class="tab" id="tab-restaurant">
            <div class="row row-group">
                <div class="control-group col-md-6">
                    <label class="control-label">Please Select a restaurant <span style="color: red;">*</span></label>
                    <div class="controls">
                        <select id="restaurant" class="form-select" name="restaurant">
                            <option value="">------</option>
                        </select>
                    </div> <!-- /controls -->
                </div> <!-- /control-group -->
            </div>
            &emsp14;
        </div>

        <div class="tab">
            <div id="dishInputs">
                <div class="row row-group select-dish-group">
                    <div class="control-group col-md-6">
                        <label class="control-label">Please Select a Dish <span style="color: red;">*</span></label>
                        <div class="controls">
                            <select class="form-select select-dish" name="dish[item-0][dish]">
                                <option value="">------</option>
                            </select>
                        </div> <!-- /controls -->
                    </div> <!-- /control-group -->
                    <div class="control-group col-md-6">
                        <label class="control-label">Please Enter no. of servings <span style="color: red;">*</span></label>
                        <div class="controls">
                            <input type="number" class="form-control" name="dish[item-0][servings]" value="">
                            <span class="error"></span>
                        </div> <!-- /controls -->
                    </div> <!-- /control-group -->
                </div>
            </div>

            &emsp14;
            <button id="addButton" type="button">+</button>
            &emsp14;
        </div>

        <div class="tab tab-preview">
            <div class="row row-group">
                <label for="meal" style="width: 25%;">Meal</label>
                <span id="span-meal" style="width: 25%;"></span>
            </div>
            <div class="row row-group">
                <label for="meal" style="width: 25%;">No. of. People</label>
                <span id="span-nop" style="width: 25%;"></span>
            </div>
            <div class="row row-group">
                <label for="meal" style="width: 25%;">Restaurant</label>
                <span id="span-restaurant" style="width: 25%;"></span>
            </div>
            <div class="row row-group">
                <label for="dishes" style="width: 25%;">Dishes</label>
                <span id="span-dishes" style="width: 25%; border: solid 1px black;"></span>
            </div>
        </div>

        <div style="overflow:auto;">
            <div style="float:right;">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
            </div>
        </div>

        <div style="text-align:center;margin-top:40px;">
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
        </div>

    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>
<style>
    label.error {
        color: red;
    }

    .row-group {
        display: flex;
        justify-content: center;
    }

    select {
        display: block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s
    }

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    input.invalid {
        background-color: #ffdddd;
    }

    .tab {
        display: none;
    }

    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    .step.finish {
        background-color: #04AA6D;
    }
</style>
<script>
    var currentTab = 0;
    var inputCount = 0;

    showTab(currentTab);

    function showTab(n) {
        var x = $(".tab");
        x.eq(n).css("display", "block");

        if (n == 0) {
            $("#prevBtn").css("display", "none");
        } else {
            $("#prevBtn").css("display", "inline");
        }

        if (n == (x.length - 1)) {
            $("#nextBtn").text("Submit");
        } else {
            $("#nextBtn").text("Next");
        }
        fixStepIndicator(n);
    }

    async function nextPrev(n) {
        var x = $(".tab");
        if (n == 1 && !$("#chooseDishForm").validate().form()) return false;
        x.eq(currentTab).css("display", "none");
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
            $("#chooseDishForm").submit();
            return false;
        }
        showTab(currentTab);

        $("#span-meal").text($('#meal').val());
        $("#span-nop").text($('#noPeople').val());
        $("#span-restaurant").text($('#restaurant').val());

        var dishData = [];

        $('.select-dish-group').each(function(index) {
            var dishItem = {};
            dishItem['dish'] = $(this).find('select').val();
            dishItem['servings'] = $(this).find('input[type="number"]').val();
            dishData.push(dishItem);
        });

        var spanDishes = $('#span-dishes');
        spanDishes.empty();

        dishData.forEach(function(item, index) {
            var dishHtml = '<div>Dish ' + item.dish + ' - ' + item.servings + '</div>';
            spanDishes.append(dishHtml);
        });
    }

    function fixStepIndicator(n) {
        var x = $(".step");
        x.removeClass("active");
        x.eq(n).addClass("active");
    }

    $("#chooseDishForm").validate({
        rules: {
            meal: {
                required: true
            },
            noPeople: {
                required: true,
                number: true,
            },
            restaurant: {
                required: true,
            },
            "dish[item-0][dish]": {
                checkSelect: true,
                checkSelectExist: true,
            },
        },
    });

    $.validator.addMethod("checkSelect", function(value, element) {
        var isValid = true;
        $(".select-dish-group").each(function() {
            var dishValue = $(this).find("select").val();
            var servingsValue = $(this).find("input[type='number']").val();
            if (dishValue === "" || servingsValue === "") {
                isValid = false;
                return false;
            }
        });
        return isValid;
    }, "Please select at least one dish and specify servings");

    $.validator.addMethod("checkSelectExist", function(value, element) {
        var isValid = true;
        var arraySelect = [];
        $(".select-dish-group").each(function() {
            var dishValue = $(this).find("select").val();
            if (arraySelect.indexOf(dishValue) !== -1) {
                isValid = false;
                return false;
            }
            arraySelect.push(dishValue);
        });
        return isValid;
    }, "Each item can only be selected once");

    $("#meal").on("change", function() {
        var request = {
            availableMeals: $("#meal").val() ?? null,
        };
        var url = "{{ route('get-restaurant') }}";
        callApi(request, url, true);
    });

    $("#restaurant").on("change", function() {
        var request = {
            availableMeals: $("#meal").val() ?? null,
            restaurant: $("#restaurant").val() ?? null,
        };
        var url = "{{ route('get-dish') }}";
        callApi(request, url);
    });

    function callApi(request, url, status) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            method: 'POST',
            data: request,
            success: function(response) {
                if (request.availableMeals && status) {
                    $('#restaurant').empty();
                    var uniqueRestaurants = {};
                    var uniqueRestaurantArray = [];
                    for (var key in response.data) {
                        if (response.data.hasOwnProperty(key)) {
                            var restaurant = response.data[key].restaurant;
                            if (!uniqueRestaurants[restaurant]) {
                                uniqueRestaurants[restaurant] = response.data[key];
                                uniqueRestaurantArray.push(response.data[key]);
                            }
                        }
                    }
                    $('#restaurant').append('<option value="">-------</option>');
                    $.each(uniqueRestaurantArray, function(index, item) {
                        var option = $('<option value="' + item.restaurant + '">' + item.restaurant + '</option>');
                        $('#restaurant').append(option);
                    });
                }
                if (request.restaurant) {
                    $('.select-dish').empty();
                    $('.select-dish').append('<option value="">-------</option>');
                    $.each(response.data, function(index, item) {
                        var option = $('<option value="' + item.name + '">' + item.name + '</option>');
                        $('.select-dish').append(option);
                    });
                }
            },
            error: function(xhr, text, err) {
                console.log(err);
            }
        });
    }

    $('#addButton').click(function() {
        inputCount++;
        var uniqueKey = 'input_' + inputCount;

        var selectInputPair = $('<div class="row row-group select-dish-group" id="' + uniqueKey + '">\
            <div class="control-group col-md-6">\
                <label class="control-label">Please Select a Dish <span style="color: red;">*</span></label>\
                <div class="controls">\
                    <select class="form-select select-dish-' + uniqueKey + '" name="dish[item-' + inputCount + '][dish]">\
                        <option value="">------</option>\
                    </select>\
                </div>\
            </div>\
            <div class="control-group col-md-6">\
                <label class="control-label">Please Enter no. of servings <span style="color: red;">*</span></label>\
                <div class="controls">\
                    <input type="number" class="form-control" name="dish[item-' + inputCount + '][servings]" value="">\
                    <span class="error"></span>\
                </div>\
            </div>\
        </div>');

        $('#dishInputs').append(selectInputPair);

        var request = {
            availableMeals: $("#meal").val() ?? null,
            restaurant: $("#restaurant").val() ?? null,
        };
        var url = "{{ route('get-dish') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            method: 'POST',
            data: request,
            success: function(response) {
                if (request.restaurant) {
                    $('.select-dish-' + uniqueKey).empty();
                    $('.select-dish-' + uniqueKey).append('<option value="">-------</option>');
                    $.each(response.data, function(index, item) {
                        var option = $('<option value="' + item.name + '">' + item.name + '</option>');
                        $('.select-dish-' + uniqueKey).append(option);
                    });
                }
            },
            error: function(xhr, text, err) {
                console.log(err);
            }
        });
    });
</script>