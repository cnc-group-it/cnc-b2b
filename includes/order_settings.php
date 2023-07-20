<?php
if (isset($_POST['cnc_b2b_save_sync_order_setting'])) {
    update_option("cnc_b2b_sync_order_type", $_POST['cnc_b2b_sync_order_type']);
    update_option("cnc_b2b_sync_order_status", $_POST['cnc_b2b_sync_order_status']);
    update_option("cnc_b2b_sync_order_status_automatically", (isset($_POST["cnc_b2b_sync_order_status_automatically"]) && $_POST['cnc_b2b_sync_order_status_automatically'] == "on") ? "1" : "0");
    update_option("cnc_b2b_sync_order_status_if_other_product_also", (isset($_POST["cnc_b2b_sync_order_status_if_other_product_also"]) && $_POST["cnc_b2b_sync_order_status_if_other_product_also"] == "on") ? "1" : "0");
    update_option("cnc_b2b_import_category", (isset($_POST["cnc_b2b_import_category"]) && $_POST["cnc_b2b_import_category"] == "on") ? "1" : "0");
    update_option("cnc_b2b_import_all", (isset($_POST["cnc_b2b_import_all"]) && $_POST["cnc_b2b_import_all"] == "on") ? "1" : "0");
    update_option("cnc_b2b_margin_for_ragular_price", $_POST["cnc_b2b_margin_for_ragular_price"]);
    update_option("cnc_b2b_round_up_the_nearest", $_POST["cnc_b2b_round_up_the_nearest"]);
    update_option("cnc_b2b_price_for_product", $_POST["cnc_b2b_price_for_product"]);
    update_option("cnc_b2b_maximum_rrp", $_POST["cnc_b2b_maximum_rrp"]);
    update_option("cnc_b2b_product_ranges", $_POST["cnc_b2b_product_ranges"]);
    update_option("cnc_b2b_next_day_shipping", $_POST["cnc_b2b_next_day_shipping"]);
    update_option('cnc_b2b_photography_images_as_main_image',$_POST["cnc_b2b_photography_images_as_main_image"]);
}

