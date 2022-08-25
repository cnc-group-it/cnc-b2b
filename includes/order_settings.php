<?php 
    if(isset($_POST['cnc_b2b_save_sync_order_setting'])){
        update_option("cnc_b2b_sync_order_type",$_POST['cnc_b2b_sync_order_type']);
        update_option("cnc_b2b_sync_order_status",$_POST['cnc_b2b_sync_order_status']);
        update_option("cnc_b2b_sync_order_status_automatically",$_POST['cnc_b2b_sync_order_status_automatically'] == "on" ? "1" : "0");
        update_option("cnc_b2b_sync_order_status_if_other_product_also",$_POST["cnc_b2b_sync_order_status_if_other_product_also"]=="on"?"1":"0");
    }
    
    $cnc_b2b_sync_order_type = get_option("cnc_b2b_sync_order_type");
    $cnc_b2b_sync_order_status = get_option("cnc_b2b_sync_order_status");
    $cnc_b2b_sync_order_status_automatically = get_option("cnc_b2b_sync_order_status_automatically");
    $cnc_b2b_sync_order_status_if_other_product_also = get_option("cnc_b2b_sync_order_status_if_other_product_also");
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
                <div class="pgs_button">
                        <input type="submit" name="cnc_b2b_save_sync_order_setting" value="Save Settings"/>
                </div>
            </div>
        </form>
    </div>
</div>