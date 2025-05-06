<div class="container sidebar">
	<div class="row">
		<div class="col-md-3">
			<div class="box-menu">
				<h4 class="titlebox">DANH MỤC SẢN SẢN PHẨM</h4>
				<div class="content">
					<div class="menu-tree">
						<ul>
						  <?php echo multilevelMenu(0, $shops_cat_list, $shops_cat_data); ?>
						</ul>
					</div>
				</div>
			</div>
			<?php echo isset($posts_featured) ? $posts_featured : ''; ?>
		</div>
		<div class="col-md-9">

		</div>
	</div>
</div>