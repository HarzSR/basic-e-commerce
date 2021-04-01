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
                        $('#getPrice').html("&#8377; " + getPrice[0]);
                        $('#hiddenPrice').val(getPrice[0]);
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
