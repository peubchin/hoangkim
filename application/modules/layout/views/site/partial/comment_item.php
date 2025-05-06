<?php if (isset($data) && is_array($data) && !empty($data)): ?>
	<?php
	foreach($data as $value):
		$data_id = $value['id'];
		$data_val = $value['val'];
		$data_author = $value['author'];
		$data_subject = $value['subject'];
		$data_comment = $value['comment'];
		$data_is_verified_purchase = $value['is_verified_purchase'] == 1 ? TRUE : FALSE;
		$data_date = date('d M, Y', $value['created']);
	?>
	<div class="media">
		<img class="align-self-start mr-3" width="64px" height="64px" src="<?php echo get_asset('img_path'); ?>avarta.png">
		<div class="media-body">
			<div class="rating-public-view">
			  	<ul>
			  		<?php for($i = 1; $i <= 5; $i++): ?>
						<?php if($i <= $data_val): ?>
							<li><a class="active" title="<?php echo $i; ?>"></a></li>
						<?php else: ?>
							<li><a title="<?php echo $i; ?>"></a></li>
						<?php endif; ?>
					<?php endfor; ?>
			  	</ul>
			</div>
			<span><?php echo $data_subject; ?></span>
			<h5 class="mt-0"><?php echo $data_author; ?><br><small class="date-time"><?php echo $data_date; ?></small></h5>
			<p><?php echo nl2br($data_comment); ?></p>
		</div>
	</div>
	<?php endforeach; ?>
<?php else: ?>
	<p class="note">Không có bình luận nào cả.</p>
<?php endif; ?>