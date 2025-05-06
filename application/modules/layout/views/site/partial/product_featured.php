<?php if (isset($data) && is_array($data) && !empty($data)):?>
	<div class="box-product-feature">
		<h2 class="title">SẢN PHẨM NỔI BẬT</h2>
		<div class="clearfix"></div>
		<div class="row row-7px">
			<?php
			$i = 0;
		  foreach ($data as $value):
		    $i++;
				$data_id = $value['id'];
				$data_title = convert_to_uppercase($value['title']);
				$data_hometext = word_limiter($value['hometext'], 16);
				$data_link = site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $data_id);
				$data_image = array(
					'src' => get_image(get_module_path('shops') . $value['homeimgfile'], get_module_path('shops') . 'no-image.png'),
					'alt' => ''
				);
				$data_price = $value['product_price'];
        $data_sales_price = get_product_discounts($value['product_price'], $value['product_sales_price']);
				?>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 col-7px">
					<div class="box-product-item">
						<div class="new-label">
							NEW
						</div>
						<div class="block-images">
							<a href="<?php echo $data_link; ?>"><img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=195&h=166&zc=1&q=100'); ?>" alt="" class="img-responsive"></a>
						</div>
						<div class="block-content">
							<h3 class="title-logo"><a href="<?php echo $data_link; ?>"><?php echo $data_title; ?></a></h3>
							<?php if ($data_sales_price > 0): ?>
								<?php if ($data_sales_price == $data_price): ?>
									<p><?php echo formatRice($data_price); ?>VNĐ</p>
								<?php else: ?>
		              <p><?php echo  formatRice($data_sales_price);?> VNĐ</p>
		              <small><?php echo  formatRice($data_price);?> VNĐ</small>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if($i==4) : ?>
		      <div class="clearfix"></div>
		    <?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
