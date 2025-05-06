<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <?php foreach ($data as $data_cat): ?>
        <?php if (isset($data_cat['items']) && !empty($data_cat['items'])): ?>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<?php
				$t = 0;
				foreach ($data_cat['items'] as $value):
					$t++;
					$data_id = $value['id'];
					$data_title = convert_to_uppercase($value['title']);
					$data_hometext = $value['hometext'];
					$data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
					$data_image = array(
						'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image-thumb.png'),
						'alt' => $value['homeimgalt']
					);
					?>
					<?php if($t==1): ?>
					<div class="post-item">
						<a href="<?php echo $data_link; ?>"><img class="img-responsive" src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=369&h=200&zc=1&q=100'); ?>" alt=""></a>
						<h3 class="title"><a href="<?php echo $data_link; ?>"><span><?php echo $data_title; ?></span></a></h3>
						<div class="block-content">
							<?php echo $data_hometext; ?>
						</div>
					</div>
					<?php else: ?>
					<div class="post-orther-item">
						<a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=117&h=64&zc=1&q=100'); ?>" alt=""></a>
						<h3><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
						<div class="clearfix"></div>
					</div>
					<?php endif; ?>
				<?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>