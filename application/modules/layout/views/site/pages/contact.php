<article>
	<section>
		<div class="box-warpper">
	    <div class="container">
	      <div class="row">
					<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 order-first order-xl-9 order-lg-9 order-md-12">
						<?php $this->load->view('breadcrumb'); ?>
						<div class="block-contact-page">
							<div class="embed-map embed-responsive embed-responsive-4by3">
								<?php echo isset($iframe_map) ? $iframe_map : ''; ?>
							</div>
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
									<h2>THÔNG TIN LIÊN HỆ</h2>
									<?php echo isset($site_content_contact) ? $site_content_contact : ''; ?>
								</div>
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
									<h2>GỬI THÔNG TIN LIÊN HỆ</h2>
									<?php $this->load->view('layout/notify'); ?>
									<form class="form-horizontal" action="<?php echo site_url('lien-he'); ?>" method="post">
		                <div class="form-group row">
		                  <label class="col-form-label col-form-label-sm col-xl-3 col-lg-3 col-md-3 col-sm-3" for="full_name">Tên:</label>
		                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
		                    <input type="text" class="form-control form-control-sm" id="full_name" name="full_name" placeholder="Họ và tên">
		                  </div>
		                </div>
		                <div class="form-group row">
		                  <label class="col-form-label col-form-label-sm col-xl-3 col-lg-3 col-md-3 col-sm-3" for="email">Email:</label>
		                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
		                    <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email">
		                  </div>
		                </div>
		                <div class="form-group row">
		                  <label class="col-form-label col-form-label-sm col-xl-3 col-lg-3 col-md-3 col-sm-3" for="phone">Số điện thoại:</label>
		                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
		                    <input type="text" class="form-control form-control-sm" id="phone" name="phone" placeholder="Điện thoại">
		                  </div>
		                </div>
										<div class="form-group row">
		                  <label class="col-form-label col-form-label-sm col-xl-3 col-lg-3 col-md-3 col-sm-3" for="address">Địa chỉ:</label>
		                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
		                    <input type="text" class="form-control form-control-sm" id="address" name="address" placeholder="Địa chỉ">
		                  </div>
		                </div>
										<div class="form-group row">
		                  <label class="col-form-label col-form-label-sm col-xl-3 col-lg-3 col-md-3 col-sm-3" for="subject">Tiêu đề:</label>
		                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
		                    <input type="text" class="form-control form-control-sm" id="subject" name="subject" placeholder="Chủ đề">
		                  </div>
		                </div>
		                <div class="form-group row">
		                  <label class="col-form-label col-form-label-sm col-xl-3 col-lg-3 col-md-3 col-sm-3" for="message">Nội dung:</label>
		                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
		                    <textarea class="form-control form-control-sm" id="message" name="message" rows="6" placeholder="Nội dung"></textarea>
		                  </div>
		                </div>
		                <div class="form-group row">
		                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
		                    <button class="btn submit-contact" type="submit" name="button">Gửi liên hệ</button>
		                  </div>
		                </div>
		              </form>
									<div class="clearfix"></div>
									<br>
								</div>
							</div>
						</div>
          </div>
					<?php $this->load->view('block-left'); ?>
	      </div>
				<?php echo isset($products_bestseller) ? $products_bestseller : ''; ?>
	    </div>
    </div>
  </section>
</article>
