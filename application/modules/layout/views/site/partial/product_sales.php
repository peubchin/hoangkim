<?php if (isset($data) && is_array($data) && !empty($data)): ?>                    
    <div class="clearfix"></div>
    <div class="box-product-sale">
        <div class="header">
            <h4>SẢN PHẨM KHUYẾN MÃI</h4>
        </div>
        <div class="vticker">
            <ul>
                <?php
                foreach ($data as $value):
                    $data_id = $value['id'];
                    $data_title = word_limiter($value['title'], 10);
                    $data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
                    $data_image = array(
                        'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image-thumb.png'),
                        'alt' => ''
                    );
                    $data_discounts = $value['product_discounts'];
                    $data_price = $value['product_price'];
                    $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
                    ?>
                    <li>
                        <div class="row">
                            <div class="product-sale-item">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <a href="<?php echo $data_link; ?>"><img class="img-responsive center-block" src="<?php echo $data_image['src']; ?>" /></a>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <h4><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h4>
                                    <p class="price"><?php echo formatRice($data_sales_price); ?> VNĐ</p>
                                    <p class="price-sale"><?php echo formatRice($data_price); ?> VNĐ</p>
                                    <p class="price-sale-1">Giảm <?php echo formatRice($data_discounts); ?> vnđ</p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>