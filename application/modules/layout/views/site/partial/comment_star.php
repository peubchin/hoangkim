<?php if (isset($data) && is_array($data) && !empty($data)): ?>
	<?php
	$comments_stars = array(
		1 => 'one',
		2 => 'two',
		3 => 'three',
		4 => 'four',
		5 => 'five'
	);
	$total = array_sum($data);
	krsort($data);
	?>
	<?php foreach($data as $key => $value): ?>
	<div class="<?php echo $comments_stars[$key]; ?> histo-rate">
		<span class="histo-star"><i class="active fa fa-star"></i> <?php echo $key; ?></span>
		<span class="bar-block">
			<span id="bar-<?php echo $comments_stars[$key]; ?>" class="bar" data-percent="<?php echo $total != 0 ? round($value*100/$total) : 0; ?>" style="width: <?php echo $total != 0 ? round($value*100/$total) : 0; ?>%">
				<span><?php echo $value; ?></span>&nbsp;
			</span>
		</span>
	</div>
	<?php endforeach; ?>
<?php endif; ?>