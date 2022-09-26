<?php
// add_action("wp",function(){
//     if($_GET['ssss']=='ssss'){
//         echo "<pre>";

//         cnc_b2b_get_singal_page_pgs_product(25);
//         exit;
//         }
//     });
function cnc_b2b_add_action_scheduler_for_add_all_pgs_product()
{
    if (false === as_next_scheduled_action('cnc_b2b_add_all_pgs_product') && get_option("cnc_b2b_import_all") == "1") {
        as_schedule_recurring_action(strtotime('tomorrow'), DAY_IN_SECONDS, 'cnc_b2b_add_all_pgs_product');
    }
}
add_action("init", "cnc_b2b_add_action_scheduler_for_add_all_pgs_product");

add_action('cnc_b2b_add_all_pgs_product', 'cnc_b2b_get_all_pgs_product_count');

function cnc_b2b_get_all_pgs_product_count()
{
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/count";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' => get_option("pgs_products_api_key"),
            'username' => get_option("pgs_username"),
        )
    );
    $responsedata = wp_remote_get($url, $args);
    $data = wp_remote_retrieve_body($responsedata);
    $body = json_decode($data, true);
    $count = (int)$body['data']['count'];

    $total_page = ((int)($count / 10)) + 1;

    for ($i = 1; $i <= $total_page; $i++) {
        as_schedule_single_action(strtotime('now'), 'cnc_b2b_fatch_singal_page', array("page" => $i));
    }
}


add_action('cnc_b2b_fatch_singal_page', 'cnc_b2b_get_singal_page_pgs_product');

function cnc_b2b_get_singal_page_pgs_product($page)
{
    $url = "https://personalisedgiftsupply.com/api/reseller-api/v1/product/all_products/?page=" . $page . "&size=10";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'token' => get_option("pgs_products_api_key"),
            'username' => get_option("pgs_username"),
        )
    );
    $responsedata = wp_remote_get($url, $args);
    $data = wp_remote_retrieve_body($responsedata);
    $body = json_decode($data, true);
    
    if ($body['statusCode'] == 200) {
        foreach ($body['data']['product'] as $product) {
            $post_id = cnc_b2b_create_post_to_pgs_product($product);
			
            if ($post_id) {
                cnc_b2b_create_product_for_wooconnerce($post_id, true);
            }
        }
    }
}
