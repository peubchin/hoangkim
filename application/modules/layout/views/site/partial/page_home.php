<?php
if (isset($data) && is_array($data) && !empty($data)):
    foreach ($data as $value):
        $data_id = $value['id'];
        $data_title = word_limiter($value['title' . $_lang], 4);
        $data_hometext = word_limiter($value['hometext' . $_lang], 40);
        $data_link = site_url($value['alias'] . '-' . $data_id);
        $data_image = array(
            'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
            'alt' => $value['homeimgalt']
        );
        ?>
        <div class="logo2">
            <a href="<?php echo $data_link; ?>" class="hvr-wobble-skew">
                <img src="<?php echo $data_image['src']; ?>" class="img-responsive" >
            </a>
        </div>
        <p><?php echo $data_hometext; ?></p>
    <?php endforeach; ?>
<?php endif; ?>