<?php if (isset($data) && is_array($data) && !empty($data)): ?>
  <?php foreach ($data as $data_cat): ?>
    <?php if (isset($data_cat['childs']) && !empty($data_cat['childs'])): ?>
      <section>
        <div class="box-product-cat">
          <div class="block-tab">
            <div class="tab-header">
              <div class="title">
                <h3><span><?php echo $data_cat['name']; ?></span> <a href="<?php echo site_url($this->config->item('url_shops_cat') . '/' . $data_cat['alias']); ?>">Xem tất cả</a></h3>
              </div>
            </div>
            <div class="field-product-header owl-length-cat">
              <div id="owl-tabproduct<?php echo $data_cat['id']; ?>" class="owl-carousel">
                <?php
                foreach ($data_cat['childs'] as $child):
                  $data_image_cat = array(
                    'src' => get_image(get_module_path('shops_cat') . $child['image'], get_module_path('shops_cat') . 'no-image.png'),
                    'alt' => '',
                  );
                  ?>
                  <?php if (isset($child['items']) && !empty($child['items'])): ?>
                    <div class="item">
                      <a href="#menu<?php echo $child['id']; ?>">
                        <div class="tab-product-item">
                          <img src="<?php echo base_url('timthumb.php?src=' . $data_image_cat['src'] . '&w=130&h=70&zc=2&q=100'); ?>" alt="" class="img-fluid d-block mx-auto">
                          <span class="name"><?php echo $child['name']; ?></span>
                        </div>
                      </a>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="field-product-body">
            <div class="tab-content">
              <?php $i = 0; foreach ($data_cat['childs'] as $child): ?>
                <?php if (isset($child['items']) && !empty($child['items'])): $i++; ?>
                  <div id="menu<?php echo $child['id']; ?>" class="owl-length tab-pane<?php echo $i == 1 ? ' active' : ''; ?>">
                    <div id="owl-product-field-catelogy-<?php echo $child['id']; ?>" class="owl-carousel owl-catelogy-slide">
                      <?php
                      foreach ($child['items'] as $value)://items childs
                        $data_id = $value['id'];
                        $data_title = convert_to_uppercase($value['title']);
                        $data_hometext = word_limiter($value['hometext'], 16);
                        $data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
                        $data_image = array(
                          'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image.png'),
                          'alt' => ''
                        );
                        $data_discount_percent = $value['product_discount_percent'];
                        $data_price = $value['product_price'];
                        $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
                        ?>
                        <div class="item">
                          <div class="product-item">
                            <?php if ($data_discount_percent > 0): ?>
                              <div class="overlay-saleoff"><?php echo ceil($data_discount_percent); ?>%</div>
                            <?php endif;?>
                            <div class="block-images">
                              <a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=147&h=182&zc=2&q=100'); ?>" alt="" class="img-fluid d-block mx-auto"></a>
                              <div class="overlay-quickview">
                                <a href="<?php echo $data_link; ?>"><i class="fas fa-eye"></i> XEM CHI TIẾT</a>
                              </div>
                            </div>
                            <div class="block-content">
                              <h3><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
                              <div class="price">
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
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
