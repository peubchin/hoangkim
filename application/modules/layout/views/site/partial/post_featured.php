<?php if (isset($data) && is_array($data) && !empty($data)):?>
	<section>
		<div class="box-post-bestseller">
			<div class="block--title">
				<h3>TIN TỨC NỔI BẬT</h3>
			</div>
			<div class="">
				<?php
				$i = 0;
				$count = count($data);
				foreach ($data as $value):
					$i++;
					$data_id = $value['id'];
					$data_title = word_limiter($value['title'], 15);
					$data_hometext = word_limiter($value['hometext'], 8);
					$data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
					$data_image = array(
						'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
						'alt' => $value['homeimgalt']
					);
					?>
					<div class="post-related-item">
						<div class="block-images">
							<a href="<?php echo $data_link; ?>"><img class="img-fluid" src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=255&h=188&zc=2&q=100'); ?>" /></a>
						</div>
						<div class="block-content">
							<h3><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
							<p><?php echo $data_hometext; ?></p>
						</div>
					</div>
					<?php if($i!=$count): ?>
						<hr>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
