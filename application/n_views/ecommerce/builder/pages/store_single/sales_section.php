<?php //start sales
if ($n_eco_builder_config['front_sales_products_show'] == 'true') {
    if (!empty($sales_list)) {
        ?>

        <div class="title-link-wrapper pb-1 mb-4">
            <h2 class="title ls-normal mb-0"><?php echo $n_eco_builder_config['front_sales_products_text']; ?></h2>
            <?php

            $n_special_generate = _link('ecommerce/bestsellers/' . $js_store_unique_id);
            $n_special_generate = mec_add_get_param($n_special_generate, array("subscriber_id" => $subscriberId, "pickup" => $pickup));
            ?>
            <a href="<?php echo $n_special_generate; ?>"
               class="font-size-normal font-weight-bold ls-25 mb-0"><?php echo $l->line('More Products'); ?>
                <i class="w-icon-long-arrow-right"></i>
            </a>
        </div>
    <div class="<?php columns_width($n_eco_builder_config['front_sales_products_rows'], $n_eco_builder_config['mobile_columns_div']); ?>">

        <?php
        $hide_reviews_listing = 'true';
        $always_show_reviews = 'false';
        if ($n_eco_builder_config['front_sales_reviews_show'] == 'show' or $n_eco_builder_config['front_sales_reviews_show'] == 'always_show') {
            $hide_reviews_listing = 'false';
        }
        if ($n_eco_builder_config['front_sales_reviews_show'] == 'always_show') {
            $always_show_reviews = 'true';
        }

        foreach ($sales_list as $key => $value) {
            $imgSrc = ($value['thumbnail'] != '') ? base_url('upload/ecommerce/' . $value['thumbnail']) : base_url('assets/img/products/product-1.jpg');

            if (isset($value["woocommerce_product_id"]) && !is_null($value["woocommerce_product_id"]) && $value['thumbnail'] != '') {
                $imgSrc = $value['thumbnail'];
            }

            $display_price = mec_display_price($value['original_price'], $value['sell_price'], ' ' . $currency_icon . ' ', '1', $currency_position, $decimal_point, $thousand_comma);

            include(APPPATH . 'n_views/ecommerce/builder/product/product_v1.php');
        };
        echo '</div>';
    };
};
//end sales
?>