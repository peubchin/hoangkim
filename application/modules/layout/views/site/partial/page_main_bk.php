<?php
if (isset($data) && is_array($data) && !empty($data)):?>
    <h2 class="text-center text-trans-upcase open-sans text-white mar-t-b-30">CHƯƠNG TRÌNH KHUYẾN MÃI</h2>
    <div class="row">
	<?php foreach ($data as $value):
        $data_id = $value['id'];
        $data_title = word_limiter($value['title'], 8);
        $data_hometext = word_limiter($value['hometext'], 150);
        $data_link = site_url($value['alias'] . '-' . $data_id);
		$data_image = array(
            'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image.png'),
            'alt' => $value['homeimgalt']
        );
        ?>
		<div class="col-md-4">
			<div class="bg-news">
				<a href="<?php echo $data_link; ?>"><img class="img-responsive border" src="<?php echo $data_image['src']; ?>" /></a>
				<div class="pad-l-r-20">
					<p class="open-sans size-16-px text-limit-2d text-justify mar-t-b-15"><a class="a-text-black-1" href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></p>
					<p class="open-sans size-13-px text-limit-5d text-justify mar-b-20"><?php echo $data_hometext; ?></p>
					<p class="open-sans size-16-px text-semi-bold pad-b-25"><a class="a-text-green" href="<?php echo $data_link; ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Read more</a></p>
				</div>
			</div>
		</div>
    <?php endforeach; ?>
	</div>
<?php endif; ?>