$cnc_b2b_import_all = get_option("cnc_b2b_import_all") ? get_option("cnc_b2b_import_all") : "0";
$cnc_b2b_import_category = get_option("cnc_b2b_import_category") ? get_option("cnc_b2b_import_category") : "0";
$cnc_b2b_sync_order_type = get_option("cnc_b2b_sync_order_type");
$cnc_b2b_sync_order_status = get_option("cnc_b2b_sync_order_status");
$cnc_b2b_sync_order_status_automatically = get_option("cnc_b2b_sync_order_status_automatically") ? get_option("cnc_b2b_sync_order_status_automatically") : "0";
$cnc_b2b_sync_order_status_if_other_product_also = get_option("cnc_b2b_sync_order_status_if_other_product_also") ? get_option("cnc_b2b_sync_order_status_if_other_product_also") : "0";
$cnc_b2b_margin_for_ragular_price = get_option("cnc_b2b_margin_for_ragular_price");
$cnc_b2b_round_up_the_nearest = get_option("cnc_b2b_round_up_the_nearest");
$cnc_b2b_price_for_product = get_option("cnc_b2b_price_for_product");
$cnc_b2b_maximum_rrp = get_option("cnc_b2b_maximum_rrp");
$cnc_b2b_product_ranges = get_option("cnc_b2b_product_ranges");
$cnc_b2b_next_day_shipping = get_option("cnc_b2b_next_day_shipping");
$cnc_b2b_photography_images_as_main_image = get_option("cnc_b2b_photography_images_as_main_image");
?>
<div class="cnc_b2b_order_settings_page">
    <div class="page_title cnc_special_title">
        <h1>Order Settings</h1>
    </div>
    <div class="cnc_b2b_order_setting_content">
        <form name="order_settins" method="POST">
            <div class="order_type_wrap">
                <h3>Order Synchronize Type</h3>
                <p>Use "manually synchronised" to sync orders manually with Personalised Gift Supply, this is used if you want to send all orders at the end of each day.</p>
                <p>Synchronise when order status change will update orders to processed when they're dispatched from Personalised Gift Supply</p>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_sync_order_type" value="manually_sync" id="manually_sync" <?php if ($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "manually_sync") {
                                                                                                                    echo "checked='checked'";
                                                                                                                } ?> />
                    <label for="manually_sync">Manually Synchronize</label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_sync_order_type" value="sync_on_status_change" id="sync_on_status_change" <?php if ($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "sync_on_status_change") {
                                                                                                                                    echo "checked='checked'";
                                                                                                                                } ?> />
                    <label for="sync_on_status_change">Synchronize When Order Status Change</label>
                </div>
            </div>

            <div class="order_type_wrap cnc_b2b_sync_order_status_wrap" <?php if ($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "manually_sync") {
                                                                            echo "style='display: none;'";
                                                                        } ?>>
                <h3>Select Order Status</h3>
                <p>Default setting is "Processing" which will only send an order through to us when the order is paid</p>
                <div class="select_status_wrap">
                    <select name="cnc_b2b_sync_order_status">
                        <?php
                        foreach (wc_get_order_statuses() as $key => $value) {
                        ?>
                            <option value="<?php echo $key; ?>" <?php if ($cnc_b2b_sync_order_status && $cnc_b2b_sync_order_status == $key) {
                                                                    echo "selected='selected'";
                                                                } ?>><?php echo $value; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3>Next Day Shipping</h3>
                <p>When this is enabled if you charge shipping cost the order will be marked as paid shipping and upgraded to next day delivery, this will incur a charge on your account.</p>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_next_day_shipping" value="enable_next_day_shipping" id="enable_next_day_shipping" <?php if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "enable_next_day_shipping") {
                                                                                                                                            echo "checked='checked'";
                                                                                                                                        } ?> />
                    <label for="enable_next_day_shipping">Enable next day shipping</label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_next_day_shipping" value="all_orders_to_next_day" id="all_orders_to_next_day" <?php if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "all_orders_to_next_day") {
                                                                                                                                        echo "checked='checked'";
                                                                                                                                    } ?> />
                    <label for="all_orders_to_next_day">Upgrade all orders to next day regardless of shipping cost</label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_next_day_shipping" value="disable_next_day_shipping" id="disable_next_day_shipping" <?php if ($cnc_b2b_next_day_shipping && $cnc_b2b_next_day_shipping == "disable_next_day_shipping") {
                                                                                                                                                echo "checked='checked'";
                                                                                                                                            } ?> />
                    <label for="disable_next_day_shipping">Disable next day shipping</label>
                </div>
            </div>
            <div class="order_type_wrap">
                <h3>Automatically Process Orders</h3>
                <p>If this is ticked your orders will automatically be updated to completed once the order has been dispatched from Personalised Gift Supply.</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_sync_order_status_automatically" id="cnc_b2b_sync_order_status_automatically" <?php if ($cnc_b2b_sync_order_status_automatically && $cnc_b2b_sync_order_status_automatically == "1") {
                                                                                                                                            echo "checked='checked'";
                                                                                                                                        } ?> />
                    <label for="cnc_b2b_sync_order_status_automatically">Automictically Process Orders</label>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3>Process Shared Orders</h3>
                <p>By default this is unticked, if you sell a mix of our products and your own, when ticked this will mark the order as complete once we have processed the order from our side</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_sync_order_status_if_other_product_also" id="cnc_b2b_sync_order_status_if_other_product_also" <?php if ($cnc_b2b_sync_order_status_if_other_product_also && $cnc_b2b_sync_order_status_if_other_product_also == "1") {
                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                        } ?> />
                    <label for="cnc_b2b_sync_order_status_if_other_product_also">Automatically process mixed orders</label>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3>Import Category ?</h3>
                <p>When enabled this will import our recommended categories into your site and list products into those categories.</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_import_category" id="cnc_b2b_import_category" <?php if ($cnc_b2b_import_category && $cnc_b2b_import_category == "1") {
                                                                                                            echo "checked='checked'";
                                                                                                        } ?> />
                    <label for="cnc_b2b_import_category">Enabled</label>
                </div>
            </div>

            <div class="order_type_wrap">
                <h3>Product Ranges</h3>
                <p>Please check the product ranges that you would like to be displayed on your website.</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="personalised_products" id="personalised_products" <?php if ($cnc_b2b_product_ranges && in_array("personalised_products", $cnc_b2b_product_ranges)) {
                                                                                                                                        echo "checked='checked'";
                                                                                                                                    }
                                                                                                                                    ?> />
                    <label for="personalised_products">Personalised Products</label>
                </div>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="decorated_glassware" id="decorated_glassware" <?php if ($cnc_b2b_product_ranges && in_array("decorated_glassware", $cnc_b2b_product_ranges)) {
                                                                                                                                    echo "checked='checked'";
                                                                                                                                }
                                                                                                                                ?> />
                    <label for="decorated_glassware">Decorated Glassware</label>
                </div>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="engraved_trophies" id="engraved_trophies" <?php if ($cnc_b2b_product_ranges && in_array("engraved_trophies", $cnc_b2b_product_ranges)) {
                                                                                                                                echo "checked='checked'";
                                                                                                                            }
                                                                                                                            ?> />
                    <label for="engraved_trophies">Engraved Trophies</label>
                </div>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_product_ranges[]" value="say_it_with_glass" id="say_it_with_glass" <?php if ($cnc_b2b_product_ranges && in_array("say_it_with_glass", $cnc_b2b_product_ranges)) {
                                                                                                                                echo "checked='checked'";
                                                                                                                            }
                                                                                                                            ?> />
                    <label for="say_it_with_glass">Say it with Glass</label>
                </div>
            </div>
            <!--......................................................................................................................................................................-->
            <div class="order_type_wrap">
                <h3>Enable lifestyle image as main product image</h3>
                <p>When selected, if available a lifestyle image will be used as the main product image rather than a white background image.</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_photography_images_as_main_image" id="photography_images_as_main_image" <?php if ($cnc_b2b_photography_images_as_main_image == "on") {
                                                                                                                                echo "checked='checked'";
                                                                                                                            } ?>/>
                    <label for="photography_images_as_main_image">Enable</label>
                </div>
            </div>
            <!--......................................................................................................................................................................-->

            <div class="order_type_wrap">
                <h3>Import All ?</h3>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_import_all" id="cnc_b2b_import_all" <?php if ($cnc_b2b_import_all && $cnc_b2b_import_all == "1") {
                                                                                                    echo "checked='checked'";
                                                                                                } ?> />
                    <label for="cnc_b2b_import_all">Import All ?</label>
                </div>
                <?php if ($cnc_b2b_import_all && $cnc_b2b_import_all == "1") {
                    global $wpdb;
                    $count = $wpdb->get_var("
                        SELECT count(*) FROM " . $wpdb->prefix . "actionscheduler_actions 
                            WHERE 
                            hook = 'cnc_b2b_fatch_singal_page' AND
                            status = 'pending'
                    ");
                ?>
                    <div>
                        <p><b>Note : </b><?php echo $count; ?> Actions Left. <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=wc-status&tab=action-scheduler&status=pending&s=cnc_b2b_fatch_singal_page">View Pending Action</a></p>
                    </div>
                <?php
                } ?>
            </div>

            <div class="order_type_wrap">
                <h3>Pricing ?</h3>
                <p>Here you're able to state how you would like your products priced.</p>
                <p>Set my own pricing - Will initially pull our RRP but you're then able to manually set each product to the price you would like</p>
                <p>Suggested RRP - This will take use our RRP and continually update the RRP as products become cheaper or more expensive your RRP will fluctuate up and down making you roughly a 35% margin on each item.</p>
                <p>Custom Margin - This will allow you to set your own margin, after this has been selected you will need to click save which will enable two extra fields "Margin" and "Deduct from pricing" once your margin has been set your products will be rounded up to the nearest pound you can then set the value you wish to deduct from the price, for example you wish for your products to round to the nearest 99p you would select 0.01 in this field.</p>
                <div class="radio_wrap">
                    <select class="pricing_option" name="cnc_b2b_price_for_product">
                        <option value="set_own_price" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "set_own_price") {
                                                            echo "selected";
                                                        } ?>>Set my own pricing</option>
                        <option value="suggested_rrp" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "suggested_rrp") {
                                                            echo "selected";
                                                        } ?>>Suggested RRP</option>
                        <option value="custom_margin" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "custom_margin") {
                                                            echo "selected";
                                                        } ?>>Custom Margin</option>
                    </select>
                </div>
            </div>

            <div class="cnc_b2b_margin_pricing" <?php if ($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "custom_margin") {
                                                    echo "style='display: block;'";
                                                } else {
                                                    echo "style='display: none;'";
                                                } ?>>
                <div class="order_type_wrap">
                    <h3>Margin: </h3>
                    <div class="radio_wrap margin_input">
                        <input type="text" name="cnc_b2b_margin_for_ragular_price" id="cnc_b2b_margin_for_ragular_price" value="<?php if ($cnc_b2b_margin_for_ragular_price) {
                                                                                                                                    echo $cnc_b2b_margin_for_ragular_price;
                                                                                                                                } ?>" />
                        <span> %</span>
                    </div>
                    <p class="cnc_b2b_margin_error" <?php if ((int)$cnc_b2b_margin_for_ragular_price < 1 || (int)$cnc_b2b_margin_for_ragular_price > 99) {
                                                        echo "style='display: block;'";
                                                    } else {
                                                        echo "style='display: none;'";
                                                    } ?>>Margin should be less then 99 and greter then 1 or valid Integer</p>
                </div>

                <div class="order_type_wrap">
                    <h3>Deduct from pricing : </h3>
                    <div class="radio_wrap">
                        <input type="text" name="cnc_b2b_round_up_the_nearest" id="cnc_b2b_round_up_the_nearest" value="<?php if ($cnc_b2b_round_up_the_nearest) {
                                                                                                                            echo $cnc_b2b_round_up_the_nearest;
                                                                                                                        } ?>" />
                    </div>
                </div>
            </div>

            <div class="order_type_wrap" style="display:none">
                <h3>Maximum RRP :</h3>
                <div class="radio_wrap">
                    <input type="text" name="cnc_b2b_maximum_rrp" id="cnc_b2b_maximum_rrp" value="<?php if ($cnc_b2b_maximum_rrp) {
                                                                                                        echo $cnc_b2b_maximum_rrp;
                                                                                                    } ?>" />
                </div>
            </div>
            <div class="order_type_wrap">
                <div class="pgs_button">
                    <input type="submit" name="cnc_b2b_save_sync_order_setting" value="Save Settings" />
                </div>
            </div>
        </form>
    </div>
</div>