<?php

add_action("wp_ajax_cnc_b2b_sync_product_with_woocommerce", "cnc_b2b_sync_product_with_woocommerce");
add_action("wp_ajax_nopriv_cnc_b2b_sync_product_with_woocommerce", "cnc_b2b_sync_product_with_woocommerce");

function cnc_b2b_sync_product_with_woocommerce() {
    $product_id = $_POST['product_id'];
  
	$post_id = cnc_b2b_create_product_for_wooconnerce($product_id,false);
	
    $results = array(
        "status" => 200,
        "url" => get_edit_post_link($post_id)
    );
    
    echo json_encode($results);
    die();
}


add_action("wp_ajax_cnc_b2b_sync_order_with_pgs", "cnc_b2b_sync_order_with_pgs");
add_action("wp_ajax_nopriv_cnc_b2b_sync_order_with_pgs", "cnc_b2b_sync_order_with_pgs");

function cnc_b2b_sync_order_with_pgs(){
    if(isset($_POST['order_id'])){
        cnc_b2b_order_sync_by_id($_POST['order_id']);
    }
    die();
}

function cnc_b2b_order_sync_by_id($order_id){
    $order = wc_get_order( $order_id );
    if ( $order ) {
        foreach ( $order->get_items() as $item_id => $item ) {
            $product_id = $item->get_product_id();
            if(get_post_meta($product_id,"cnc_b2b_bigcommerce_product",true) == "1"){
                cnc_b2b_order_item_sync_to_pgs($order,$item_id,$item);
            }
        }
    }
}

function cnc_b2b_order_item_sync_to_pgs($order,$item_id,$item){
    $custom_field = get_post_meta( $product_id, '_tmcartepo_data', true);
    $data = array(
        "order_id" => $order->get_id(),
        "item_id" => $item_id,
        "custom_field" => wc_get_order_item_meta( $item_id, 'custom_field', true ),
        "product_sku" => get_post_meta( $item->get_product_id(), 'cnc_b2b_bigcommerce_sku', true ),
        "product_name" => $item->get_name(),
        "item_number" =>  get_post_meta( $item->get_product_id(), 'cnc_b2b_bigcommerce_sku', true ),
        "quantity" => $item->get_quantity(),
        "shipping_type" => wc_get_order_item_meta( $item_id, 'shipping_type', true ),
        "next_day" => wc_get_order_item_meta( $item_id, 'next_day', true ),
        "customer_name" => $order->get_billing_first_name()." ".$order->get_billing_last_name(),
        "address_line_1" => $order->get_billing_address_1(),
        "address_line_2" => $order->get_billing_address_2(),
        "town" => $order->get_billing_city(),
        "county" => $order->get_billing_state(),
        "postcode" => $order->get_billing_postcode(),
        "country" =>$order->get_billing_country(),
        "reference" => wc_get_order_item_meta( $item_id, 'reference', true ),
        "order_notes" => $order->get_customer_note(),
        "engrave_fonts" => wc_get_order_item_meta( $item_id, 'Engrave Fonts', true ),
        "font_color" => wc_get_order_item_meta( $item_id, 'Engrave Font Color', true ),
        "clipart" => wc_get_order_item_meta( $item_id, 'Engrave Clipart', true ),
        "font_value_1" => wc_get_order_item_meta( $item_id, 'Engrave Font 1', true ),
        "font_value_2" => wc_get_order_item_meta( $item_id, 'Engrave Font 2', true ),
        "font_value_3" => wc_get_order_item_meta( $item_id, 'Engrave Font 3', true ),
        "font_value_4" => wc_get_order_item_meta( $item_id, 'Engrave Font 4', true ),
        "font_value_5" => wc_get_order_item_meta( $item_id, 'Engrave Font 5', true ),
        "font_value_6" => wc_get_order_item_meta( $item_id, 'Engrave Font 6', true ),
        "font_value_7" => wc_get_order_item_meta( $item_id, 'Engrave Font 7', true ),
        "font_value_8" => wc_get_order_item_meta( $item_id, 'Engrave Font 8', true ),
        "font_value_9" => wc_get_order_item_meta( $item_id, 'Engrave Font 9', true ),
        "font_value_10" => wc_get_order_item_meta( $item_id, 'Engrave Font 10', true ),
        "uploadNewUrl" => wc_get_order_item_meta( $item_id, 'uploadNewUrl', true ),
        "print_url" => wc_get_order_item_meta( $item_id, 'print_url', true ),
        "sale_price" => $item->get_total()
    );
    $url="https://personalisedgiftsupply.com/api/reseller-api/v1/order/create";
    $args = array(
        'headers' => array(
          'Content-Type' => 'application/json',
          'token' => get_option("pgs_products_api_key"),
          'username' => get_option("pgs_username"),
        ),
        'body' => wp_json_encode($data)
    );
    $responsedata=wp_remote_post($url,$args);
    $data=wp_remote_retrieve_body($responsedata);
    $body = json_decode($data, true);
    update_post_meta($order->get_id(),"cnc_order_id",$body['data']['cnc_order_id']);
    if($body['statusCode'] == 200){
        update_post_meta($order->get_id(),"cnc_b2b_order_created",true);
    }
}

function cnc_b2b_is_image_exist($image){
	$args = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_status' => 'inherit',
        'meta_query' => array(
            array(
	            'key' => 'cnc_b2b_reference_url', 
	            'value' => $image, 
	            'compare' => '='
            )
        )
	);
	$query =  new WP_Query($args);
    if($query->post_count > 0){
        return $query->post->ID;
    }else{
    	return false;
    }
}