<?php
if (isset($data) && is_array($data) && !empty($data)):
    $i = 0;
    foreach ($data as $value):
        $i++;
        $data_id = $value['id'];
        $data_title = word_limiter($value['title' . $_lang], $word_limiter_title_posts);
        $data_hometext = word_limiter($value['hometext' . $_lang], $word_limiter_hometext_posts);
        $data_link = site_url($value['categories']['alias' . $_lang] . '/' . $value['alias' . $_lang] . '-' . $data_id);
        $data_image = array(
            'src' => base_url(get_module_path('posts') . $value['homeimgfile']),
            'alt' => $value['homeimgalt']
        );
        $data_date = date('d.M.Y', $value['addtime']) . '<br>' . date('l', $value['addtime']);
        $data_order = ($i < 10) ? "0" . $i : $i;
        $data_order_next = (($i + 1) < 10) ? "0" . ($i + 1) : ($i + 1);
        ?>
        <li>
            <a href="<?php echo $data_link; ?>">
                <img src="<?php echo $data_image['src']; ?>" alt="" class="hinhtinlq">
                <h4 class="tieudetinlq"><?php echo $data_title; ?></h4>
                <div class="tomtattinlq"><p><?php echo $data_hometext; ?></p></div>
            </a>
            <div class="clearfix"></div>
        </li>
    <?php endforeach; ?>
<?php endif; ?>