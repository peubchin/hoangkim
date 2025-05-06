<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <?php
    foreach ($data as $value):
        $image = get_image(get_module_path('shops') . $value['image'], get_module_path('shops') . 'no-image.png');
		?>
		<div class="item"><img class="img-responsive" src="<?php echo $image; ?>" /></div>
	<?php endforeach; ?>
<?php endif; ?>