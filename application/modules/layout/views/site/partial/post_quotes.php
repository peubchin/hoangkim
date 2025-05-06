<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <section>
        <div class="container">
            <div class="header-quote">
                <span>
                    BÁO GIÁ SẮT THÉP XÂY DỰNG cập nhật trong vòng 24h
                </span>
            </div>
            <div class="row">
                <?php
                foreach ($data as $value):
                    $data_id = $value['id'];
                    $data_title = convert_to_uppercase($value['title']);
                    $data_hometext = $value['hometext'];
                    $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
                    $data_image = array(
                        'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image-thumb.png'),
                        'alt' => $value['homeimgalt']
                    );
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="qoute-item">
                            <div class="qoute-item-over">
                                <a href="<?php echo $data_link; ?>"><img class="img-responsive" src="<?php echo $data_image['src']; ?>" /></a>
                            </div>
                            <h4><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h4>
                            <p><?php echo $data_hometext; ?></p>
                            <a class="view-more" href="<?php echo $data_link; ?>">READ MORE</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>