/* main header cart functions to delete product */
function remove_cart_item(id) {
    var base_url = $("base_url_footer").html();
    alertify.confirm("Please Confirm Delete", "Are you sure you want to remove market place product from your cart?",
            function () {
                url = base_url+"home/remove_cart_item";
                $.post(url, {sid: id, action: "remove_cart_item"})
                        .done(function (data) {
                            $('#viewcart_' + id).hide();
                            $('#checkoutcart_' + id).remove();
                            var total_rows = $('#cart_count').val();
                            total_rows = total_rows - 1;
                            var product_total = $('#product_total_' + id).html();
                            var cart_subtotal = $('#cart_subtotal_value').html();
                            //console.log('product_total: '+product_total+' cart_subtotal: '+cart_subtotal);
                            var remain_cart_subtotal = cart_subtotal - product_total;
                            //console.log('remain_cart_subtotal: '+remain_cart_subtotal);
                            $('#cart_subtotal').html('$'+remain_cart_subtotal);
                            $('#checkout_subtotal').html('$'+remain_cart_subtotal);
                            $('#cart_subtotal_value').html(remain_cart_subtotal);
                            $('#cart_count').val(total_rows);
                            $('#cart_total_top').html(total_rows);
                            $('#cart_total_inner').html('Your cart ('+total_rows+' items )');
                            if (total_rows <= 0) {
                                $('#show_no_cart').html('<article><div class="text"><p>No Market place product found!</p></div></article>');
                                $('#hide_cart_footer').hide();
                            }
                            alertify.notify(data, 'success');
                            //console.log(data);
                        });
            },
            function () {
                alertify.error('Cancelled');
            });
}
/* main header cart functions to apply coupon */
$('#apply_coupon').click(function(){
    var coupon_code = $("input[id^='discount_coupon_main_cart']" ).val().trim();
    var base_url = $("base_url_footer").html();
    if(coupon_code==""){ 
        $('#discount_coupon_main_cart').addClass('warning');
        $('#coupon_response_main_cart').html('<p class="warning"><i class="fa fa-warning"></i> To Avail Discount, Please provide coupon code</p>');
    } else {
        $('#discount_coupon_main_cart').removeClass('warning');
        myurl = base_url+"home/apply_coupon_code";               
        $.ajax({
                type: "POST",
                url: myurl,
                data: {coupon_code: coupon_code, action: "apply_coupon_code"},
                dataType: "json", // Set the data type so jQuery can parse it for you
                success: function (data) {
                    if(data[0]=='error'){
                        $('#discount_coupon_main_cart').addClass('error');
                        $('#coupon_response_main_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>'); 
                    } else {
                        $('#coupon_response_main_cart').html('<p class="coupon_success"><i class="fa fa-spinner fa-spin"></i> loading...</p>');
                        var js_coupon_code      = data[1];
                        var js_coupon_discount  = data[2];
                        var js_coupon_type      = data[3];
                        var checkout_subtotal = $('#cart_subtotal_value').html();
                        //console.log('cart_subtotal: '+checkout_subtotal);
                        if(js_coupon_type!='fixed'){
                            js_coupon_discount = (checkout_subtotal * js_coupon_discount)/100;
                        } 
                        //console.log('coupon discount: '+js_coupon_discount);
                        var toal_after_discount = checkout_subtotal - js_coupon_discount;
                        $('#show_coupon_amount').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Coupon ('+js_coupon_code+')</b></td><td class="text-align"><p id="coupon_amount">-$'+js_coupon_discount+'</p></td>');
                        $('#show_coupon_total').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Total</b></td><td class="text-align"><p id="checkout_total">$'+toal_after_discount+'</p></td>');

                        $('#discount_coupon_main_cart').removeClass('error');
                        $('#coupon_response_main_cart').html('<p class="coupon_success"><i class="fa fa-exclamation-circle"></i> Coupon code is succesfully applied!</p>');
                        //$('#checkout_subtotal').html('Sub-Total');
                        $('#checkout_total').html('$'+toal_after_discount);
                        $('#checkout_title').html('Sub-Total');
                        $('#coupon_code').html(js_coupon_code);
                    }
                }
            });
    }
});
/* main header cart functions checkout out button functions */
$('#checkout_cart_click').click(function(){
    var coupon_code = $('#coupon_code').html();
    //console.log("I AM IN with: "+coupon_code);
    var base_url = $("base_url_footer").html();
    if(coupon_code!=""){
        myurl = base_url+"home/apply_coupon_code";
        $.ajax({
                type: "POST",
                url: myurl,
                data: {coupon_code: coupon_code, action: "apply_coupon_code"},
                dataType: "json", // Set the data type so jQuery can parse it for you
                success: function (data) {
                    if(data[0]=='error'){
                    // do nothing
                    } else {
                        var js_coupon_code      = data[1];
                        var js_coupon_discount  = data[2];
                        var js_coupon_type      = data[3];
                        var checkout_subtotal = $('#cart_subtotal_value').html();
                        //console.log('cart_subtotal: '+checkout_subtotal);
                        if(js_coupon_type!='fixed'){
                            js_coupon_discount = (checkout_subtotal * js_coupon_discount)/100;
                        } 
                        //console.log('coupon discount: '+js_coupon_discount);
                        var toal_after_discount = checkout_subtotal - js_coupon_discount;
                        $('#show_coupon_amount').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Coupon ('+js_coupon_code+')</b></td><td class="text-align"><p id="coupon_amount">-$'+js_coupon_discount+'</p></td>');
                        $('#show_coupon_total').html('<td colspan="2" width="60%">&nbsp;</td><td>&nbsp;</td><td class="text-align"><b>Total</b></td><td class="text-align"><p id="checkout_total">$'+toal_after_discount+'</p></td>');

                        //$('#checkout_subtotal').html('Sub-Total');
                        $('#checkout_total').html('$'+toal_after_discount);
                        $('#checkout_title').html('Sub-Total');
                        $('#checkout_title').html(js_coupon_code);
                    }
                }
            });
    }
});
/* mini cart functionality at job advertise page */
$('#apply_coupon_mini').click(function(){
    //console.log('Mini cart here');
    var coupon_code = $("input[id^='discount_coupon_mini_cart']" ).val().trim();
    var base_url = $("base_url_footer").html();
    if(coupon_code==""){ 
        $('#discount_coupon_mini_cart').addClass('warning');
        $('#coupon_response_mini_cart').html('<p class="warning"><i class="fa fa-warning"></i> To Avail Discount, Please provide coupon code</p>');
    } else {
        $('#discount_coupon_mini_cart').removeClass('warning');
        $('#coupon_response_mini_cart').html('<p class="coupon_success"><i class="fa fa-spinner fa-spin"></i> loading...</p>');
        //console.log('teek hai');
        myurl = base_url+"home/apply_coupon_code";               
        $.ajax({
                type: "POST",
                url: myurl,
                data: {coupon_code: coupon_code, action: "apply_coupon_code", minicart: "true"},
                dataType: "json", // Set the data type so jQuery can parse it for you
                success: function (data) {
                    if(data[0]=='error'){
                        $('#discount_coupon_mini_cart').addClass('error');
                        $('#coupon_response_mini_cart').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Coupon code is not valid or expired!</p>'); 
                    } else {
                        var js_coupon_code      = data[1];
                        var js_coupon_discount  = data[2];
                        var js_coupon_type      = data[3];
                        var checkout_subtotal = $('.cart_total').html();
                        $('#discount_coupon_mini_cart').removeClass('error');
                        $('#coupon_response_mini_cart').html('<p class="coupon_success"><i class="fa fa-exclamation-circle"></i> Coupon code is succesfully applied!</p>');
                        //console.log('code: '+js_coupon_code+' discount: '+js_coupon_discount+' type: '+js_coupon_type+' subtotal: '+checkout_subtotal);
                        if(js_coupon_type!='fixed'){
                            js_coupon_discount = (checkout_subtotal * js_coupon_discount)/100;
                        } 
                        var toal_after_discount = checkout_subtotal - js_coupon_discount;
                        $('#checkout_title_minicart').html('Sub-Total');
                        $('#show_coupon_amount_minicart').html('<td width="70%" colspan="2" style="text-align: right;"><b>Coupon ('+js_coupon_code+')</b></td><td class="text-align"><p id="coupon_amount">-$'+js_coupon_discount+'</p></td>');
                        $('#show_coupon_total_minicart').html('<td width="70%" colspan="2" style="text-align: right;"><b>Total</b></td><td class="text-align"><p id="checkout_total">$'+toal_after_discount+'</p></td>');                           
                    }
                }
            });
    }
});
/* mini cart functionality checkout out button functions*/
$('#checkout_cart_click_minicart').click(function(){
    var coupon_code = $("input[id^='discount_coupon_mini_cart']" ).val().trim();
    var base_url = $("base_url_footer").html();
    if(coupon_code!=""){
        myurl = base_url+"home/apply_coupon_code";
        $.ajax({
                type: "POST",
                url: myurl,
                data: {coupon_code: coupon_code, action: "apply_coupon_code", minicart: "true"},
                dataType: "json", // Set the data type so jQuery can parse it for you
                success: function (data) {
                    if(data[0]=='error'){
                    // do nothing
                    } else {
                        var js_coupon_code      = data[1];
                        var js_coupon_discount  = data[2];
                        var js_coupon_type      = data[3];
                        var checkout_subtotal = $('.cart_total').html();
                        //console.log('code: '+js_coupon_code+' discount: '+js_coupon_discount+' type: '+js_coupon_type+' subtotal: '+checkout_subtotal);
                        if(js_coupon_type!='fixed'){
                            js_coupon_discount = (checkout_subtotal * js_coupon_discount)/100;
                        } 
                        var toal_after_discount = checkout_subtotal - js_coupon_discount;
                        $('#checkout_title_minicart').html('Sub-Total');
                        $('#show_coupon_amount_minicart').html('<td width="70%" colspan="2" style="text-align: right;"><b>Coupon ('+js_coupon_code+')</b></td><td class="text-align"><p id="coupon_amount">-$'+js_coupon_discount+'</p></td>');
                        $('#show_coupon_total_minicart').html('<td width="70%" colspan="2" style="text-align: right;"><b>Total</b></td><td class="text-align"><p id="checkout_total">$'+toal_after_discount+'</p></td>');                           
                    }
                }
            });
    }
});
/* main header checkout function for cc */
$('#paypal_confirm_main').click(function(){
    console.log('I am In');
});