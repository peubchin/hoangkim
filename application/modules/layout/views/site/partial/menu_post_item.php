<?php if (isset($value) && is_array($value) && !empty($value)): ?>
    <?php
    $data_id = $value['id'];
    $data_title = word_limiter($value['title'], 15);
    $data_hometext = word_limiter($value['hometext'], 40);
    $data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
    $data_date = 'Ngày ' . date('d/m/Y', $value['addtime']) . ' Lúc ' . date('h', $value['addtime']) . 'h' . date('m', $value['addtime']);
    ?>
    <a href="<?php echo $data_link; ?>">
        <h3 class="tieudebv">
            <?php echo $data_title; ?><br>
            <small><?php echo $data_date; ?></small>
        </h3>
    </a>
    <div class="tomtatbv">
        <?php echo $data_hometext; ?>
        <a href="<?php echo $data_link; ?>" class="xemthem">Xem thêm</a>
    </div>
<?php endif; ?>