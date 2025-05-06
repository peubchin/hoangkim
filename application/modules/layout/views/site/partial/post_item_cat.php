<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <div class="row">
        <?php
        $i = 0;
        $count = count($data);
        foreach ($data as $value):
            $i++;
            $data_id = $value['id'];
            $data_title = word_limiter($value['title'], 15);
            $data_hometext = word_limiter($value['hometext'], 20);
            $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
            $data_image = array(
                'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image-thumb.png'),
                'alt' => $value['homeimgalt']
            );
            ?>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="row">
                    <div class="news-bottom-item">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <a href="<?php echo $data_link; ?>"><img class="img-responsive" src="<?php echo get_asset('img_path'); ?>news-boot_09.png" /></a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <h4><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h4>
                            <p><?php echo $data_hometext; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>