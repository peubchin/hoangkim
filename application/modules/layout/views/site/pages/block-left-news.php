<div class="col-lg-3 col-md-3 col-sm-4 col-lg-pull-9 col-md-pull-9 col-sm-pull-8">
    <div class="box-category">
        <div class="header">
            <h4>DANH MỤC SẢN PHẨM</h4>
        </div>
        <ul>
            <?php echo multilevelMenu(0, $shops_cat_list, $shops_cat_data); ?>
        </ul>
    </div>
    <?php echo isset($posts_home) ? $posts_home : ''; ?>
    <div class="clearfix"></div>
</div>