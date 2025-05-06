<?php if(isset($data) && is_array($data) && !empty($data)): ?>
    <?php foreach($data as $value):?>
    <div class="row container-element">
		<input class="option-id" type="hidden" name="option_id[<?php echo $value['id']; ?>]" value="<?php echo $value['id']; ?>">
        <div class="col-md-2">
			<input class="form-control text-right order option-order" name="order[]" type="text" value="<?php echo $value['order']; ?>">
        </div>
        <div class="col-md-4">
            <img src="<?php echo get_image(get_module_path('shops') . $value['image'], get_module_path('shops') . 'no-image.png'); ?>" alt="" class="img-thumbnail img-responsive">
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <input class="form-control option-alt" name="alt[]" type="text" value="<?php echo $value['alt']; ?>" placeholder="Mô tả" maxlength="255">
                <span class="input-group-btn">
                    <button class="btn btn-danger remove-element option-delete" type="button"> <i class="fa fa-trash"></i></button>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>