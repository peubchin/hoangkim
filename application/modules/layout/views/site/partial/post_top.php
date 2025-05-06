<?php
if (isset($data) && is_array($data) && !empty($data)):
    foreach ($data as $value):
        $data_id = $value['id'];
        $data_title = word_limiter($value['title' . $_lang], $word_limiter_title_posts);
        $data_hometext = word_limiter($value['hometext' . $_lang], 60);
        $data_link = site_url($value['categories']['alias' . $_lang] . '/' . $value['alias' . $_lang] . '-' . $data_id);
        $data_image = array(
            'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
            'alt' => $value['homeimgalt']
        );
        $data_date = date('d/m/Y', $value['addtime']);
        $data_author = $value['author'];
        ?>
        <a href="<?php echo $data_link; ?>">
            <img src="<?php echo $data_image['src']; ?>" class="img-responsive" alt="">
            <h4><?php echo $data_title; ?><small> (<?php echo $this->lang->line('date'); ?> <?php echo $data_date; ?> - <?php echo $this->lang->line('by'); ?>: <?php echo $data_author; ?>)</small></h4>
        </a>
        <div class="tomtat">
            <p><?php echo $data_hometext; ?></p>
            <a href="<?php echo $data_link; ?>" style="float:right"><?php echo $this->lang->line('view_more'); ?></a><div class="clearfix"></div><br>
        </div>
    <?php endforeach; ?>
<?php endif; ?>