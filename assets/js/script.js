jQuery(document).on("change",".cnc_b2b_order_setting_content .order_type_wrap input[type='radio']",function(){
    let sync_type = jQuery(".cnc_b2b_order_setting_content .order_type_wrap input[type='radio']:checked").val();
    if(sync_type == "manually_sync"){
        jQuery(".cnc_b2b_sync_order_status_wrap").hide(1000);
    }else{
        jQuery(".cnc_b2b_sync_order_status_wrap").show(1000);
    }
});
jQuery(document).on("click",".cnc_b2b_sync_with_woocommerce",function(){
    var product_id = jQuery(this).attr("data_id");
    var request_data = {
        "action" : "cnc_b2b_sync_product_with_woocommerce",
        "product_id": product_id
    }
    jQuery.post( cnc_b2b_ajax.ajaxurl ,request_data, function( data ) {
        var responce =  JSON.parse(data)
        if(responce.status == 200){
            window.location.replace(responce.url.replace(/&amp;/g, "\&"));
        }
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