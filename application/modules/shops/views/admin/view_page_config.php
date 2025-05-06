<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-cog">&nbsp;</em>Cấu hình module giỏ hàng</h3>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo get_admin_url('shops/config'); ?>" method="post" autocomplete="off">
                    <?php $has_error = (form_error('img_max_width') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="img_max_width" class="control-label">Chiều rộng tối đa ảnh sản phẩm</label>
                        <?php $configs_img_max_width = (set_value('img_max_width') != '') ? set_value('img_max_width') : $configs['img_max_width']; ?>
                        <input class="form-control" name="img_max_width" id="img_max_width" type="text" value="<?php echo $configs_img_max_width; ?>">
                        <?php echo form_error('img_max_width'); ?>
                    </div>
                    <?php $has_error = (form_error('img_max_height') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="img_max_height" class="control-label">Chiều cao tối đa ảnh sản phẩm</label>
                        <?php $configs_img_max_height = (set_value('img_max_height') != '') ? set_value('img_max_height') : $configs['img_max_height']; ?>
                        <input class="form-control" name="img_max_height" id="img_max_height" type="text" value="<?php echo $configs_img_max_height; ?>">
                        <?php echo form_error('img_max_height'); ?>
                    </div>

                    <?php $has_error = (form_error('img_width') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="img_width" class="control-label">Chiều rộng ảnh sản phẩm</label>
                        <?php $configs_img_width = (set_value('img_width') != '') ? set_value('img_width') : $configs['img_width']; ?>
                        <input class="form-control" name="img_width" id="img_width" type="text" value="<?php echo $configs_img_width; ?>">
                        <?php echo form_error('img_width'); ?>
                    </div>
                    <?php $has_error = (form_error('img_height') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="img_height" class="control-label">Chiều cao ảnh sản phẩm</label>
                        <?php $configs_img_height = (set_value('img_height') != '') ? set_value('img_height') : $configs['img_height']; ?>
                        <input class="form-control" name="img_height" id="img_height" type="text" value="<?php echo $configs_img_height; ?>">
                        <?php echo form_error('img_height'); ?>
                    </div>

                    <?php $has_error = (form_error('cart_img_width') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="cart_img_width" class="control-label">Chiều rộng ảnh sản phẩm ở giỏ hàng</label>
                        <?php $configs_cart_img_width = (set_value('cart_img_width') != '') ? set_value('cart_img_width') : $configs['cart_img_width']; ?>
                        <input class="form-control" name="cart_img_width" id="cart_img_width" type="text" value="<?php echo $configs_cart_img_width; ?>">
                        <?php echo form_error('cart_img_width'); ?>
                    </div>                    
                    <?php $has_error = (form_error('cart_img_height') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="cart_img_height" class="control-label">Chiều cao ảnh sản phẩm ở giỏ hàng</label>
                        <?php $configs_cart_img_height = (set_value('cart_img_height') != '') ? set_value('cart_img_height') : $configs['cart_img_height']; ?>
                        <input class="form-control" name="cart_img_height" id="cart_img_height" type="text" value="<?php echo $configs_cart_img_height; ?>">
                        <?php echo form_error('cart_img_height'); ?>
                    </div>

                    <?php $has_error = (form_error('cat_img_width') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="cat_img_width" class="control-label">Chiều rộng ảnh sản phẩm ở chủ đề</label>
                        <?php $configs_cat_img_width = (set_value('cat_img_width') != '') ? set_value('cat_img_width') : $configs['cat_img_width']; ?>
                        <input class="form-control" name="cat_img_width" id="cat_img_width" type="text" value="<?php echo $configs_cat_img_width; ?>">
                        <?php echo form_error('cat_img_width'); ?>
                    </div>
                    <?php $has_error = (form_error('cat_img_height') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="cat_img_height" class="control-label">Chiều cao ảnh sản phẩm ở chủ đề</label>
                        <?php $configs_cat_img_height = (set_value('cat_img_height') != '') ? set_value('cat_img_height') : $configs['cat_img_height']; ?>
                        <input class="form-control" name="cat_img_height" id="cat_img_height" type="text" value="<?php echo $configs_cat_img_height; ?>">
                        <?php echo form_error('cat_img_height'); ?>
                    </div>

                    <?php $has_error = (form_error('details_img_width') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="details_img_width" class="control-label">Chiều rộng chi tiết ảnh sản phẩm</label>
                        <?php $configs_details_img_width = (set_value('details_img_width') != '') ? set_value('details_img_width') : $configs['details_img_width']; ?>
                        <input class="form-control" name="details_img_width" id="details_img_width" type="text" value="<?php echo $configs_details_img_width; ?>">
                        <?php echo form_error('details_img_width'); ?>
                    </div>
                    <?php $has_error = (form_error('details_img_height') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="details_img_height" class="control-label">Chiều cao chi tiết ảnh sản phẩm</label>
                        <?php $configs_details_img_height = (set_value('details_img_height') != '') ? set_value('details_img_height') : $configs['details_img_height']; ?>
                        <input class="form-control" name="details_img_height" id="details_img_height" type="text" value="<?php echo $configs_details_img_height; ?>">
                        <?php echo form_error('details_img_height'); ?>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>