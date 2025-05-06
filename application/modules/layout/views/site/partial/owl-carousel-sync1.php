<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <?php
    foreach ($data as $value):
        $image = get_image(get_module_path('shops') . $value['image'], get_module_path('shops') . 'no-image.png');
		?>
		<div class="item"><span class="demowrap"><a href="<?php echo $image; ?>" class="lightbox" rel="group1" title="<?php echo isset($row['title']) ? $row['title'] : ''; ?>"><img class="demo1 img-responsive" src="<?php echo $image; ?>"></a></span></div>
	<?php endforeach; ?>
<?php endif; ?>