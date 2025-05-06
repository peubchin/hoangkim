<article>
	<section>
		<div class="bg-brea">
			<div class="container">
				<?php $this->load->view('breadcrumb'); ?>
				<div class="register-main">
					<h4>TẠO TÀI KHOẢN MỚI - BƯỚC 1</h4>
					<div class="row">
						<div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 offset-xl-1 offset-lg-1 offset-md-1">
							<?php $this->load->view('layout/notify'); ?>
							<form id="f-register-step-1" action="<?php echo current_full_url(); ?>" method="post">
								<div class="form-group required<?php echo form_error('phone') != '' ? ' has-error' : ''; ?>">
									<label for="f-register-step-1-phone" class="control-label">Số điện thoại</label>
									<input type="text" class="form-control" name="phone" id="f-register-step-1-phone" maxlength="10">
									<?php echo form_error('phone'); ?>
								</div>
								<div class="form-group row mb-1">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
										<p class="note">Bạn đã có tài khoản rồi. Nhấp <a href="<?php echo site_url('dang-nhap'); ?>">vào đây</a> để đăng nhập</p>
										<div class="text-center">
											<button type="submit" class="btn btn-sm btn-success">Tiếp tục đăng ký</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
						<?php if(isset($login_introdue_none) && is_array($login_introdue_none) && !empty($login_introdue_none)): ?>
							<?php foreach ($login_introdue_none as $value): ?>
								<div class="login-introdue">
									<div class="box-login-introdue">
										<div class="box-footer-logo">
											<a href="<?php echo $value['link']; ?>"><img src="<?php echo base_url(get_module_path('images') . ((trim($value['image']) != '') ? $value['image'] : $value['image'])); ?>" alt="" class="img-fluid"></a>
										</div>
										<div class="box-footer-content">
											<h3><?php echo $value['title']; ?></h3>
											<?php echo $value['content']; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
							<div class="clearfix"></div>
						<?php endif; ?>
					</div>
					</div>
				</div>
			</div>
		</section>
	</article>
