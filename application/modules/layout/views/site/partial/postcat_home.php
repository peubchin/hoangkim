<?php if (isset($data) && is_array($data) && !empty($data)): ?>    
    <?php
    foreach ($data as $data_cat):
        if (isset($data_cat['items']) && !empty($data_cat['items'])):
            ?>
            <section>
                <div class="container">
                    <div class="healing-news">
                        <h4><?php echo convert_to_uppercase($data_cat['name']); ?> <span><a href="<?php echo site_url($this->config->item('url_posts_cat') . '/' . $data_cat['alias']); ?>">Xem tất cả...</a></span></h4>
                    </div>
                    <div class="row">
                        <div class="box-news-bottom">
                            <?php
                            foreach ($data_cat['items'] as $value):
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
                                                <a href="<?php echo $data_link; ?>"><img class="img-responsive" src="<?php echo $data_image['src']; ?>" /></a>
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
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-bottom-news">
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>