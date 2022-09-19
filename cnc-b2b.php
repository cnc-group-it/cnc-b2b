<?php

/**
 * Plugin Name: Personalised Gift Supply - Listing Tool
 * Description:       The All-in-one Personalised Gift Supply listing tool, helps in listing products, with customisers and order processing. The easiest way to get Personalised Gifts for sale.
 * Version:           0.0.17
 * Author:            Akshar Soft Solutions
 * Author URI:        http://aksharsoftsolutions.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
 
/**
 * Activate the plugin.
 */
function cnc_b2b_activate() { 
    update_option("cnc_b2b_sync_order_type","sync_on_status_change");
    update_option("cnc_b2b_sync_order_status","wc-pending");
    update_option("cnc_b2b_sync_order_status_automatically","1");
    update_option("cnc_b2b_dynamic_pricing","1");
    update_option("cnc_b2b_price_for_product","suggested_rrp");
}
register_activation_hook( __FILE__, 'cnc_b2b_activate' );

global $image_uploade_url;
$image_uploade_url = "https://www.allthingspersonalised.com/wp-content";

require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');

include "includes/support_ajax.php";
include "includes/update_stock.php";
include "includes/update_status.php";
include "includes/update_product.php";
include "includes/upload_all_pgs_product.php";
include "includes/customiser.php";
function cnc_b2b_product_get()
{
    $args = array(
        'labels'      => array(
            'name'          => __('Product List'),
            'singular_name' => __('Personalised Gift Supply'),
            'menu_name' => __("Personalised Gift Supply")
        ),
        'capability_type' => 'post',
        // 'map_meta_cap'=>true,
        'capabilities' => array(
            'create_posts' => false,
            'delete_post' => false,
            'read_post' => true,
            'edit_post' => false
        ),
        'public'      => false,
        'show_ui' => true,
        'menu_icon' => plugin_dir_url(__FILE__) . 'assets/images/Personalised_Gift_Supply_Logo_WP.png',
        'has_archive' => true,
    );
    register_post_type('pgs_products', $args);
}
add_action('init', 'cnc_b2b_product_get');

