<article>
	<section>
		<div class="box-warpper">
	    <div class="container">
	      <div class="row">
					<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 order-first order-xl-9 order-lg-9 order-md-12">
            <?php $this->load->view('breadcrumb'); ?>
            <div class="block-single--post">
              <h1><?php echo isset($row['title']) ? $row['title'] : ''; ?></h1>
              <div class="meta-list">
                <!--<span class="date-post"><i class="far fa-clock"></i> <?php echo get_day_of_week_vi($row['addtime']); ?> - <?php echo date('d/m/Y H:i', $row['addtime']); ?></span>-->
              </div>
              <div class="block-content">
                <?php echo isset($row['bodyhtml']) ? $row['bodyhtml'] : ''; ?>
								<?php $this->load->view('addthis'); ?>
              </div>
            </div>
						<?php if (isset($related_rows) && trim($related_rows) != ''): ?>
		          <div class="box-post-related">
								<div class="block-title">
                  <h3>BÀI VIẾT LIÊN QUAN</h3>
                </div>
		            <div class="row">
		              <?php echo $related_rows; ?>
		            </div>
		          </div>
						<?php endif; ?>
          </div>
					<?php $this->load->view('block-left'); ?>
	      </div>
				<?php echo isset($products_bestseller) ? $products_bestseller : ''; ?>
	    </div>
    </div>
  </section>
</article>
