<?php
if (isset($data) && is_array($data) && !empty($data)):
    $i = 0;
    foreach ($data as $value):
        $i++;
        $data_id = $value['id'];
        $data_title = word_limiter($value['title'], 15);
        $data_hometext = word_limiter($value['hometext'], 40);
        $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
        $data_cat_link = site_url($this->config->item('url_posts_cat') . '/' . $value['categories']['alias']);
    		$data_cat_name = $value['categories']['name'];
        $data_image = array(
            'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
            'alt' => $value['homeimgalt']
        );
        $data_date = date('d', $value['addtime']) . ' ' . date('M', $value['addtime']) . ' ' . date('Y', $value['addtime']);
        ?>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <div class="post-page-item">
            <h3><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
            <div class="meta-list">
             <!-- <span class="date-post"><i class="far fa-clock"></i> <?php echo $data_date; ?></span>-->
  						<span class="category-blog"><i class="fa fa-folder-open"></i>
              <a href="<?php echo $data_cat_link; ?>" rel="category tag"><?php echo $data_cat_name; ?></a></span>
  					</div>
            <div class="block-images">
              <a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=570&h=285&zc=1&q=100'); ?>" alt="" class="img-fluid"></a>
            </div>
            <div class="block-content">
              <p><?php echo $data_hometext; ?></p>
            </div>
          </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