function cnc_b2b_support_plugin_scripts()
{
    wp_enqueue_style('atp_style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), null, "all");
    wp_enqueue_script('atp_script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array(), null, "all");
    

    wp_localize_script(
        'atp_script',
        'cnc_b2b_ajax',
        array(
            'ajaxurl' => admin_url('admin-ajax.php')
        )
    );
}
add_action('admin_enqueue_scripts', 'cnc_b2b_support_plugin_scripts');

add_filter('wp_get_attachment_url', 'cnc_b2b_change_post_media_url', 10, 2);

function cnc_b2b_change_post_media_url($url, $image_id)
{
    if (get_post_meta($image_id, 'cnc_b2b_image_url', true)) {
        $url = get_post_meta($image_id, 'cnc_b2b_image_url', true);
    }
    return $url;
}

function cnc_b2b_support_plugin_frontend_scripts()
{
    wp_enqueue_style('pgs_frontend_style', plugin_dir_url(__FILE__) . 'assets/css/fronend_style.css', array(), null, "all");
    wp_enqueue_script('pgs_frontend_bpopup_script', plugin_dir_url(__FILE__) . 'assets/js/jquery.bpopup.min.js', array(), null, "all");
    wp_enqueue_script('pgs_frontend_script', plugin_dir_url(__FILE__) . 'assets/js/frontend_script.js', array(), null, "all");
}
add_action('wp_enqueue_scripts', 'cnc_b2b_support_plugin_frontend_scripts');
add_action('admin_menu', 'cnc_b2b_register_my_custom_submenu_page');
function cnc_b2b_register_my_custom_submenu_page()
{
    add_submenu_page(
        "edit.php?post_type=pgs_products",   //or 'options.php'
        'Product List',
        'Product List',
        'manage_options',
        'edit.php?post_type=pgs_products'
    );
    add_submenu_page(
        "edit.php?post_type=pgs_products",   //or 'options.php'
        'Order Settings',
        'Order Settings',
        'manage_options',
        'order-settings-submenu-page',
        'cnc_b2b_order_settings_submenu_page_callback'
    );
    add_submenu_page(
        "edit.php?post_type=pgs_products",   //or 'options.php'
        'Settings',
        'Settings',
        'manage_options',
        'setting-submenu-page',
        'cnc_b2b_setting_submenu_page_callback'
    );
    add_submenu_page(
        "edit.php?post_type=pgs_products",   //or 'options.php'
        'How To',
        'How To',
        'manage_options',
        'how-submenu-page',
        'cnc_b2b_how_to_submenu_page_callback'
    );
    add_submenu_page(
        "edit.php?post_type=pgs_products",   //or 'options.php'
        'FAQs',
        'FAQs',
        'manage_options',
        'faqs-submenu-page',
        'cnc_b2b_faqs_submenu_page_callback'
    );
}

function cnc_b2b_faqs_submenu_page_callback()
{
    include "includes/faqs.php";
}

function cnc_b2b_how_to_submenu_page_callback()
{
    include "includes/how_to.php";
}
function cnc_b2b_setting_submenu_page_callback()
{
    include "includes/settings.php";
}
function cnc_b2b_order_settings_submenu_page_callback()
{
    include "includes/order_settings.php";
}

function cnc_b2b_product_action_button($columns)
{
    unset($columns['date']);
    $columns['listed'] = __('Listed');
    $columns['action'] = __('Action');
    return $columns;
}
add_filter('manage_pgs_products_posts_columns', 'cnc_b2b_product_action_button');


function cnc_b2b_action_button_details($columns, $post_id)
{
    switch ($columns) {
        case 'action':
            $pgs_link = get_post_meta($post_id, 'pgs_link', true);
?>
            <div class="cnc_b2b_action_buttons">
                <div class="cnc_b2b_action"><a name='sync' class='cnc_b2b_sync_with_woocommerce cnc_b2b_link_button' data_id='<?php echo $post_id ?>'>
                        <div class="loadding" style="display:none"><svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-fidget-spinner" style="background: none;">
                                <g transform="rotate(96 50 50)">
                                    <g transform="translate(50 50)">
                                        <g ng-attr-transform="scale({{config.r}})" transform="scale(0.8)">
                                            <g transform="translate(-50 -58)">
                                                <path ng-attr-fill="{{config.c2}}" d="M27.1,79.4c-1.1,0.6-2.4,1-3.7,1c-2.6,0-5.1-1.4-6.4-3.7c-2-3.5-0.8-8,2.7-10.1c1.1-0.6,2.4-1,3.7-1c2.6,0,5.1,1.4,6.4,3.7 C31.8,72.9,30.6,77.4,27.1,79.4z" fill="#ffffff" />
                                                <path ng-attr-fill="{{config.c3}}" d="M72.9,79.4c1.1,0.6,2.4,1,3.7,1c2.6,0,5.1-1.4,6.4-3.7c2-3.5,0.8-8-2.7-10.1c-1.1-0.6-2.4-1-3.7-1c-2.6,0-5.1,1.4-6.4,3.7 C68.2,72.9,69.4,77.4,72.9,79.4z" fill="#ffffff" />
                                                <circle ng-attr-fill="{{config.c4}}" cx="50" cy="27" r="7.4" fill="#ffffff" />
                                                <path ng-attr-fill="{{config.c1}}" d="M86.5,57.5c-3.1-1.9-6.4-2.8-9.8-2.8c-0.5,0-0.9,0-1.4,0c-0.4,0-0.8,0-1.1,0c-2.1,0-4.2-0.4-6.2-1.2 c-0.8-3.6-2.8-6.9-5.4-9.3c0.4-2.5,1.3-4.8,2.7-6.9c2-2.9,3.2-6.5,3.2-10.4c0-10.2-8.2-18.4-18.4-18.4c-0.3,0-0.6,0-0.9,0 C39.7,9,32,16.8,31.6,26.2c-0.2,4.1,1,7.9,3.2,11c1.4,2.1,2.3,4.5,2.7,6.9c-2.6,2.5-4.6,5.7-5.4,9.3c-1.9,0.7-4,1.1-6.1,1.1 c-0.4,0-0.8,0-1.2,0c-0.5,0-0.9-0.1-1.4-0.1c-3.1,0-6.3,0.8-9.2,2.5c-9.1,5.2-12,17-6.3,25.9c3.5,5.4,9.5,8.4,15.6,8.4 c2.9,0,5.8-0.7,8.5-2.1c3.6-1.9,6.3-4.9,8-8.3c1.1-2.3,2.7-4.2,4.6-5.8c1.7,0.5,3.5,0.8,5.4,0.8c1.9,0,3.7-0.3,5.4-0.8 c1.9,1.6,3.5,3.5,4.6,5.7c1.5,3.2,4,6,7.4,8c2.9,1.7,6.1,2.5,9.2,2.5c6.6,0,13.1-3.6,16.4-10C97.3,73.1,94.4,62.5,86.5,57.5z M29.6,83.7c-1.9,1.1-4,1.6-6.1,1.6c-4.2,0-8.4-2.2-10.6-6.1c-3.4-5.9-1.4-13.4,4.5-16.8c1.9-1.1,4-1.6,6.1-1.6 c4.2,0,8.4,2.2,10.6,6.1C37.5,72.8,35.4,80.3,29.6,83.7z M50,39.3c-6.8,0-12.3-5.5-12.3-12.3S43.2,14.7,50,14.7 c6.8,0,12.3,5.5,12.3,12.3S56.8,39.3,50,39.3z M87.2,79.2c-2.3,3.9-6.4,6.1-10.6,6.1c-2.1,0-4.2-0.5-6.1-1.6 c-5.9-3.4-7.9-10.9-4.5-16.8c2.3-3.9,6.4-6.1,10.6-6.1c2.1,0,4.2,0.5,6.1,1.6C88.6,65.8,90.6,73.3,87.2,79.2z" fill="#ffffff" />
                                            </g>
                                        </g>
                                    </g>
                                    <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite" />
                                </g>
                            </svg></div>List Product
                    </a></div>


                <?php if (get_post_meta($post_id, "cnc_b2b_sync_with_woocommerce", true)) { ?>
                    <div class="cnc_b2b_action"><a href="<?php echo get_edit_post_link(get_post_meta($post_id, "cnc_b2b_woocommerce_product_id", true)); ?>" target="_blank" class="cnc_b2b_link_button">Edit Product</a></div>
                    <div class="cnc_b2b_action"><a href="<?php echo get_permalink(get_post_meta($post_id, "cnc_b2b_woocommerce_product_id", true)); ?>" target="_blank" class="cnc_b2b_link_button">View Product</a></div>

                <?php } ?>
                <div class="cnc_b2b_action"><a href="<?php echo $pgs_link; ?>" name='view_in_pgs' target="_blank" class='cnc_b2b_view_in_pgs cnc_b2b_link_button'>View Product in PGS</a></div>
            </div>
            <?php
        case 'listed':
            $id_of_product = get_post_meta($post_id, "cnc_b2b_woocommerce_product_id", true);

            if ($columns == "listed" && $id_of_product && 'publish' == get_post_status($id_of_product)) { ?>
                <div class="cnc_b2b_product_listed">
                    <div class="listed_with_wooconnerce"></div>
                </div>
            <?php } else if ($columns == "listed") {  ?>
                <div class="cnc_b2b_product_listed">
                    <div class="not_listed_with_wooconnerce"></div>
                </div>
    <?php }
            break;
    }
}
add_filter('manage_pgs_products_posts_custom_column', 'cnc_b2b_action_button_details', 10, 2);


function cnc_b2b_sku_meta_boxes()
{
    add_meta_box('product-pricing', __('Product Pricing', 'product'), 'cnc_b2b_product_pricing_callback', "product");
    add_meta_box('product-sku', __('Product SKU', 'product'), 'cnc_b2b_sku_callback', "product");
    add_meta_box('order-sync', __('Order Synchronize', 'product'), 'cnc_b2b_manually_sync_order_callback', "shop_order");
}
add_action('add_meta_boxes', 'cnc_b2b_sku_meta_boxes');

function cnc_b2b_product_pricing_callback($post)
{
    $pricing = get_post_meta($post->ID, "reseller_pricing", true);
    $pricing = (array)$pricing;
    ?>
    <div class="reseller_pricing" style="display:flex;text-align: left;">

        <div style="width:50%;">
            <h1>Reseller Pricing</h1>
            <table>
                <?php

                foreach ($pricing as $key => $value) {
                    if ($key == "Images") {
                        continue;
                    }
                ?>
                    <tr>
                        <th style="border:1px solid black;padding: 10px;"><?php echo $key; ?></th>
                        <td style="border:1px solid black;padding: 10px;">
                            <?php if ($key != 'Stock Level' && $key != 'SKU' && $key != 'Title') {
                                echo '£';
                            }
                            echo $value;   ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <div style="width:50%;">
            <h1>Profit</h1>
            <?php $regular_price = get_post_meta($post->ID, "_regular_price", true); ?>
            <table>
                <tr>
                    <th style="border:1px solid black;padding: 10px;">Sale Price</th>
                    <td style="border:1px solid black;padding: 10px;"><?php echo  '£' . $regular_price; ?></td>

                </tr>
                <tr>
                    <th style="border:1px solid black;padding: 10px;">Profit</th>
                    <td style="border:1px solid black;padding: 10px;"><?php
                                                                        $profit = ($regular_price / 1.2) - $pricing['Dropship For 1'];
                                                                        echo '£' . round($profit, 2);
                                                                        ?></td>

                </tr>
                <tr>
                    <td colspan="2">
                        <p>These calculations give a rough profit and are based on being VAT registered, we always recommend doing your own calculations too.</p>
                    </td>
                </tr>
            </table>

        </div>
    </div>
<?php
}
function cnc_b2b_manually_sync_order_callback($post)
{
?>
    <div class="order_sync_coustom_box">
        <div class="order_sync_checkack_box">
            <input type="checkbox" id="is_cnc_b2b_order_sync" <?php if (get_post_meta($post->ID, "cnc_b2b_order_created", true)) {
                                                                    echo "checked='checked'";
                                                                } ?> onclick="return false;" />
            <label for="is_cnc_b2b_order_sync">Is Order Synchronize with Personalised Gift Supply ?</label>
        </div>
        <?php
        if (get_post_meta($post->ID, "cnc_b2b_order_tracking_id", true)) {
        ?>
            <div class="order_tracking_Info">
                <label>Order Tracking ID : </label>
                <input type="text" value="<?php echo get_post_meta($post->ID, "cnc_b2b_order_tracking_id", true); ?>" readonly />
            </div>
        <?php
        }
        ?>
        <div class="order_sync_manully">
            <input type="button" class="order_sync_manully_button" data-id="<?php echo $post->ID; ?>" value="Manully Synchronize" <?php if (get_post_meta($post->ID, "cnc_b2b_order_created", true)) {
                                                                                                                                        echo "disabled='disabled'";
                                                                                                                                    } ?> />
        </div>
    </div>
<?php
}

function cnc_b2b_sku_callback($post)
{
    $sku = get_post_meta($post->ID, 'cnc_b2b_bigcommerce_sku', true);
?>
    <div>
        <div>
            <lable>Product SKU : </lable><b><?php echo $sku; ?></b>
        </div>
    </div>
    <?php
}

add_action('woocommerce_order_status_changed', 'cnc_b2b_on_order_status_change', 10, 3);

function cnc_b2b_on_order_status_change($order_id, $old_status, $new_status)
{
    if (get_option("cnc_b2b_sync_order_type") == "sync_on_status_change") {
        $option_status = str_replace("wc-", "", get_option("cnc_b2b_sync_order_status"));
        if ($option_status == $new_status) {
            $order = wc_get_order($order_id);
            if ($order) {
                foreach ($order->get_items() as $item_id => $item) {
                    $product_id = $item->get_product_id();
                    if (get_post_meta($product_id, "cnc_b2b_bigcommerce_product", true) == "1") {
                        cnc_b2b_order_item_sync_to_pgs($order, $item_id, $item);
                    }
                }
            }
        }
    }
}

function b2b_cnc_add_custom_variant_option_after_product_title()
{
    $cnc_b2b_bigcommerce_modifier_data = json_decode(get_post_meta(get_the_ID(), "cnc_b2b_bigcommerce_modifier_data", true));
    $customiser_data = get_post_meta(get_the_ID(), "customiser_data", true);
    // echo "<pre>";
    // print_r($cnc_b2b_bigcommerce_modifier_data);
    // print_r($customiser_data);
    // echo "</pre>";
    foreach ($cnc_b2b_bigcommerce_modifier_data as $modifier) {
        if ($modifier->type == "rectangles") {
    ?>
            <div for="option-<?php echo esc_attr($modifier->id); ?>" class="bc-product-form__control bc-product-form__control--pick-list">
                <span class="bc-form__label bc-product-form__option-label bc-form-control-required"><?php echo esc_html("Design"); ?></span>
                <!-- data-js="product-form-option" and data-field="product-form-option-radio" are required -->
                <div class="bc-product-form__option-variants" data-js="product-form-option" data-field="product-form-option-radio">
                    <?php foreach ($modifier->option_values as $option) { ?>
                        <input type="radio" name="option[<?php echo esc_attr($modifier->id); ?>]" data-option-id="<?php echo esc_attr($modifier->id); ?>" id="option--<?php echo esc_attr($option->id); ?>" value="<?php echo esc_attr($option->id); ?>" data-js="bc-product-option-field" class="u-bc-visual-hide bc-product-variant__radio--hidden" required="required" />
                        <label for="option--<?php echo esc_attr($option->id); ?>" class="bc-product-variant__label">
                            <span class="bc-product-variant__label--pick-list">
                                <span class="bc-product-variant__label--title">
                                    <?php echo esc_html($option->label); ?>
                                </span>
                            </span>
                        </label>

                    <?php } ?>
                </div>

            </div>
<?php
        }
    }
}
add_action('woocommerce_single_product_summary', 'b2b_cnc_add_custom_variant_option_after_product_title', 20);

add_filter('woocommerce_loop_add_to_cart_link', 'cnc_b2b_shop_page_add_to_cart_callback', 10, 2);
function cnc_b2b_shop_page_add_to_cart_callback($button, $product)
{
    if (is_product_category() || is_shop()) {
        if (get_post_meta(get_the_ID(), "cnc_b2b_bigcommerce_product", true)) {
            $button_text = __("Personalise", "woocommerce");
            $button_link = $product->get_permalink();
            $button = '<div class="cnc_b2b_personalise_button">
                <div class="Personalise-btn"><a href="' . $button_link . '">
                    <button type="button" class="Pro-btn bcaddoncustomize">
                        <svg enable-background="new 0 0 24 24" height="24px" id="Layer_1" version="1.1" viewBox="0 0 24 24" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M21.635,6.366c-0.467-0.772-1.043-1.528-1.748-2.229c-0.713-0.708-1.482-1.288-2.269-1.754L19,1C19,1,21,1,22,2S23,5,23,5  L21.635,6.366z M10,18H6v-4l0.48-0.48c0.813,0.385,1.621,0.926,2.348,1.652c0.728,0.729,1.268,1.535,1.652,2.348L10,18z M20.48,7.52  l-8.846,8.845c-0.467-0.771-1.043-1.529-1.748-2.229c-0.712-0.709-1.482-1.288-2.269-1.754L16.48,3.52  c0.813,0.383,1.621,0.924,2.348,1.651C19.557,5.899,20.097,6.707,20.48,7.52z M4,4v16h16v-7l3-3.038V21c0,1.105-0.896,2-2,2H3  c-1.104,0-2-0.895-2-2V3c0-1.104,0.896-2,2-2h11.01l-3.001,3H4z"></path></svg>
                        <span>Personalise</span>
                    </button></a>
                </div>
            </div>';
            return $button;
        } else {
            return $button;
        }
    } else {
        return $button;
    }
}

function cnc_b2b_create_post_to_pgs_product($product)
{
	
	if($product['reseller_pricing'] && $product['RRP'] && ($product['meta']['_thumbnail_id'] || $product['reseller_pricing']['Images']!='')){
	    if (!$product['customiser_data']['varialble_option']) {
	        $args = array(
	            'post_type'  => 'pgs_products',
		    'post_status' => 'any',
	            'meta_query' => array(
	                array(
	                    'key'     => 'bigcommerce_sku',
	                    'value'   => $product['meta']['bigcommerce_sku'][0],
	                    'compare' => '=',
	                ),
	            ),
	        );
	        $query = new WP_Query($args);
	
	        if ($query->post_count > 0) {
	            $post_id = $query->posts[0]->ID;
	        } else {
	            $post = $product['post'];
	            $args = array(
	                'post_content'   => $post['post_content'],
	                'post_excerpt'   => $post['post_excerpt'],
	                'post_name'      => $post['post_name'],
	                'post_title'     => $post['post_title'],
	                'post_type'      => "pgs_products"
	            );
	
	            $post_id = wp_insert_post($args);
	        }
	
	        $metas = $product['meta'];
	        foreach ($metas as $key => $value) {
	            update_post_meta($post_id, $key, $value[0]);
	        }
	        update_post_meta($post_id, "customiser_data", $product['customiser_data']);
	        update_post_meta($post_id, "pgs_link", $product['pgs_link']);
	        update_post_meta($post_id, "reseller_pricing", $product['reseller_pricing']);
	        update_post_meta($post_id, "cnc_b2b_category", $product['category']);
	
	        return $post_id;
	    }
	    else{
	    	return false;
	    }
	}
	else{
    	return false;
    }
}

function cnc_b2b_create_product_for_wooconnerce($product_id, $is_publish)
{
    global $image_uploade_url;
    $post = get_post($product_id);
	$prices_data = get_post_meta($product_id,"reseller_pricing",true);
	$thumbnail = get_post_meta($product_id,"_thumbnail_id",true);
	//print_R($prices_data);
	if($prices_data && $prices_data['RRP']!='' && ($thumbnail || $prices_data['Images']!='')){
	    $product_args = array(
	        'post_type'  => 'product',
	        'meta_query' => array(
	            array(
	                'key'     => 'cnc_b2b_bigcommerce_sku',
	                'value'   => get_post_meta($product_id, "bigcommerce_sku", true),
	                'compare' => '=',
	            ),
	        )
	    );
	    $query = new WP_Query($product_args);
	    if ($query->post_count > 0) {
	        $post_id = $query->posts[0]->ID;
	    } else {
	        $args = array(
	            'post_content'   => $post->post_content,
	            'post_excerpt'   => $post->post_excerpt,
	            'post_name'      => $post->post_name,
	            'post_status'    => ($is_publish ? "publish" : "draft"),
	            'post_title'     => $post->post_title,
	            'post_type'      => "product",
	        );
	        $post_id = wp_insert_post($args);
	    }
	    $prices = get_post_meta($product_id, "reseller_pricing", true);
	
	    update_post_meta($post_id, "cnc_b2b_bigcommerce_sku", get_post_meta($product_id, "bigcommerce_sku", true));
	    update_post_meta($post_id, "cnc_b2b_bigcommerce_source_data", get_post_meta($product_id, "bigcommerce_source_data", true));
	    update_post_meta($post_id, "cnc_b2b_bigcommerce_modifier_data", get_post_meta($product_id, "bigcommerce_modifier_data", true));
	    update_post_meta($post_id, "cnc_b2b_csv_price_data", get_post_meta($product_id, "csv_price_data", true));
	    update_post_meta($post_id, "customiser_data", get_post_meta($product_id, "customiser_data", true));
	    update_post_meta($post_id, "cnc_b2b_product_id", $product_id);
	
	    if (get_option("cnc_b2b_import_category") == "1") {
	        if (get_post_meta($product_id, "cnc_b2b_category", true)) {
	            foreach (get_post_meta($product_id, "cnc_b2b_category", true) as $pgs_term) {
	                $category = get_term_by('name', $pgs_term['name'], 'product_cat');
	                if ($category) {
	                    wp_set_post_terms($post_id, $category->term_id, "product_cat", true);
	                } else {
	                    $term = wp_insert_term(
	                        $pgs_term['name'],   // the term 
	                        'product_cat', // the taxonomy
	                        array(
	                            'description' => $pgs_term['description'],
	                            'slug'        => $pgs_term['slug'],
	                        )
	                    );
	                    wp_set_post_terms($post_id, $term['term_id'], "product_cat", true);
	
	                    $image_is_exist = cnc_b2b_is_image_exist($pgs_term['background_image']);
	                    if ($image_is_exist) {
	                        $attachmentId = $image_is_exist;
	                    } else {
	                        $file = array();
	                        $file['name'] = "term-" . $pgs_term['slug'] . ".jpg";
	                        $file['tmp_name'] = download_url($pgs_term['background_image']);
	                        $attachmentId = media_handle_sideload($file);
	                        update_post_meta($attachmentId, "cnc_b2b_reference_url", $pgs_term['background_image']);
	                    }
	                    if (!is_wp_error($attachmentId)) {
	                        update_term_meta($term['term_id'], "thumbnail_id", $attachmentId);
	                    }
	                }
	            }
	        }
	    }
	    
		$regular_price = cnc_b2b_get_regular_price($prices);
	    update_post_meta($post_id, "_price", $regular_price);
	    update_post_meta($post_id, "_regular_price", $regular_price);
	    
	    //update_post_meta($post_id,"_sale_price",$prices[8]);
	    update_post_meta($post_id, "cnc_b2b_bigcommerce_product", true);
	    update_post_meta($product_id, "cnc_b2b_sync_with_woocommerce", true);
	    update_post_meta($product_id, "cnc_b2b_woocommerce_product_id", $post_id);
	    update_post_meta($post_id, "reseller_pricing", get_post_meta($product_id, "reseller_pricing", true));
	
	    //-----------------------------------------------------------------------------Thumbnail Image & Gallery Images-----------------------------------------------------------------------//
	    $images = explode(",", get_post_meta($product_id, "reseller_pricing", true)['Images']);
	
	    $thamnail_url = $image_uploade_url . "/uploads/Images/PlainImages/" . get_post_meta($product_id, "bigcommerce_sku", true) . ".jpg";
	    $image_is_exist = cnc_b2b_is_image_exist($thamnail_url);
	    if ($image_is_exist) {
	        $attachmentId = $image_is_exist;
	    } else {
	        $file = array();
	        $file['name'] = get_post_meta($product_id, "bigcommerce_sku", true) . ".jpg";
	        $file['tmp_name'] = download_url($thamnail_url);
	        $attachmentId = media_handle_sideload($file, $post_id);
	        update_post_meta($attachmentId, "cnc_b2b_reference_url", $thamnail_url);
	    }
	    if (!is_wp_error($attachmentId)) {
	        set_post_thumbnail($post_id, $attachmentId);
	    }
	
	    $gallery_images = array();
	    foreach ($images as $key => $image) {
	        $image_is_exist = cnc_b2b_is_image_exist($image_uploade_url . $image);
	        if ($image_is_exist) {
	            $attachmentId = $image_is_exist;
	        } else {
	            $file = array();
	            $file['name'] = $key . "-gallery-" . get_post_meta($product_id, "bigcommerce_sku", true) . ".jpg";
	            $file['tmp_name'] = download_url($image_uploade_url . $image);
	            $attachmentId = media_handle_sideload($file);
	            update_post_meta($attachmentId, "cnc_b2b_reference_url", $image_uploade_url . $image);
	        }
	        if (!is_wp_error($attachmentId)) {
	            $gallery_images[] = $attachmentId;
	        }
	    }
	    update_post_meta($post_id, '_product_image_gallery', implode(',', $gallery_images));
	    //-----------------------------------------------------------------------------Thumbnail Image & Gallery Images-----------------------------------------------------------------------//
	
	    return $post_id;
	}else{
		return false;
	}
}
global $engrave_fonts;
$engrave_fonts = apply_filters(
    'bc_addon_fonts',
    array(
        "timesnewroman" => "Times New Roman",
        "zapfchancery" => "Zapf Chancery",
        "vivaldi" => "Vivaldi",
        "palacescript" => "Palace Script",
        "oldenglish" => "Old English",
        "lobsterregular" => "Lobster-Regular",
        "harrington" => "Harrington",
        "greatVibesregular" => "GreatVibes-Regular",
        "frenchscript" => "French Script",
        "edwardianscript" => "Edwardian Script",
        "curlz" => "Curlz",
        "chicleregular" => "Chicle Regular",
        "evilempire" => "Evil Empire",
        "mtcorsva" => "MTCORSVA",
        "eras" => "Eras",
        "copplerplate" => "Coppler Plate",
        "cooper" => "Cooper",
        "bauhaus" => "Bauhaus",
        "trumpit" => "Trumpit",
        "popwarner" => "Pop Warner",
    )
);
ksort($engrave_fonts);


require __DIR__ . '/cnc-plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/cnc-group-it/cnc-b2b',
    __FILE__,
    'cnc-b2b'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');

//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('sdfsdfsdf');
