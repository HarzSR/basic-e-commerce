
$(document).ready(function()
{
	$("#new_pwd").keyup(function()
    {
		var current_pwd = $("#current_pwd").val();
		$.ajax({
			type:'get',
			url:'/admin/check-pwd',
			data:{current_pwd:current_pwd},
			success:function(resp)
            {
				//alert(resp);
                // console.log(current_pwd);
                if(current_pwd == "")
                {
                    $("#chkPwd").html("<font color='red'>Current Password is Empty</font>");
                }
				else if(resp == "false")
				{
					$("#chkPwd").html("<font color='red'>Current Password is Incorrect</font>");
				}
				else if(resp == "true")
				{
					$("#chkPwd").html("<font color='green'>Current Password is Correct</font>");
				}
			},
            error:function()
            {
				alert("Error");
			}
		});
	});

	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();

	$('select').select2();

	// Form Validation
    $("#basic_validate").validate({
		rules:{
			required:{
				required:true
			},
			email:{
				required:true,
				email: true
			},
			date:{
				required:true,
				date: true
			},
			url:{
				required:true,
				url: true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// Add Category Validation
    $("#add_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,
			},
			url:{
				required:true,
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// Add Product Validation
    $("#add_product").validate({
		rules:{
			category_id:{
				required:true,
			},
			product_name:{
				required:true,
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,
			},
            description:{
			    required:true,
            },
			price:{
				required:true,
				number:true,
			},
			image:{
				required:true,
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// Edit Category Validation
    $("#edit_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,
			},
			url:{
				required:true,
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#number_validate").validate({
		rules:{
			min:{
				required: true,
				min:10
			},
			max:{
				required:true,
				max:24
			},
			number:{
				required:true,
				number:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#password_validate").validate({
		rules:{
			current_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			new_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			confirm_pwd:{
				required:true,
				minlength:6,
				maxlength:20,
				equalTo:"#new_pwd"
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// $(".delCategory").click(function(){
	// 	if(confirm('Are you sure you want to delete this Category?')){
	// 		return true;
	// 	}
	// 	return false;
	// });

    // $(".delProduct").click(function(){
    //     if(confirm('Are you sure you want to delete this Category?')){
    //         return true;
    //     }
    //     return false;
    // });

    $(".deleteRecord").click(function (){
        var id = $(this).attr('rel');
        var deleteFunction = $(this).attr('rel1');
        swal({
            title: "Are you sure",
            text: "Would you like to Delete the Product ID " + id + " ? ",
            type: "warning",
            showCancelButton: true,
            // confirmButtonClass: "btn-danger",
            // confirmButtonText: "Yes, Delete it",
            confirmButtonColor: '#3085D6',
            cancelButtonColor: '#D33',
            confirmButtonText: "Yes, Delete it",
            cancelButtonText: "No",
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-success',
            buttonStyling: false,
            reverseButtons: true
        },
        function (){
            window.location.href="/admin/" + deleteFunction + "/" + id;
        });
    })

    $(document).ready(function () {
        // Max Input Field
        var maxField = 10;
        // Add Button to Select
        var addButton = $('.add_button');
        // Input Field Wrapper
        var wrapper = $('.field_wrapper');
        // New Input Field
        // var fieldHTML = '<div><input type="text" name="field_name[]" value=""><a href="javascipr:void(0);" class="remove_button" title="Remove Filed"><img src="remove-icon.png"></a></div>';
        var fieldHTML = '<div class="field_wrapper" style="margin-left: 180px;">\n' +
            '                                        <div><input type="text" name="sku[]" id="sku" placeholder="SKU" style="width: 120px;" required>\n' +
            '                                            <input type="text" name="size[]" id="size" placeholder="Size" style="width: 120px;" required>\n' +
            '                                            <input type="text" name="price[]" id="price" placeholder="Price" style="width: 120px;" required>\n' +
            '                                            <input type="text" name="stock[]" id="stock" placeholder="Stock" style="width: 120px;" required><a href="javascipr:void(0);" class="remove_button" title="Remove Filed">Remove</a></div></div>';
        // Counter Initialization
        var counter = 1;
        $(addButton).click(function (){
            if( counter < maxField)
            {
                counter++;
                $(wrapper).append(fieldHTML);
            }
        });
        $(wrapper).on('click', '.remove_button', function (e){
            e.preventDefault();
            $(this).parent('div').remove();
            counter--;
        });
    });

});
