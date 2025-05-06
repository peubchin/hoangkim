<?php if (isset($partner_none) && is_array($partner_none) && !empty($partner_none)): ?>
  <section>
    <div class="box-partner">
      <div class="container">
        <div class="block--title"> <h3>ĐỐI TÁC</h3> </div>
        <div class="partner-rows">
          <div id="owl-partner" class="owl-carousel owl-theme owl-navigations-partner">
            <?php foreach ($partner_none as $value):
              $data_image = array(
                'src' => get_image(get_module_path('images') . $value['image'], get_module_path('images') . 'no-image.png'),
                'alt' => '',
              );
            ?>
              <a href="<?php echo ($value['link']); ?>" class="d-block"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=210&h=105&zc=2&q=100'); ?>" alt="" class="img-fluid"></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
