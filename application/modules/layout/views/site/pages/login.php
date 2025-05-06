<article>
	<section>
		<div class="bg-brea">
			<div class="container">
				<?php $this->load->view('breadcrumb'); ?>
				<div class="login-main">
					<h4>ĐĂNG NHẬP</h4>
					<div class="row">
						<div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 offset-xl-1 offset-lg-1 offset-md-1">
							<?php $this->load->view('layout/notify'); ?>
							<form id="f-login" action="<?php echo current_full_url(); ?>" method="post">
								<div class="form-group required<?php echo form_error('username') != '' ? ' has-error' : ''; ?>">
									<label for="username" class="control-label">Tên đăng nhập</label>
									<input type="text" maxlength="255" name="username" class="form-control">
									<?php echo form_error('username'); ?>
								</div>
								<div class="form-group required<?php echo form_error('password') != '' ? ' has-error' : ''; ?>">
									<label for="password" class="control-label">Mật khẩu</label>
									<input type="password" maxlength="255" name="password" class="form-control">
									<?php echo form_error('password'); ?>
								</div>
								<div class="form-group row mb-1">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
										<p class="note">Bạn đã có tài khoản chưa. Nếu chưa hãy nhấp <a href="<?php echo site_url('dang-ky'); ?>">vào đây để đăng ký.</a><br>
											Bạn quên mật khẩu? Bấm <a href="<?php echo site_url('quen-mat-khau'); ?>">vào đây để lấy lại mật khẩu.</a></p>
											<div class="text-center">
												<button type="submit" class="btn btn-sm btn-success">Đăng nhập</button>
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
			</div>
		</section>
	</article>
