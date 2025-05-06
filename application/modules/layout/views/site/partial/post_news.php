<?php
if (isset($data) && is_array($data) && !empty($data)):
  foreach ($data as $value):
    $data_id = $value['id'];
    $data_title = $value['title'];
    $data_hometext = $value['hometext'];
    $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
    $data_image = array(
      'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image.png'),
      'alt' => ''
    );
    ?>
    <div class="col-lg-4 col-md-4 col-sm-4">
      <div class="post-item">
        <a href="<?php echo $data_link ?>"><img class="img-responsive"
            src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=369&h=200&zc=1&q=100'); ?>" alt=""></a>
        <h3 class="title"><a href="<?php echo $data_link ?>"><span><?php echo $data_title; ?></span></a></h3>
        <div class="block-content">
          <?php echo $data_hometext ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php endif; ?>
