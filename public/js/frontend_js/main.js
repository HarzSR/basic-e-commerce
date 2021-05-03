/*price range*/

 $('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};

/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});
});

$(document).ready(function ()
{
    // Change Price based on Selected Size

    if(document.getElementById("getPrice") !== null)
    {
        var originalSize = document.getElementById('getPrice').innerHTML;
        $('#size').change(function () {
            var idSize = $(this).val();
            if (idSize) {
                $.ajax({
                    type: 'get',
                    url: '/get-product-price',
                    data: {
                        idSize: idSize,
                    },
                    success: function (response) {
                        // alert(response);
                        var getPrice = response.split('#');
                        var otherPrice = getPrice[0].split('-');
                        $('#getPrice').html("&#8377; " + otherPrice[0] + "<br> <h2> US&#x24; " + otherPrice[1] + "<br> GB&#xa3; " + otherPrice[2] + "<br> EU&#x20AC; " + otherPrice[3] + "<br>NZ&#x24; " + otherPrice[4] + "<br> </h2>");
                        $('#hiddenPrice').val(otherPrice[0]);
                        if(getPrice[1] == 0)
                        {
                            $('#cartButton').hide();
                            $('#availability').text("Out of Stock");
                        }
                        else if(getPrice[1] <= 5)
                        {
                            $('#cartButton').show();
                            $('#availability').text("Last few in Stock");
                        }
                        else
                        {
                            $('#cartButton').show();
                            $('#availability').text("In Stock");
                        }
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            } else {
                $('#getPrice').html(originalSize);
            }
        });
    }

});

// Instantiate EasyZoom instances
var $easyzoom = $('.easyzoom').easyZoom();

// Setup thumbnails example
var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

$('.thumbnails').on('click', 'a', function(e)
{
    var $this = $(this);
    e.preventDefault();

    var originalImage = $(".mainImage").attr("src");
    var mainImageHref = $(".mainImageHref").attr('href');

    // Use EasyZoom's `swap` method
    api1.swap($this.data('standard'), $this.attr('href'));

    $this.attr('href', mainImageHref);
    $this.data('standard', originalImage);
    $this.find("img").attr("src", originalImage);
});

// Setup toggles example
var api2 = $easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

$('.toggle').on('click', function()
{
    var $this = $(this);

    if ($this.data("active") === true)
    {
        $this.text("Switch on").data("active", false);
        api2.teardown();
    }
    else
    {
        $this.text("Switch off").data("active", true);
        api2._init();
    }
});

$().ready(function (){
    $("#registerForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                accept: "[a-zA-Z]+"
            },
            registerPassword: {
                required: true,
                minlength: 5
            },
            email: {
                required: true,
                email: true,
                remote: '/check-email'
            }
        },
        messages: {
            name: {
                required: "Please enter your Full Name.",
                minlength: "Minimum length required is 2.",
                accept: "Please enter only Alphabets."
            },
            registerPassword: {
                required: "Please enter your Password.",
                minlength: "Minimum length required is 5."
            },
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email.",
                remote: "Email already exists, Please try signing in or else sign up with a new Email."
            }
        }
    });

    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            loginPassword: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email."
            },
            loginPassword: {
                required: "Please enter your Password.",
            }
        }
    });

    $("#forgotPasswordForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            }
        },
        messages: {
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email."
            }
        }
    });

    $("#accountForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                accept: "[a-zA-Z]+"
            },
            address: {
                required: true
            },
            city: {
                required: true,
                minlength: 2,
                accept: "[a-zA-Z]+"
            },
            state: {
                required: true,
                minlength: 2,
                accept: "[a-zA-Z]+"
            },
            country: {
                required: true
            },
            pincode: {
                required: true,
                minlength: 4,
                maxlength: 6,
                accept: "[0-9]+"
            },
            mobile: {
                required: true,
                accept: "[0-9]+"
            }
        },
        messages: {
            name: {
                required: "Please enter your Full Name",
                minlength: "Minimum 2 characters",
                accept: "Only Alphabets"
            },
            address: {
                required: "Please enter your Address with Street Name"
            },
            city: {
                required: "Please enter your City Name",
                minlength: "Minimum 2 characters",
                accept: "Only Alphabets"
            },
            state: {
                required: "Please enter your State Name",
                minlength: "Minimum 2 characters",
                accept: "Only Alphabets"
            },
            country: {
                required: "Please select your Country"
            },
            pincode: {
                required: "Please enter your Pin Code/Zip Code",
                minlength: "Minimum 4 digit",
                maxlength: "Maximum 6 digit",
                accept: "Only Numbers"
            },
            mobile: {
                required: "Please enter your Mobile number with + ISD code",
                accept: "Only Numbers & +"
            }
        }
    });

    $("#passwordForm").validate({
        rules: {
            current_pwd: {
                required: true,
                minlength: 5
            },
            new_pwd: {
                required: true,
                minlength: 5
            },
            confirm_pwd: {
                required: true,
                minlength: 5,
                equalTo : "#new_pwd"
            }
        },
        messages: {
            current_pwd: {
                required: "Please enter the Password.",
                minlength: "Password should be at least 5 characters long."
            },
            new_pwd: {
                required: "Please enter the Password.",
                minlength: "Password should be at least 5 characters long."
            },
            confirm_pwd: {
                required: "Please enter the Password.",
                minlength: "Password should be at least 5 characters long.",
                equalTo: "Password doesn't match with New one. Please type same password"
            }
        }
    });

    $("#current_pwd").keyup(function () {
        var current_pwd = $(this).val();
        $.ajax({
            type: 'post',
            url: '/check-user-pwd',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                current_pwd: current_pwd
            },
            success: function (resp) {
                // alert(resp);
                if(resp == "false")
                {
                    $("#chkPwd").html("<font color='red'> Current Password is Incorrect</font>");
                }
                else if(resp == "true")
                {
                    $("#chkPwd").html("<font color='green'> Current Password is Corret</font>");
                }
            },
            error: function (resp) {
                alert("Error - " + resp);
            }
        });
    });

    $("#registerPassword").passtrength({
        minChars: 5,
        passwordToggle: true,
        tooltip: true
    });

    $("#current_pwd").passtrength({
        minChars: 5,
        passwordToggle: true,
        tooltip: true
    });

    $("#new_pwd").passtrength({
        minChars: 5,
        passwordToggle: true,
        tooltip: true
    });

    $("#confirm_pwd").passtrength({
        minChars: 5,
        passwordToggle: true,
        tooltip: true
    });

    $("#billToShip").click(function (){
        if(this.checked)
        {
            $("#shipping_name").val($("#billing_name").val());
            $("#shipping_address").val($("#billing_address").val());
            $("#shipping_city").val($("#billing_city").val());
            $("#shipping_state").val($("#billing_state").val());
            $("#shipping_country").val($("#billing_country").val());
            $("#shipping_pincode").val($("#billing_pincode").val());
            $("#shipping_mobile").val($("#billing_mobile").val());
        }
        else
        {
            $("#shipping_name").val("");
            $("#shipping_address").val("");
            $("#shipping_city").val("");
            $("#shipping_state").val("");
            $("#shipping_country").val("");
            $("#shipping_pincode").val("");
            $("#shipping_mobile").val("");
        }
    });
});

function selectPaymentMethod()
{
    if($('#Paypal').is(':checked') || $('#COD').is(':checked'))
    {
        // alert("Paypal");
    }
    else
    {
        alert("Please select Payment method");
        return false;
    }
}

function checkPincode()
{
    var pincode = $('#chkPincode').val();
    if(pincode == '')
    {
        alert("Please Enter Pincode");
    }
    else
    {
        $.ajax({
            type: 'post',
            data: {
                pincode: pincode
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/check-pincode',
            success: function (response) {
                // alert(response);
                if(response > 0)
                {
                    $('#pincodeResponse').html("<font color='green'><b>Yes, We can deliver to this pincode - " + pincode + "</b></font>");
                }
                else
                {
                    $('#pincodeResponse').html("<font color='red'><b>Unfortunately, we are yet to cover the pincode - " + pincode + ". But, don't worry, we will soon service this area (if its possible).</b></font>");
                }
            },
            error: function (response) {
                alert("Error : " + response);
            }
        });
    }
}
