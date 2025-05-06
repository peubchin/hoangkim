<?php if (isset($data) && is_array($data) && !empty($data)): ?>
<h2 style="font-size: 25px;color: #e86c28;text-align: center;font-family:Open Sans;font-weight:600;font-style:normal" class="vc_custom_heading"><?php echo isset($title) ? $title : ''; ?></h2>
<div class="porto-separator"><hr class="separator-line  align_center" style="background-image: -webkit-linear-gradient(left, transparent, orange, transparent); background-image: linear-gradient(to right, transparent, orange, transparent);height:3px;"></div>
	<?php
	foreach ($data as $value):
		$data_id = $value['id'];
		$data_title = $value['title'];
		$data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
		?>
		<div><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></div>
	<?php endforeach; ?>
<?php endif; ?>