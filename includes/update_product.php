<?php


function cnc_b2b_add_action_scheduler_for_product(){
    if ( false === as_next_scheduled_action( 'cnc_b2b_get_product_list_update' )) {
		as_schedule_recurring_action( strtotime('+1 hour'), HOUR_IN_SECONDS, 'cnc_b2b_get_product_list_update' );
	}
}
add_action("init","cnc_b2b_add_action_scheduler_for_product");

add_action('cnc_b2b_get_product_list_update', 'update_product_list_with_pgs');


function update_product_list_with_pgs(){
    $url="https://personalisedgiftsupply.com/api/reseller-api/v1/product/list";
    $args = array(
        'headers' => array(
          'Content-Type' => 'application/json',
          'token' => get_option("pgs_products_api_key"),
          'username' => get_option("pgs_username"),
        )
    );
    $responsedata=wp_remote_get($url,$args);
    $data=wp_remote_retrieve_body($responsedata);
    $body = json_decode($data);
    
    $post_ids = array();
    $wc_post_ids = array();
    if($body->statusCode == 200){
        foreach($body->data as $product){
            $args = array(
                'post_type'  => 'pgs_products',
                'meta_query' => array(
                    array(
                        'key'     => 'bigcommerce_sku',
                        'value'   => $product->meta->bigcommerce_sku[0],
                        'compare' => '=',
                    ),
                ),
            );
            $query = new WP_Query( $args );
            
            if($query->post_count > 0){
                $post_id = $query->posts[0]->ID;
                $post_ids[] = $post_id;
                $wc_post_ids[] = get_post_meta($post_id,"cnc_b2b_woocommerce_product_id",true);
            }
        }
    }
    $args  = array(
        'post_type'      => 'pgs_products',
        'post__not_in'   => $post_ids,
    );
    $the_query = new WP_Query( $args );
    
    if ($the_query->have_posts() ) {
        while( $the_query->have_posts() ) {
            $the_query->the_post();
            wp_delete_post(get_the_ID());
        }
    }
    
    
    $args = array(
        'post_type'  => 'product',
        'meta_query' => array(
            array(
                'key'     => 'cnc_b2b_bigcommerce_product',
                'value'   => true,
                'compare' => '=',
            ),
        ),
        'post__not_in'   => $wc_post_ids,
    );
    $wc_query = new WP_Query( $args );
    
    if ($wc_query->have_posts() ) {
        while( $wc_query->have_posts() ) {
            $wc_query->the_post();
            wp_delete_post(get_the_ID());
        }
    }
}