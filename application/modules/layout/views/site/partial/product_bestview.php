<?php if (isset($data) && is_array($data) && !empty($data)): ?>
  <div class="box-product-bestview">
    <div class="block--title">
      <h3>SẢN PHẨM XEM NHIỀU</h3>
    </div>
    <div class="product-bestview-rows slick-navigation-arrow">
      <?php
      foreach ($data as $value):
        $data_id = $value['id'];
        $data_title = word_limiter($value['title'], 10);
        $data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
        $data_image = array(
          'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image-thumb.png'),
          'alt' => ''
        );
        // $data_discounts_percent = $value['product_discounts_percent'];
        $data_price = $value['product_price'];
        $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
        ?>
        <div class="product-bestview-item">
          <div class="block-images">
            <a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=70&h=80&zc=1&q=100'); ?>" alt="" class="img-fluid"></a>
          </div>
          <div class="block-content">
            <h3 class="title-logo"><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
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
          </div>
          <div class="clearfix"></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
