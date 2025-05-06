<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 order-last order-xl-3 order-lg-3 order-md-12">
  <aside>
    <div class="box-sidabar">
      <div class="catelogy-menu-home">
        <div class="block-title">
          <h3>DANH MỤC SẢN PHẨM</h3>
        </div>
        <div id="cssmenu">
          <ul>
            <?php echo multilevelMenu(0, $shops_cat_list, $shops_cat_data, isset($shops_cat_active_page) ? $shops_cat_active_page : ''); ?>
          </ul>
        </div>
      </div>
      <?php echo isset($products_bestview) ? $products_bestview : ''; ?>
      <!-- <div class="box-filter-price">
        <div class="block--title">
          <h3>TÌM THEO GIÁ</h3>
        </div>
        <div class="card">
          <div class="card-body">
            <form>
              <?php //$search_price = $this->config->item('search_price'); ?>
              <?php //if(is_array($search_price) && !empty($search_price)): ?>
              <?php //foreach ($search_price as $key => $value): ?>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input filter-price" id="price-<?php //echo $key; ?>" value="<?php //echo site_url('search') . '?price=' . $key; ?>">
                <label class="custom-control-label" for="price-<?php //echo $key; ?>"><?php //echo $value; ?></label>
              </div>
              <?php //endforeach; ?>
              <?php //endif; ?>
            </form>
          </div>
        </div>
      </div> -->
      <div class="clearfix"></div>
      <?php echo isset($posts_featured) ? $posts_featured : ''; ?>
      <?php if (isset($advertise_sidebar_none) && is_array($advertise_sidebar_none) && !empty($advertise_sidebar_none)): ?>
        <div class="box-advertise-sidebar">
          <?php foreach ($advertise_sidebar_none as $value): ?>
              <a href="<?php echo ($value['link']); ?>" class="d-block mb-4"><img src="<?php echo base_url(get_module_path('images') . ((trim($value['image']) != '') ? $value['image'] : $value['image'])); ?>" alt="" class="img-fluid"></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="clearfix"></div>
    </div>
  </aside>
</div>
