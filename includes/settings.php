<?php
$product_sync = false;
if(isset($_POST["verify"]) || isset($_POST['sync_data'])){
        ?>
        <?php
        $url="https://personalisedgiftsupply.com/api/reseller-api/v1/user/token";
        $args = array(
            'headers' => array(
              'Content-Type' => 'application/json',
              'token' => $_POST["tokan_name"],
              'username' => $_POST["pgs_username"],
            )
        );
        $responsedata=wp_remote_get($url,$args);
        $data=wp_remote_retrieve_body($responsedata);
        $body = json_decode($data);
        update_option("pgs_products_api_key",$_POST["tokan_name"]);
        update_option("pgs_username",$_POST["pgs_username"]);
        if($body->statusCode == 200){
            update_option("pgs_products_api_key_varify",true);
        }else{
            update_option("pgs_products_api_key_varify",false);
        }
}
if(isset($_POST['sync_data'])){
    $url="https://personalisedgiftsupply.com/api/reseller-api/v1/product/list";
    $args = array(
        'headers' => array(
          'Content-Type' => 'application/json',
          'token' => $_POST["tokan_name"],
          'username' => $_POST["pgs_username"],
        )
    );
    $responsedata=wp_remote_get($url,$args);
    $data=wp_remote_retrieve_body($responsedata);
    $body = json_decode($data);
    
    if($body->statusCode == 200){
        foreach($body->data as $product){
            if(!$product->customiser_data->varialble_option){
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
                
                // echo "<pre>";
                // 	print_R($product);
                // echo "</pre>";
                // if($product->post->ID == 2273111){
                // 	print_R($query->post_count);
                // }
                if($query->post_count > 0){
                    $post_id = $query->posts[0]->ID;
                }else{
                    $post = $product->post;
                    $args = array(
            			'post_content'   => $post->post_content,
            			'post_excerpt'   => $post->post_excerpt,
            			'post_name'      => $post->post_name,
            			'post_title'     => $post->post_title,
            			'post_type'      => "pgs_products"
            		);
            
            		$post_id = wp_insert_post( $args );
                }
                
                $metas = $product->meta;
                foreach($metas as $key => $value){
                    update_post_meta($post_id,$key,$value[0]);
                }
                update_post_meta($post_id,"customiser_data",$product->customiser_data);
                update_post_meta($post_id,"pgs_link",$product->pgs_link);
                update_post_meta($post_id,"reseller_pricing",$product->reseller_pricing);
                update_post_meta($post_id,"cnc_b2b_category",$product->category);
                $product_sync = true;
            }
        }
    }
    
    $fonts_url="https://personalisedgiftsupply.com/api/reseller-api/v1/content/fonts";
    $fonts_args = array(
        'headers' => array(
          'Content-Type' => 'application/json',
          'token' => $_POST["tokan_name"],
          'username' => $_POST["pgs_username"],
        )
    );
    $fonts_responsedata=wp_remote_get($fonts_url,$fonts_args);
    $fonts_data=wp_remote_retrieve_body($fonts_responsedata);
    $fonts_body = json_decode($fonts_data);
    update_option("cnc_b2b_user_specific_fonts",$fonts_body->data->user_fonts);
    update_option("cnc_b2b_fonts",$fonts_body->data->option_fonts);
    
    $clipart_url="https://personalisedgiftsupply.com/api/reseller-api/v1/content/clipart";
    $clipart_args = array(
        'headers' => array(
          'Content-Type' => 'application/json',
          'token' => $_POST["tokan_name"],
          'username' => $_POST["pgs_username"],
        )
    );
    $clipart_responsedata=wp_remote_get($clipart_url,$clipart_args);
    $clipart_data=wp_remote_retrieve_body($clipart_responsedata);
    $clipart_body = json_decode($clipart_data);
    
    update_option("cnc_b2b_cliparts",$clipart_body->data);
    update_product_list_with_pgs();
}
    
$apikey = get_option("pgs_products_api_key");
$varify = get_option("pgs_products_api_key_varify");
$username = get_option("pgs_username")
    
?>
<div class="cnc_b2b_settings_page">
    <div class="page_title">
        <h1>Settings</h1>
    </div>
    <form class="pgs_form" method="POST">
        <div>
            <?php
            if($product_sync){ 
            ?>
                <div class="product_sync"> Product Synchronize with Personalise Gift Suppy Successfully ... </div>
            <?php
            }
            ?>
            <div class="info_section">
                <div class="token_lebel"><h3>Username :</h3></div>
                <div class="token_and_varification">
                    <div class="pgs_col token_input">
                        <input name="pgs_username" value="<?php if($username){ echo $username; } ?>" />
                    </div>
                </div>
            </div>
            <div class="info_section">
                <div class="token_lebel"><h3>Api Key :</h3></div>
                <div class="token_and_varification">
                    <div class="pgs_col token_input">
                        <textarea name="tokan_name" cols="75" rows="10"><?php if($apikey){ echo $apikey; } ?></textarea>
                    </div>
                </div>
            </div>
            <div class="pgs_button">
                <input class="varification_button" type="submit" name="verify" value="Verify">
                <?php 
                if($varify == "1"){
                    ?>
                    <div class="varification_wrapper"><span class="varification varification-true"></span>Validation Successful</div>
                    <?php
                }else{
                    ?>
                    <div class="varification_wrapper"><span class="varification varification-false"></span> Validation Fail</div>
                    <?php
                }
                
                ?>
            </div>
            <?php
            if($varify == "1"){
            ?>
                <div class="pgs_button">
                    <input type="submit" name="sync_data" value="Sync Data">
                </div>
            <?php
            }
            ?>
        </div>
    </form>
    <p>Please contact us on <a href="sales@personalisedgiftsupply.com">sales@personalisedgiftsupply.com</a> for your API key.</p>
</div>