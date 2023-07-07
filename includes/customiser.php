<?php
add_action("woocommerce_after_add_to_cart_quantity", "cnc_b2b_add_personalise_button_product_page", 111);
function cnc_b2b_add_personalise_button_product_page()
{
    global $engrave_fonts;

    $postdata = (array)get_post_meta(get_the_ID(), "customiser_data", true);
    if (in_array($postdata['product_type_cnc'], array('print', 'engrave')) && get_post_meta(get_the_ID(), "cnc_b2b_bigcommerce_product", true) == "1" && !has_term(array("kings-coronation"), 'product_cat') && !has_term(array("decorated-glassware"), 'product_cat')) :
?>
        <div class="cnc_b2b_personalise_button">
            <div class="Personalise-btn">
                <button type="button" class="Pro-btn bcaddoncustomize">
                    <svg enable-background="new 0 0 24 24" height="24px" id="Layer_1" version="1.1" viewBox="0 0 24 24" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path d="M21.635,6.366c-0.467-0.772-1.043-1.528-1.748-2.229c-0.713-0.708-1.482-1.288-2.269-1.754L19,1C19,1,21,1,22,2S23,5,23,5  L21.635,6.366z M10,18H6v-4l0.48-0.48c0.813,0.385,1.621,0.926,2.348,1.652c0.728,0.729,1.268,1.535,1.652,2.348L10,18z M20.48,7.52  l-8.846,8.845c-0.467-0.771-1.043-1.529-1.748-2.229c-0.712-0.709-1.482-1.288-2.269-1.754L16.48,3.52  c0.813,0.383,1.621,0.924,2.348,1.651C19.557,5.899,20.097,6.707,20.48,7.52z M4,4v16h16v-7l3-3.038V21c0,1.105-0.896,2-2,2H3  c-1.104,0-2-0.895-2-2V3c0-1.104,0.896-2,2-2h11.01l-3.001,3H4z"></path>
                    </svg>
                    <span>Personalise</span>
                </button>
                <div class="quantity-notice-second">
                    <div>If you require <span>more than 1</span> of the same item, <span>but with different customised text.</span> <br>
                        Please make sure to press the “ADD TO CART” button above, after entering the first custom text. Then simply repeat the process for subsequent items.</div>
                </div>
            </div>
            <div class="right_side_div">
                <div class="customisation_details">
                    <div class="fullwidth font-customisation-result" data-id="font">
                        <div class="width_20">Font</div>
                        <div class="width_70 ">&nbsp;</div>
                        <input type="hidden" name="engrave_fonts" class="engrave_fonts" value="" />
                    </div>
                    <?php
                    for ($x = 1; $x <= 10; $x++) {
                    ?>
                        <div class="fullwidth font-customisation-result" data-id="<?php echo $x; ?>">
                            <div class="width_70"></div>
                            <div class="width_20 ">&nbsp;</div>
                            <input type="hidden" name="font_value_<?php echo $x; ?>" class="engrave_text_box" value="" />
                        </div>
                    <?php
                    }
                    ?>
                    <input type="hidden" name="engrave_product_sku" class="engrave_product_sku" value="<?php echo get_post_meta(get_the_ID(), "cnc_b2b_bigcommerce_sku", true); ?>" />
                    <input type="hidden" name="clipart" class="engrave_clipart" value="" />
                    <input type="hidden" name="font_color" class="engrave_font_color" value="" />
                    <div class="fullwidth notice-centeralised">All text will be centralised</div>
                </div>
            </div>
            <?php if ($postdata['product_type_cnc'] == 'print') :
                $path = $postdata['previewimage'];
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                global $cnc_b2b_url;
            ?>
                <input type="hidden" id="fileupload" value="<?php echo $cnc_b2b_url . 'includes/fileupload.php'; ?>" />
                <input type="hidden" id="frameurl" value="<?php echo str_replace('secureservercdn.net/160.153.137.170/', '', $postdata['previewimage']); ?>" />
                <input type="hidden" id="uploadNewUrl" value="" />
                <input type="hidden" id="originalWidth" value="<?php echo $postdata['print_image_width']; ?>" />
                <input type="hidden" id="originalHeight" value="<?php echo $postdata['print_image_height']; ?>" />
                <input type="hidden" id="imageLeftBase" value="<?php echo $postdata['print_position_left']; ?>" />
                <input type="hidden" id="imageTopBase" value="<?php echo $postdata['print_position_top']; ?>" />
                <input type="hidden" id="imageLeft" value="" />
                <input type="hidden" id="imageTop" value="" />
                <input type="hidden" id="imageRotation" value="" />
                <input type="hidden" id="print_url" name="print_url" value="" />
                <input type="hidden" id="image_url" name="image_url" value="" />


                <div class="customizationpopup" style="display:none;">
                    <div class="width_100 headSection">
                        <h1>Customise Your Product</h1>
                        <span><button type="button" onclick="closepopup()" class="closeIcon"></button></span>
                    </div>
                    <div class="width_100 contentWrapper print">
                        <div class="width_50 left">
                            <div class="print_wrap" id="screenshort-wrapper" style="
                        width : <?php echo $postdata['print_image_width']; ?>px;
                        height : <?php echo $postdata['print_image_height']; ?>px;
                        ">
                                <!--<img src="<?php echo str_replace('secureservercdn.net/160.153.137.170/', '', $postdata['previewimage']); ?>" style="-->
                                <img src="<?php echo $base64; ?>" style="
                        width : <?php echo $postdata['print_image_width']; ?>px;
                        height : <?php echo $postdata['print_image_height']; ?>px;
                        " />
                                <div class="printarea" style="
                            width: <?php echo $postdata['print_width']; ?>px;
                            height: <?php echo $postdata['print_height']; ?>px;
                            margin-top: <?php echo $postdata['print_position_top']; ?>px;
                            margin-left: <?php echo $postdata['print_position_left']; ?>px;
                            ">
                                    <div id="imageWrapper" class="defaultState" style="
                            width: <?php echo ($postdata['print_width'] - 10); ?>px;
                            height: <?php echo ($postdata['print_height'] - 10); ?>px;" hidden>
                                        <img id="imgLogo" src="" alt="" />
                                    </div>
                                </div>
                            </div>
                            <div class="notice width_100">Use your mouse to scale, move & rotate your image</div>
                        </div>
                        <div class="width_50 right">
                            <div class="width_100 mobile_bc_action_button">
                                <div class="width_30">
                                    <div class="inline-block rotate-left"><i class="fa fa-undo"></i></div>
                                    <div class="inline-block rotate-right"><i class="fa fa-repeat"></i></div>
                                </div>
                                <div class="width_30">
                                    <div class="inline-block mv-left"><i class="fa fa-arrow-left"></i></div>
                                    <div class="inline-block mv-right"><i class="fa fa-arrow-right"></i></div>
                                    <div class="inline-block mv-up"><i class="fa fa-arrow-up"></i></div>
                                    <div class="inline-block mv-down"><i class="fa fa-arrow-down"></i></div>
                                </div>
                                <div class="width_30">
                                    <div class="inline-block zoomin"><i class="fa fa-search-minus"></i></div>
                                    <div class="inline-block zoomout"><i class="fa fa-search-plus"></i></div>
                                </div>
                            </div>
                            <h2>Image Upload</h2>
                            <div class="width_100" />
                            <input type="file" accept="jpg,jpeg,png,gif" name="userfileprint" onchange="readURL(this,'print')" />
                        </div>
                        <div class="width_100 remove_image">
                            <button onclick="remove_image()" class="remove_image">Remove Image</button>
                        </div>
                        <div class="width_100 remove_image">

                        </div>
                        <div class="width_100">
                            <button onclick="downloadimage()" class="confirm_print">Confirm Customisation</button>
                        </div>
                    </div>
                </div>
        </div>
    <?php
            endif; ?>
    <?php if ($postdata['product_type_cnc'] == 'engrave') : ?>
        <div class="customizationpopup" style="display:none;">
            <?php


            $engrave_user_fonts = array();
            ?>
            <style id="personalised-style">
                <?php
                $user_specific_fonts = get_option('cnc_b2b_user_specific_fonts');
                $rows = get_option('cnc_b2b_fonts');
                if (gettype($postdata['engrave_fonts']) == 'string') {
                    $postdata['engrave_fonts'] = array($postdata['engrave_fonts']);
                }

                if ($rows) {
                    foreach ($rows as $row) {

                        $engrave_fonts[str_replace(' ', '', $row->name_of_font)] = $row->name_of_font;
                ?>@font-face {
                    font-family: "<?php echo $row->name_of_font ?>";
                    src: url('<?php echo $row->font_file ?>');
                }

                .<?php echo str_replace(' ', '', $row->name_of_font); ?> {
                    font-family: '<?php echo $row->name_of_font ?>';
                }

                <?php
                    }
                }

                if ($user_specific_fonts) {
                    foreach ($user_specific_fonts as $user_font) {

                        $engrave_user_fonts[str_replace(' ', '', $user_font->name_of_font)] = $user_font->name_of_font;
                ?>@font-face {
                    font-family: "<?php echo $user_font->name_of_font ?>";
                    src: url('<?php echo $user_font->font_file ?>');
                }

                .<?php echo str_replace(' ', '', $user_font->name_of_font); ?> {
                    font-family: '<?php echo $user_font->name_of_font ?>';
                }

                <?php
                    }
                }
                ksort($engrave_fonts);


                ?>
            </style>
            <?php


            ?>
            <div class="width_100 headSection">
                <h1>Customise Your Gift</h1>
                <span><button type="button" onclick="closepopup()" class="closeIcon"></button></span>
            </div>
            <div class="width_100 contentWrapper">
                <?php
                if (!empty($postdata['engrave_option_images'])) {
                    foreach ($postdata['engrave_option_images'] as $key => $value) {
                ?>
                        <input type="hidden" data-id="<?php echo $key ?>" class="image_variation_hidden" value="<?php echo $value; ?>" />
                <?php
                    }
                }
                ?>
                <input type="hidden" class="variation_data_hidden" value='<?php echo json_encode($postdata['varialble_option'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>' />
                <input type="hidden" class="engrave_visible_font_customization" value='<?php echo ($postdata['engrave_visible_font_customization']); ?>' />
                <div class="width_50 left">
                    <div class="print_wrap engrave" id="screenshort-wrapper" style="
                            height : <?php echo ($postdata['engrave_image_height'] + 10); ?>px;
                            width : <?php echo ($postdata['engrave_image_width'] + 10); ?>px;
                            ">
                        <img class="engrave_main_image" src="<?php echo str_replace(array("es.", "aus.", "us.", "secureservercdn.net/160.153.137.170/"), '', $postdata['engravepreviewimage']); ?>" style="
                                width : <?php echo $postdata['engrave_image_width']; ?>px;
                                height : <?php echo $postdata['engrave_image_height']; ?>px;
                                " />
                        <?php
                        for ($i = 1; $i <= $postdata['engrave_no_of_customization']; $i++) {
                        ?>
                            <div class="font_frontend <?php if ($postdata['multiple_fonts'] == 1) {
                                                            echo $postdata['engrave_fonts_' . $i];
                                                        } ?>
                                    <?php if ($postdata['engrave_fonts_override_' . $i] == 1) {
                                        echo 'override_this';
                                    } ?>
                                    " style="
                                    color:<?php echo (substr($postdata['font_color_' . $i], 0, 1) == '#') ? $postdata['font_color_' . $i] : '#' . $postdata['font_color_' . $i]; ?>;
                                    font-size:<?php echo  $postdata['font_size_' . $i] ?>px;
                                    top : <?php echo  $postdata['position_top_' . $i] ?>px;
                                    left : <?php echo  $postdata['position_left_' . $i] ?>px;
                                    height : <?php echo  $postdata['engrave_height_' . $i] ?>px;
                                    width : <?php echo  $postdata['engrave_width_' . $i] ?>px;
                                    " data-id="<?php echo $i; ?>"><?php $postdata['sample_text_' . $i]; ?>
                                <div class="cnc_font_actual"></div>
                            </div>
                            <input type="hidden" name="cnc_font_size_<?php echo $i; ?>" value="<?php echo  $postdata['font_size_' . $i] ?>" id="cnc_font_size_<?php echo $i; ?>" />
                            <input type="hidden" name="cnc_current_font_size_<?php echo $i; ?>" value="<?php echo  $postdata['font_size_' . $i] ?>" id="cnc_current_font_size_<?php echo $i; ?>" />
                        <?php
                        }

                        ?>

                    </div>
                    <div class="notice width_100 engravenotice">Note: Display is an approximate preview. final product may vary slightly, although we will notify of any major changes. We don't allow emojis.</div>
                </div>
                <div class="width_50 right">
                    <?php
                    if (count($postdata['engrave_fonts']) == 1) :
                    ?>
                        <input type="hidden" id="engrave_fonts" name="engrave_fonts" value="<?php echo $postdata['engrave_fonts'][0]; ?>">
                    <?php

                    else :
                    ?>

                        <div class="width_100" <?php if (isset($postdata['multiple_fonts']) && $postdata['multiple_fonts'] == 1) {
                                                    echo 'style="display:none"';
                                                } ?>>
                            <b>Font Choice</b>
                        </div>
                        <div class="width_100" <?php if (isset($postdata['multiple_fonts']) && $postdata['multiple_fonts'] == 1) {
                                                    echo 'style="display:none"';
                                                } ?>>
                            <select name="engrave_fonts" id="engrave_fonts">
                                <?php
                                if (gettype($postdata['engrave_fonts']) == 'string') {
                                    $postdata['engrave_fonts'] = array($postdata['engrave_fonts']);
                                }
                                $i = 0;
                                foreach ($engrave_fonts as $key => $font) :
                                    // echo $key."--->";
                                    // print_r($postdata['engrave_fonts']);
                                    // echo "<br>";
                                    if (!in_array($key, $postdata['engrave_fonts'])) :
                                        continue;
                                    endif;
                                    if ($postdata['engrave_fonts'][0] == 'curlz' && $i == 0 && in_array('zapfchancery', $postdata['engrave_fonts'])) :
                                        unset($engrave_fonts['$engrave_fonts']);
                                ?><option class="zapfchancery" value="zapfchancery">Zapf Chancery</option><?php
                                                                                                        endif;
                                                                                                        $i++;
                                                                                                            ?>
                                    <option class="<?php echo $key; ?>" value="<?php echo $key; ?>"><?php echo $font; ?></option>
                                <?php
                                endforeach;

                                foreach ($engrave_user_fonts as $key => $font) :
                                ?>
                                    <option class="<?php echo $key; ?>" value="<?php echo $key; ?>"><?php echo $font; ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    <?php
                    endif;
                    ?>

                    <?php if (isset($postdata['engrave_enable_clip_art'])) : ?>
                        <div class="width_100">
                            <b>Clip Art</b>
                        </div>
                        <div class="width_100">
                            <select id="clipart" name="clipart">
                                <option value="">(not selected)</option>
                                <?php
                                foreach (get_option('cnc_b2b_cliparts') as $cliparts) {
                                ?>
                                    <option data-height="<?php echo $cliparts->height; ?>" data-width="<?php echo $cliparts->width; ?>" value="<?php echo $cliparts->internal_name; ?>" data-image="<?php echo $cliparts->image; ?>"><?php echo $cliparts->name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="notice width_100 engravenotice" style="margin-top: 0;color: #fe0000;margin-bottom: 15px;">Note: While using a clipart you will not be able use the first two lines of text.</div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($postdata['available_color'])) : ?>
                        <div class="width_100">
                            <b>Select Color</b>
                        </div>
                    <?php endif; ?>
                    <div class="width_100 color_selection">

                        <div class="engrave_color_selection width_100">
                            <?php


                            $color = (substr($postdata['font_color_1'], 0, 1) == '#') ? $postdata['font_color_1'] : '#' . $postdata['font_color_1'];

                            if (!empty($postdata['available_color_label'][0])) :

                            ?>
                                <select class="engrave_color_class_select <?php if ($postdata['color_for_variation'] == '1') {
                                                                                echo 'color_for_variation';
                                                                            } ?> <?php if ($postdata['override_font_color']) {
                                                                                        echo 'override_font_color';
                                                                                    } ?>">
                                    <option value="">Select Color</option>
                                    <?php
                                    foreach ($postdata['available_color_label'] as $key => $value) {
                                        $color = (substr($postdata['available_color'][$key], 0, 1) == '#') ? $postdata['available_color'][$key] : '#' . $postdata['available_color'][$key];
                                    ?>
                                        <option data-id="1" data-image="<?php echo $postdata['available_color_image'][$key] ?>" data-label="<?php echo $value ?>" value="<?php echo $value ?>" data-color="<?php echo $color ?>"><?php echo $value ?></option>

                                    <?php
                                    }

                                    ?>
                                </select>

                                <?php
                            else :
                                if (!empty($postdata['available_color'])) :
                                    foreach ($postdata['available_color'] as $key => $value) {
                                ?>

                                        <input type="radio" class="enagrave_color_class" data-id="1" value="<?php echo $value; ?>" id="enagrave_color_1_<?php echo $key; ?>" name="enagrave_color_1" <?php if ($color == $value) {
                                                                                                                                                                                                            echo 'checked="checked"';
                                                                                                                                                                                                        } ?> />
                                        <label for="enagrave_color_1_<?php echo $key; ?>" style="background-color: <?php echo $value; ?>" />
                                        </label>
                            <?php
                                    }
                                endif;
                            endif;
                            ?>
                        </div>

                    </div>
                    <?php
                    for ($i = 1; $i <= $postdata['engrave_no_of_customization']; $i++) {
                    ?>
                        <div class="width_100">
                            <div class="width_50">
                                <div class="width_100 show_hide<?php echo $i; ?>"> &nbsp;
                                    <!--<b>Line <?php echo $i; ?></b>-->
                                </div>
                            </div>
                            <div class="width_50">
                                <div class="width_100 show_hide<?php echo $i; ?>">
                                    <strong>
                                        <div class="total_font" data-id="<?php echo $i; ?>"><?php echo $postdata['max_character_' . $i]; ?></div>
                                    </strong>
                                    <input type="hidden" class="font_value_remaining" data-id="<?php echo $i; ?>" value="<?php echo $postdata['max_character_' . $i]; ?>" />
                                    <input type="hidden" class="cnc_total_character" data-id="<?php echo $i; ?>" value="<?php echo $postdata['max_character_' . $i]; ?>" />
                                </div>
                            </div>
                        </div>



                        <div class="width_100 show_hide<?php echo $i; ?>">
                            <input placeholder="Line <?php echo $i  ?>" class="font_value" data-id="<?php echo $i; ?>" value="<?php $postdata['sample_text_' . $i]; ?>" maxlength="<?php echo $postdata['max_character_' . $i]; ?>" />
                        </div>

                        <?php

                        if ($postdata['engrave_font_size_type'] == 'fixed') {
                        ?>
                            <select name="cnc_font_size" id="cnc_font_size" class="cnc_font_size show_hide<?php echo $i; ?>" data-id="<?php echo $i; ?>">
                                <?php
                                for ($j = 30; $j >= 10; $j--) {
                                ?>
                                    <option value=<?php echo $j; ?> <?php if ($j == $postdata['font_size_' . $i]) {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?php echo $j; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        <?php
                        }

                        ?>
                    <?php
                    }
                    ?>
                    <div class="customisation-double-check">
                        Have you double-checked your personalised details? <br>
                        Personalisation details will be used as submitted
                    </div>

                    <div class="width_100">
                        <button onclick="closepopup(true)" class="confirm_print">Confirm Customisation</button>

                    </div>
                    <input type="hidden" name="cnc_product_type" id="cnc_product_type" value="<?php echo $postdata['engrave_font_size_type']; ?>" />
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
<?php
    endif;
}

// ------------------------------------------------------WooCommerce Coustom Data to Cart,Checkout,order------------------------------------------------//

add_filter('woocommerce_add_cart_item_data', 'cnc_b2b_add_cart_item_data', 25, 2);
function cnc_b2b_add_cart_item_data($cart_item_data, $product_id)
{
    if (isset($_POST['engrave_product_sku'])) {
        if (!empty($_POST['engrave_product_sku'])) {
            $cart_item_data['engrave_product_sku'] = $_POST['engrave_product_sku'];
        }
    }

    if (isset($_POST['engrave_fonts'])) {
        if (!empty($_POST['engrave_fonts'])) {
            $cart_item_data['engrave_fonts'] = $_POST['engrave_fonts'];
        }
    }

    if (isset($_POST['font_color'])) {
        if (!empty($_POST['font_color'])) {
            $cart_item_data['font_color'] = $_POST['font_color'];
        }
    }

    for ($x = 1; $x <= 10; $x++) {
        if (isset($_POST['font_value_' . $x])) {
            if (!empty($_POST['font_value_' . $x])) {
                $cart_item_data['font_value_' . $x] = stripslashes($_POST['font_value_' . $x]);
            }
        }
    }

    if (isset($_POST['clipart'])) {
        if (!empty($_POST['clipart'])) {
            $cart_item_data['clipart'] = $_POST['clipart'];
        }
    }
    if (isset($_POST['print_url'])) {
        if (!empty($_POST['print_url'])) {
            $cart_item_data['print_url'] = $_POST['print_url'];
        }
    }
    if (isset($_POST['image_url'])) {
        if (!empty($_POST['image_url'])) {
            $cart_item_data['image_url'] = $_POST['image_url'];
        }
    }
    return $cart_item_data;
}

// Display custom data on cart and checkout page.
add_filter('woocommerce_get_item_data', 'cnc_b2b_get_item_data', 25, 2);
function cnc_b2b_get_item_data($cart_data, $cart_item)
{
    if (isset($cart_item['engrave_product_sku'])) {
        $cart_data[] = array(
            'name'    => __("Engrave SKU"),
            'display' =>  $cart_item['engrave_product_sku']
        );
    }
    if (isset($cart_item['engrave_fonts'])) {
        $cart_data[] = array(
            'name'    => __("Engrave Fonts"),
            'display' =>  $cart_item['engrave_fonts']
        );
    }
    if (isset($cart_item['font_color'])) {
        $cart_data[] = array(
            'name'    => __("Engrave Font Color"),
            'display' =>  $cart_item['font_color']
        );
    }
    for ($x = 1; $x <= 10; $x++) {
        if (isset($cart_item['font_value_' . $x])) {
            $cart_data[] = array(
                'name'    => __('Engrave Font ' . $x),
                'display' =>  $cart_item['font_value_' . $x]
            );
        }
    }
    if (isset($cart_item['clipart'])) {
        $cart_data[] = array(
            'name'    => __("Engrave Clipart"),
            'display' =>  $cart_item['clipart']
        );
    }
    if (isset($cart_item['print_url'])) {
        $cart_data[] = array(
            'name'    => __("Print Preview"),
            'display' =>  $cart_item['print_url']
        );
    }
    if (isset($cart_item['image_url'])) {
        $cart_data[] = array(
            'name'    => __("Uploaded File"),
            'display' =>  $cart_item['image_url']
        );
    }

    return $cart_data;
}

// Add order item meta.
add_action('woocommerce_add_order_item_meta', 'cnc_b2b_add_order_item_meta', 10, 3);
function cnc_b2b_add_order_item_meta($item_id, $cart_item, $cart_item_key)
{
    if (isset($cart_item['engrave_product_sku'])) {
        wc_add_order_item_meta($item_id, "Engrave SKU", $cart_item['engrave_product_sku']);
    }
    if (isset($cart_item['engrave_fonts'])) {
        wc_add_order_item_meta($item_id, "Engrave Fonts", $cart_item['engrave_fonts']);
    }
    if (isset($cart_item['font_color'])) {
        wc_add_order_item_meta($item_id, "Engrave Font Color", $cart_item['font_color']);
    }
    for ($x = 1; $x <= 10; $x++) {
        if (isset($cart_item['font_value_' . $x])) {
            wc_add_order_item_meta($item_id, 'Engrave Font ' . $x, $cart_item['font_value_' . $x]);
        }
    }
    if (isset($cart_item['clipart'])) {
        wc_add_order_item_meta($item_id, "Engrave Clipart", $cart_item['clipart']);
    }
    if (isset($cart_item['print_url'])) {
        wc_add_order_item_meta($item_id, "Print Preview", $cart_item['print_url']);
    }
    if (isset($cart_item['image_url'])) {
        wc_add_order_item_meta($item_id, "Uploaded File", $cart_item['image_url']);
    }
}


add_action('woocommerce_thankyou', 'cnc_b2b_after_order_create', 10, 1);
function cnc_b2b_after_order_create($order_id)
{
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    $is_cnc_b2b_product = false;

    foreach ($order->get_items() as $item_id => $item) {
        if ($item->get_meta('Engrave SKU', true)) {
            $is_cnc_b2b_product = true;
        }
    }

    if ($is_cnc_b2b_product) {
        update_post_meta($order_id, "is_cnc_b2b_order", true);
    }
}   

// ------------------------------------------------------WooCommerce Coustom Data to Cart,Checkout,order------------------------------------------------//
