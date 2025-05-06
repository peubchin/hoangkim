<?php
if (isset($data) && is_array($data) && !empty($data)):
    foreach ($data as $value):
        $data_id = $value['id'];
        $data_title = word_limiter($value['title' . $_lang], $word_limiter_title_posts);
        $data_hometext = word_limiter($value['hometext' . $_lang], $word_limiter_hometext_posts);
        $data_link = site_url($value['categories']['alias' . $_lang] . '/' . $value['alias' . $_lang] . '-' . $data_id);
        $data_image = array(
            'src' => base_url(get_module_path('posts') . $value['homeimgfile']),
            'alt' => $value['homeimgalt']
        );
        ?>
        <div class="sptrai">
            <div class="col-xs-5">
                <a href="<?php echo $data_link; ?>"><img class="img-responsive img-portfolio img-hover" src="<?php echo $data_image['src']; ?>" alt=""></a>
            </div>
            <div class="col-xs-7">
                <a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a>
                <?php echo $data_hometext; ?>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>