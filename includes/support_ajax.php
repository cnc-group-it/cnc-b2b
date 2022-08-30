<?php

add_action("wp_ajax_cnc_b2b_sync_product_with_woocommerce", "cnc_b2b_sync_product_with_woocommerce");
add_action("wp_ajax_nopriv_cnc_b2b_sync_product_with_woocommerce", "cnc_b2b_sync_product_with_woocommerce");

function cnc_b2b_sync_product_with_woocommerce() {
	global $image_uploade_url;
    $product_id = $_POST['product_id'];
    $post = get_post($product_id);
    
    $product_args = array(
        'post_type'  => 'product',
        'meta_query' => array(
            array(
                'key'     => 'cnc_b2b_bigcommerce_sku',
                'value'   => get_post_meta($product_id,"bigcommerce_sku",true),
                'compare' => '=',
            ),
        )
    );
    $query = new WP_Query( $product_args );
    if($query->post_count > 0){
        $post_id = $query->posts[0]->ID;
    }else{
        $args = array(
    		'post_content'   => $post->post_content,
    		'post_excerpt'   => $post->post_excerpt,
    		'post_name'      => $post->post_name,
    		'post_status'    => "draft",
    		'post_title'     => $post->post_title,
    		'post_type'      => "product",
    	);
    	$post_id = wp_insert_post( $args );
    }
    $prices = get_post_meta($product_id,"reseller_pricing",true);
    
    update_post_meta($post_id,"cnc_b2b_bigcommerce_sku",get_post_meta($product_id,"bigcommerce_sku",true));
    update_post_meta($post_id,"cnc_b2b_bigcommerce_source_data",get_post_meta($product_id,"bigcommerce_source_data",true));
    update_post_meta($post_id,"cnc_b2b_bigcommerce_modifier_data",get_post_meta($product_id,"bigcommerce_modifier_data",true));
    update_post_meta($post_id,"cnc_b2b_csv_price_data",get_post_meta($product_id,"csv_price_data",true));
    update_post_meta($post_id,"customiser_data",get_post_meta($product_id,"customiser_data",true));
    update_post_meta($post_id,"cnc_b2b_product_id",$product_id);
    
    if(get_option("cnc_b2b_import_category") == "1"){
    	if(get_post_meta($product_id,"cnc_b2b_category",true)){
    		foreach(get_post_meta($product_id,"cnc_b2b_category",true) as $pgs_term){
    			$category = get_term_by('name', $pgs_term->name, 'product_cat');
    			if($category){
    				wp_set_post_terms($post_id,$category->term_id,"product_cat",true);
    			}else{
    				$term = wp_insert_term(
					    $pgs_term->name,   // the term 
					    'product_cat', // the taxonomy
					    array(
					        'description' => $pgs_term->description,
					        'slug'        => $pgs_term->slug,
					    )
					);
    				wp_set_post_terms($post_id,$category['term_id'],"product_cat",true);
    			}
    		}
    	}
    }
    update_post_meta($post_id,"_price",$prices->RRP);
    update_post_meta($post_id,"_regular_price",$prices->RRP);
    //update_post_meta($post_id,"_sale_price",$prices[8]);
    update_post_meta($post_id,"cnc_b2b_bigcommerce_product",true);
    update_post_meta($product_id,"cnc_b2b_sync_with_woocommerce",true);
    update_post_meta($product_id,"cnc_b2b_woocommerce_product_id",$post_id);
    update_post_meta($post_id,"reseller_pricing",get_post_meta($product_id,"reseller_pricing",true));
    
    //-----------------------------------------------------------------------------Thumbnail Image & Gallery Images-----------------------------------------------------------------------//
	$images = explode(",",get_post_meta($product_id,"reseller_pricing",true)->Images);
    
    $thamnail_url = $image_uploade_url."/uploads/Images/PlainImages/".get_post_meta($product_id,"bigcommerce_sku",true).".jpg";
    $image_is_exist = cnc_b2b_is_image_exist($thamnail_url);
    if($image_is_exist){
    	$attachmentId = $image_is_exist;
    }else{
	    $file = array();
	    $file['name'] = get_post_meta($product_id,"bigcommerce_sku",true).".jpg";
	    $file['tmp_name'] = download_url($thamnail_url);
	    $attachmentId = media_handle_sideload($file, $post_id);
	    update_post_meta($attachmentId,"cnc_b2b_reference_url",$thamnail_url);
    }
    set_post_thumbnail($post_id, $attachmentId);
    
    $gallery_images = array();
    foreach($images as $key => $image){
    	$image_is_exist = cnc_b2b_is_image_exist($image_uploade_url.$image);
	    if($image_is_exist){
	    	$attachmentId = $image_is_exist;
	    }else{
	    	$file = array();
		    $file['name'] = $key."-gallery-".get_post_meta($product_id,"bigcommerce_sku",true).".jpg";
		    $file['tmp_name'] = download_url($image_uploade_url.$image);
		    $attachmentId = media_handle_sideload($file);
    		update_post_meta($attachmentId,"cnc_b2b_reference_url",$image_uploade_url.$image);
	    }
	    $gallery_images[] = $attachmentId;
    }
    update_post_meta($post_id, '_product_image_gallery', implode(',',$gallery_images));
    
    //-----------------------------------------------------------------------------Thumbnail Image & Gallery Images-----------------------------------------------------------------------//
    
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