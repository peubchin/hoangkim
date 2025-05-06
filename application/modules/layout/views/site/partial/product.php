<?php if (isset($data) && is_array($data) && !empty($data)): ?>
  <?php
  $i = 0;
  foreach ($data as $value):
    $i++;
    $data_id = $value['id'];
    $data_title = convert_to_uppercase($value['title']);
    $data_hometext = word_limiter($value['hometext'], 16);
    $data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
    $data_image = array(
      'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image.png'),
      'alt' => ''
    );
    $data_price = $value['product_price'];
    $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
    ?>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-6">
      <div class="product-item">
        <div class="block-images">
          <a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=147&h=182&zc=2&q=100'); ?>" alt="" class="img-fluid d-block mx-auto"></a>
          <div class="overlay-quickview">
            <a href="<?php echo $data_link; ?>"><i class="fas fa-eye"></i> XEM CHI TIẾT</a>
          </div>
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
			  <?php else: ?>
              <span class="price-original">Liên hệ</span>
            <?php endif; ?>
            <div class="clearfix"></div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
