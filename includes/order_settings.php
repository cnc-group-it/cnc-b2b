<?php 
    if(isset($_POST['cnc_b2b_save_sync_order_setting'])){
        update_option("cnc_b2b_sync_order_type",$_POST['cnc_b2b_sync_order_type']);
        update_option("cnc_b2b_sync_order_status",$_POST['cnc_b2b_sync_order_status']);
        update_option("cnc_b2b_sync_order_status_automatically",$_POST['cnc_b2b_sync_order_status_automatically'] == "on" ? "1" : "0");
        update_option("cnc_b2b_sync_order_status_if_other_product_also",$_POST["cnc_b2b_sync_order_status_if_other_product_also"]=="on"?"1":"0");
        update_option("cnc_b2b_import_category",$_POST["cnc_b2b_import_category"]=="on"?"1":"0");
        update_option("cnc_b2b_import_all",$_POST["cnc_b2b_import_all"]=="on"?"1":"0");
        update_option("cnc_b2b_dynamic_pricing",$_POST["cnc_b2b_dynamic_pricing"]=="on"?"1":"0");
        update_option("cnc_b2b_margin_for_ragular_price",$_POST["cnc_b2b_margin_for_ragular_price"]);
        update_option("cnc_b2b_round_up_the_nearest",$_POST["cnc_b2b_round_up_the_nearest"]);
        update_option("cnc_b2b_price_for_product",$_POST["cnc_b2b_price_for_product"]);
        
    }
    
    $cnc_b2b_import_all = get_option("cnc_b2b_import_all");
    $cnc_b2b_import_category = get_option("cnc_b2b_import_category");
    $cnc_b2b_sync_order_type = get_option("cnc_b2b_sync_order_type");
    $cnc_b2b_sync_order_status = get_option("cnc_b2b_sync_order_status");
    $cnc_b2b_sync_order_status_automatically = get_option("cnc_b2b_sync_order_status_automatically");
    $cnc_b2b_sync_order_status_if_other_product_also = get_option("cnc_b2b_sync_order_status_if_other_product_also");
    $cnc_b2b_dynamic_pricing = get_option("cnc_b2b_dynamic_pricing");
    $cnc_b2b_margin_for_ragular_price = get_option("cnc_b2b_margin_for_ragular_price");
    $cnc_b2b_round_up_the_nearest = get_option("cnc_b2b_round_up_the_nearest");
    $cnc_b2b_price_for_product = get_option("cnc_b2b_price_for_product");
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
                    <input type="radio" name="cnc_b2b_sync_order_type" value="manually_sync" id="manually_sync" <?php if($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "manually_sync"){ echo "checked='checked'"; } ?> />
                    <label for="manually_sync">Manually Synchronize</label>
                </div>
                <div class="radio_wrap">
                    <input type="radio" name="cnc_b2b_sync_order_type" value="sync_on_status_change" id="sync_on_status_change" <?php if($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "sync_on_status_change"){ echo "checked='checked'"; } ?> />
                    <label for="sync_on_status_change">Synchronize When Order Status Change</label>
                </div>
            </div>
            
            <div class="order_type_wrap cnc_b2b_sync_order_status_wrap" <?php if($cnc_b2b_sync_order_type && $cnc_b2b_sync_order_type == "manually_sync"){ echo "style='display: none;'"; } ?> >
                <h3>Select Order Status</h3>
                <p>Default setting is "Processing" which will only send an order through to us when the order is paid</p>
                <div class="select_status_wrap">
                    <select name="cnc_b2b_sync_order_status">
                        <?php 
                            foreach(wc_get_order_statuses() as $key => $value){
                                ?>
                                    <option value="<?php echo $key; ?>" <?php if($cnc_b2b_sync_order_status && $cnc_b2b_sync_order_status == $key){ echo "selected='selected'"; } ?> ><?php echo $value; ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="order_type_wrap">
                <h3>Automatically Process Orders</h3>
                <p>If this is ticked your orders will automatically be updated to completed once the order has been dispatched from Personalised Gift Supply.</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_sync_order_status_automatically" id="cnc_b2b_sync_order_status_automatically" <?php if($cnc_b2b_sync_order_status_automatically && $cnc_b2b_sync_order_status_automatically == "1"){ echo "checked='checked'"; } ?> />
                    <label for="cnc_b2b_sync_order_status_automatically">Order Status Change Automatically Synchronize with CNC</label>
                </div>
            </div>
            
            <div class="order_type_wrap">
                <h3>Process Shared Orders</h3>
                <p>By default this is unticked, if you sell a mix of our products and your own, when ticked this will mark the order as complete once we have processed the order from our side</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_sync_order_status_if_other_product_also" id="cnc_b2b_sync_order_status_if_other_product_also" <?php if($cnc_b2b_sync_order_status_if_other_product_also && $cnc_b2b_sync_order_status_if_other_product_also == "1"){ echo "checked='checked'"; } ?> />
                    <label for="cnc_b2b_sync_order_status_if_other_product_also">Automatically process mixed orders</label>
                </div>
            </div>
            
            <div class="order_type_wrap">
                <h3>Import Category ?</h3>
                <p>When enabled this will import our recommended categories into your site and list products into those categories.</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_import_category" id="cnc_b2b_import_category" <?php if($cnc_b2b_import_category && $cnc_b2b_import_category == "1"){ echo "checked='checked'"; } ?> />
                    <label for="cnc_b2b_import_category">Enabled</label>
                </div>
            </div>
            
            <div class="order_type_wrap">
                <h3>Import All ?</h3>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_import_all" id="cnc_b2b_import_all" <?php if($cnc_b2b_import_all && $cnc_b2b_import_all == "1"){ echo "checked='checked'"; } ?> />
                    <label for="cnc_b2b_import_all">Import All ?</label>
                </div>
                <?php if($cnc_b2b_import_all && $cnc_b2b_import_all == "1"){
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
                <h3>Dynamic Pricing ?</h3>
                <p>This I believe is now redundant as it has been superseded by the below option</p>
                <div class="radio_wrap">
                    <input type="checkbox" name="cnc_b2b_dynamic_pricing" id="cnc_b2b_dynamic_pricing" <?php if($cnc_b2b_dynamic_pricing && $cnc_b2b_dynamic_pricing == "1"){ echo "checked='checked'"; } ?> />
                    <label for="cnc_b2b_dynamic_pricing">Enabled ?</label>
                </div>
            </div>
            
            <div class="order_type_wrap">
                <h3>Pricing  ?</h3>
                <p>Here you're able to state how you would like your products priced.</p>
                <p>Set my own pricing - Will initially pull our RRP but you're then able to manually set each product to the price you would like</p>
                <p>Suggested RRP - This will take use our RRP and continually update the RRP as products become cheaper or more expensive your RRP will fluctuate up and down making you roughly a 35% margin on each item.</p>
                <p>Custom Margin - This will allow you to set your own margin, after this has been selected you will need to click save which will enable two extra fields "Margin" and  "Deduct from pricing" once your margin has been set your products will be rounded up to the nearest pound you can then set the value you wish to deduct from the price, for example you wish for your products to round to the nearest 99p you would select 0.01 in this field.</p>
                <div class="radio_wrap">
                	<select class="pricing_option" name="cnc_b2b_price_for_product">
                		<option value="set_own_price" <?php if($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "set_own_price"){ echo "selected"; } ?>>Set my own pricing</option>
                		<option value="suggested_rrp" <?php if($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "suggested_rrp"){ echo "selected"; } ?>>Suggested RRP</option>
                		<option value="custom_margin" <?php if($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "custom_margin"){ echo "selected"; } ?>>Custom Margin</option>
                	</select>
                </div>
            </div>
            
            <div class="cnc_b2b_margin_pricing" <?php if($cnc_b2b_price_for_product && $cnc_b2b_price_for_product == "custom_margin"){ echo "style='display: block;'"; }else{ echo "style='display: none;'"; } ?>>
	            <div class="order_type_wrap">
	                <h3>Margin: </h3>
	                <div class="radio_wrap">
	                    <input type="text" name="cnc_b2b_margin_for_ragular_price" id="cnc_b2b_margin_for_ragular_price" value="<?php if($cnc_b2b_margin_for_ragular_price){ echo $cnc_b2b_margin_for_ragular_price; } ?>" />
	                </div>
	            </div>
	            
	            <div class="order_type_wrap">
	                <h3>Deduct from pricing : </h3>
	                <div class="radio_wrap">
	                    <input type="text" name="cnc_b2b_round_up_the_nearest" id="cnc_b2b_round_up_the_nearest" value="<?php if($cnc_b2b_round_up_the_nearest){ echo $cnc_b2b_round_up_the_nearest; } ?>" />
	                </div>
	            </div>
            </div>
            <div class="order_type_wrap">
                <div class="pgs_button">
                        <input type="submit" name="cnc_b2b_save_sync_order_setting" value="Save Settings"/>
                </div>
            </div>
        </form>
    </div>
</div>