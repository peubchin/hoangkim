<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <section>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="box-product-home">
                <div class="box-navigation-tabs">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#product-home-wholesale">SẢN PHẨM SỈ</a>
                        </li>
                    </ul>
                </div>
                <div class="box-content-product-rows">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="product-home-wholesale">
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
                                    $data_discount_percent = $value['product_discount_percent'];
                                    $data_price = $value['product_price'];
                                    $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
                                ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-6">
                                    <div class="product-item">
                                        <div class="overlay"><span>HOT</span></div>
                                        <!-- <div class="badge-item on-sale">SỈ</div> -->
                                        <?php if ($data_discount_percent > 0): ?>
                                        <div class="overlay-saleoff">
                                            <?php echo ceil($data_discount_percent); ?>%</div>
                                        <?php endif;?>
                                        <div class="block-images">
                                            <a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=238&h=294&zc=2&q=100'); ?>" alt="" class="img-fluid d-block mx-auto"></a>
                                            <div class="overlay-quickview">
                                                <a href="<?php echo $data_link; ?>"><i class="fas fa-eye"></i> XEM CHI TIẾT</a>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <h3 class="title-logo2"><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
                                            <div class="rating-public-view">
                                                <ul>
                                                    <li><a class="active" title="1"></a></li>
                                                    <li><a class="active" title="2"></a></li>
                                                    <li><a class="active" title="3"></a></li>
                                                    <li><a class="active" title="4"></a></li>
                                                    <li><a class="active" title="5"></a></li>
                                                </ul>
                                            </div>
                                            <div class="price2">
                                                <?php if ($data_sales_price > 0): ?>
                                                    <?php if ($data_sales_price == $data_price): ?>
                                                        <?php echo formatRice($data_price); ?><sup>đ</sup>
                                                    <?php else: ?>
                                                        <?php echo  formatRice($data_sales_price);?><sup>đ</sup>
                                                        <del><?php echo  formatRice($data_price);?><sup>đ</sup></del>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>