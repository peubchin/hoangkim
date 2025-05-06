<?php
if (isset($data) && is_array($data) && !empty($data)):
  ?>
  <section>
    <div class="box-post-new">
      <div class="block--title">
        <h3>TIN TỨC MỚI</h3>
      </div>
      <div class="row">
        <?php foreach ($data as $value):
          $data_id = $value['id'];
          $data_title = $value['title'];
          $data_hometext = $value['hometext'];
          $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
          $data_image = array(
            'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
            'alt' => $value['homeimgalt']
          );
          ?>
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
            <div class="post-related-item">
              <div class="block-images">
                <a href="<?php echo $data_link; ?>"><img class="img-fluid"
                    src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=255&h=188&zc=1&q=100'); ?>" /></a>
              </div>
              <div class="block-content">
                <h3><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
                <p><?php echo $data_hometext; ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
  
    </div>
  </section>
<?php endif; ?>