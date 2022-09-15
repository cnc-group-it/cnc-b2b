jQuery(document).on("change",".cnc_b2b_order_setting_content .order_type_wrap input[type='radio']",function(){
    let sync_type = jQuery(".cnc_b2b_order_setting_content .order_type_wrap input[type='radio']:checked").val();
    if(sync_type == "manually_sync"){
        jQuery(".cnc_b2b_sync_order_status_wrap").hide(1000);
    }else{
        jQuery(".cnc_b2b_sync_order_status_wrap").show(1000);
    }
});
jQuery(document).on("click",".cnc_b2b_sync_with_woocommerce",function(){
	jQuery(this).children(".loadding").css("display","block");
    var product_id = jQuery(this).attr("data_id");
    var request_data = {
        "action" : "cnc_b2b_sync_product_with_woocommerce",
        "product_id": product_id
    }
    jQuery.post( cnc_b2b_ajax.ajaxurl ,request_data, function( data ) {
        var responce =  JSON.parse(data)
        if(responce.status == 200 && responce.url){
           window.location.replace(responce.url.replace(/&amp;/g, "\&"));
        }else{
        	alert("There are sothing wants to wrong. Product was not created")
        }
		jQuery(this).children(".loadding").css("display","none");
    });
});
jQuery(document).on("click",".order_sync_manully .order_sync_manully_button",function(){
    var order_id =  jQuery(this).attr("data-id");
    var request_data = {
        "action" : "cnc_b2b_sync_order_with_pgs",
        "order_id": order_id
    }
    jQuery.post( cnc_b2b_ajax.ajaxurl ,request_data, function( data ) {
        location.reload();
    });
});

jQuery(document).on("click", ".cnc_b2b_accoding_wapper .accoding_item .accoding_header", function() {
	if (jQuery(this).hasClass('active')) {
        jQuery(".cnc_b2b_accoding_wapper .accoding_item .accoding_body").slideUp();
        jQuery(".cnc_b2b_accoding_wapper .accoding_item .accoding_header").removeClass('active');
    } else {
        jQuery(".cnc_b2b_accoding_wapper .accoding_item .accoding_header").removeClass('active');
        jQuery(".cnc_b2b_accoding_wapper .accoding_item .accoding_body").slideUp();
        jQuery(this).siblings(".accoding_body").slideDown();
        jQuery(this).addClass('active');
    }
 });
 
jQuery(document).on("change",".cnc_b2b_order_setting_content .pricing_option",function(){
    let opt_val = jQuery(".cnc_b2b_order_setting_content .pricing_option option:selected").val();
    if(opt_val == 'custom_margin') {
    	jQuery(".cnc_b2b_margin_pricing").slideDown();
    }else{
    	jQuery(".cnc_b2b_margin_pricing").slideUp();
    }
});