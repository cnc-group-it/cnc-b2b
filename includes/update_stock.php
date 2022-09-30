<?php
// add_action("wp",function(){
//     if($_GET['sss']=='sss'){
//     	echo "<pre>";
//   //  	$allposts = new WP_Query( 
//   //  		array(
//   //  			'post_type'=>'product',
//   //  			'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
// 	 //		 		'posts_per_page' => 299
//   //  		) 
//   //  	);
// 		// print_R($allposts);
// 		// foreach ($allposts->posts as $eachpost) {
// 		// 	wp_delete_post( $eachpost->ID, true );
// 		// }
//         cnc_b2b_product_stock_update();
//         exit;
//     }
        
// });
function cnc_b2b_add_action_scheduler_for_stock(){
    if ( false === as_next_scheduled_action( 'cnc_b2b_get_product_stock' ) ) {
		as_schedule_recurring_action( strtotime('+1 hour'), HOUR_IN_SECONDS, 'cnc_b2b_get_product_stock' );
	}
}

add_action("init","cnc_b2b_add_action_scheduler_for_stock");

add_action('cnc_b2b_get_product_stock', 'cnc_b2b_product_stock_update');

function cnc_b2b_product_stock_update(){
    global $wpdb;
    $results = $wpdb->get_results( "SELECT meta_value,post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='cnc_b2b_bigcommerce_sku'", ARRAY_A );
    $skus = array();
    
    foreach($results as $row){
        $skus[] = $row['meta_value'];
    }
    if(count($skus) > 10){
    	foreach(array_chunk($skus,10) as $skus){
    		cnc_b2b_update_product_stock_by_skus($skus);
    	}
    }else{
    	cnc_b2b_update_product_stock_by_skus($skus);
    }
    
}

function cnc_b2b_update_product_stock_by_skus($skus){
	
    $url="https://personalisedgiftsupply.com/api/reseller-api/v1/product/stock/?skus=".implode(",",$skus);
   
    $args = array(
        'headers' => array(
          'Content-Type' => 'application/json',
          'token' => get_option("pgs_products_api_key"),
          'username' => get_option("pgs_username"),
        )
    );
    $responsedata=wp_remote_get($url,$args);
    $data=wp_remote_retrieve_body($responsedata);
    
    $body = json_decode($data,true);
    $data = $body['data'];
    
    
    foreach($data as $key => $value){
        $args = array(
          'post_type'       => 'product',
          'meta_query'      => array(
            array(
              'key'         => 'cnc_b2b_bigcommerce_sku',
              'value'       => $key,
            ),
          )
        );
        $query = new WP_Query( $args );
        $product_id = $query->posts[0]->ID;
        if((int)$value['Stock Level'] == 0){
            update_post_meta($product_id, '_stock_status', 'outofstock');
            wp_set_post_terms( $product_id, 'outofstock', 'product_visibility', true );
        }else{
            wp_remove_object_terms( $product_id, 'outofstock', 'product_visibility' );
            update_post_meta($product_id, '_stock_status', 'instock');
        }
        update_post_meta($product_id, '_manage_stock', "yes");
        update_post_meta($product_id, '_stock', (int)$value['Stock Level']);
        
	    $regular_price = cnc_b2b_get_regular_price($value);
	  
     //   echo "<pre>";
	    // print_r($regular_price);
	    // exit;
	    update_post_meta($product_id, "_price", $regular_price);
	    update_post_meta($product_id, "_regular_price", $regular_price);
    	update_post_meta($product_id, 'reseller_pricing', $value);
        
        
        $product_url="https://personalisedgiftsupply.com/api/reseller-api/v1/product/singal/?sku=".$key;
        $product_args = array(
            'headers' => array(
              'Content-Type' => 'application/json',
              'token' => get_option("pgs_products_api_key"),
              'username' => get_option("pgs_username"),
            )
        );
        $product_responsedata=wp_remote_get($product_url,$product_args);
        $product_data=wp_remote_retrieve_body($product_responsedata);
        $product_body = json_decode($product_data,true);
        
        if($product_body['statusCode'] == 200){
            $customiser_data = $product_body['data']['customiser_data'];
            update_post_meta($product_id,"customiser_data",$customiser_data);
        }
    }
}

function cnc_b2b_get_regular_price($value){
	if(get_option("cnc_b2b_price_for_product") == "custom_margin"){
		$margin_per = (float)get_option("cnc_b2b_margin_for_ragular_price");
		$margin = ($margin_per / 100) + 1;
	    $round_up_price = (float)get_option("cnc_b2b_round_up_the_nearest");
		$dropship_for_1 = (float)$value['Dropship For 1'];
		$regular_price = round(($dropship_for_1 * 1.2) * $margin) - $round_up_price;
	    return $regular_price;
	}else if(get_option("cnc_b2b_price_for_product") == "suggested_rrp"){
		return $value['RRP'];
	}else{
		return 0;
	}
}