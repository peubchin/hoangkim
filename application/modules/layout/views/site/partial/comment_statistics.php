<?php if (isset($row['comments_average']) && isset($row['comments_total'])): ?>
	<?php if(isset($row['comments_average'])): ?>
	<span class="rating-num"><?php echo $row['comments_average']; ?></span>
	<div class="rating-stars">
		<?php for($i = 1; $i <= 5; $i++): ?>
			<?php if($i <= $row['comments_average']): ?>
				<span><i class="active fa fa-star"></i></span>
			<?php else: ?>
				<span><i class="fa fa-star"></i></span>
			<?php endif; ?>
		<?php endfor; ?>
	</div>
	<?php endif; ?>
	<div class="rating-users">
		<i class="icon-user"></i> <?php echo isset($row['comments_total']) ? ($row['comments_total']) : 0; ?> total
	</div>
<?php endif; ?>