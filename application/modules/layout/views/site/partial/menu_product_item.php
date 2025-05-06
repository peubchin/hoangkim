<?php if (isset($value) && is_array($value) && !empty($value)): ?>
    <?php
    $data_id = $value['id'];
    $data_title = word_limiter($value['title'], 4);
    $data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
    $data_image = array(
        'src' => get_image(get_module_path('shops_thumbnais') . get_shops_thumbnais_default_size() . '/' . $value['homeimgfile'], get_module_path('shops') . 'no-image-166x192.png'),
        'alt' => ''
    );
    $data_price = formatRice($value['product_price']);
    ?>
    <div class="sanpham">
        <span class="img">
            <a href="<?php echo $data_link; ?>">
                <img src="<?php echo $data_image['src']; ?>" alt="" title="" class="img-responsive hinhsp">
            </a>
        </span>
        <a href="<?php echo $data_link; ?>">
            <span class="tensp"><?php echo $data_title; ?></span>
        </a>
        <span class="gia"><?php echo $data_price; ?> ₫</span>
    </div>
<?php endif; ?>