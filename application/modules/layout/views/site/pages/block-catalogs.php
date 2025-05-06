<div class="col-sm-3 right">
    <h3 class="header"><?php echo $this->lang->line('product_categories'); ?></h3>
    <ul class="danhmucsp">
        <?php echo multilevelMenu(0, $shops_cat_list, $shops_cat_data); ?>
    </ul>
</div>