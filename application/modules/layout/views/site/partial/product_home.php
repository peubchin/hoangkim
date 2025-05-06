<?php if (isset($data) && is_array($data) && !empty($data)): ?>                    
    <div class="header-product-home">
        <h4>SẢN PHẨM BÁN CHẠY</h4>
        <div class="line-header"><div class="line-sub-header"></div></div>
    </div>
    <div class="row">
        <?php
        foreach ($data as $value):
            $data_id = $value['id'];
            $data_title = word_limiter($value['title'], 10);
            $data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
            $data_image = array(
                'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image-thumb.png'),
                'alt' => ''
            );
            $data_price = $value['product_price'];
            $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
            ?>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                <div class="product-home-item">
                    <a href="<?php echo $data_link; ?>"><img class="img-responsive center-block" src="<?php echo get_asset('img_path'); ?>produdu_03.png" /></a>
                    <div class="text-home-item">
                        <h4><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h4>
                        <p class="price"><?php echo formatRice($data_price); ?> VNĐ</p>
                        <p class="price-sale"><?php echo formatRice($data_sales_price); ?> VNĐ</p>
                        <span class="sale-percent">10%</span>
                    </div>
                    <div class="box-buy">
                        <button data-id="add_to_cart_id_<?php echo $data_id; ?>" class="add_to_cart">MUA NGAY</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="clearfix"></div>
<?php endif; ?>