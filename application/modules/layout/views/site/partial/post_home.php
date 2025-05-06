<?php if (isset($data) && is_array($data) && !empty($data)): ?>
  <div class="posts_slider_wrap">
    <ul id="sudoslider" class="posts_slider" style="overflow: hidden; position: relative; height: 150px;">
      <div class="slidesContainer" style="display: block; position: relative; margin: 0px; transform: translate(0px, -600px); height: 9e+06px; width: 100%; transition-timing-function: ease-in-out; transition-delay: initial; transition-property: transform;">
        <?php
        $i = 0;
        foreach ($data as $value):
          $i++;
          $data_id = $value['id'];
          $data_title = $value['title'];
          $data_hometext = word_limiter($value['hometext'], 45);
          $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
          $data_image = array(
            'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
            'alt' => $value['homeimgalt']
          );
          $data_date = date('d.M.Y', $value['addtime']) . '<br>' . date('l', $value['addtime']);
          $data_order = ($i < 10) ? "0" . $i : $i;
          $data_order_next = (($i + 1) < 10) ? "0" . ($i + 1) : ($i + 1);
          ?>
          <li class="slide" data-slide="<?php echo $i; ?>" style="position: relative; float: left; list-style: none; display: block; margin: 0px; width: 848px;">
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="pslider_date">
                  <div class="first">
                    <span><?php echo $data_order; ?></span><?php echo $data_date; ?>
                  </div>
                  <div><span><?php echo $data_order_next; ?></span></div>
                </div>
              </div>
              <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                <div class="pslider_thumb">
                  <a href="<?php echo $data_link; ?>">
                    <img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=205&h=273&zc=1&q=100'); ?>" alt="...">
                  </a>
                </div>
              </div>
              <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7">
                <div class="pslider_entry">
                  <h3><?php echo $data_title; ?></h3>
                  <?php echo $data_hometext; ?>
                  <a href="<?php echo $data_link; ?>">Xem</a>
                </div>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </div>
    </ul>
  </div>
<?php endif; ?>
