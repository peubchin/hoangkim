<?php if (isset($row) && is_array($row) && !empty($row)): ?>
	<?php
		$sizes = isset($row['sizes']) ? explode(',', $row['sizes']) : NULL;
		if(is_array($sizes) && !empty($sizes)):
			?>
		<p class="fillter-single-shop-title">Chọn size của bạn</p>
		<div class="fillter-single-shop-size">
			<?php
			$i = 0;
			foreach($sizes as $size):
				$i++;
			?>
			<div class="button" style="float: left;margin: 0 5px;">
				<input type="radio" name="size" value="<?php echo (int) $size; ?>" id="size<?php echo $i; ?>"<?php echo ($i == 1) ? ' checked="checked"' : ''; ?> />
				<label for="size<?php echo $i; ?>"><div><?php echo (int) $size; ?></div></label>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>
<?php endif; ?>
