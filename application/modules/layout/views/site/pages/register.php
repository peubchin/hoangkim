<?php $is_ref = isset($ref) && (trim($ref) != ''); ?>
<article>
	<section>
		<div class="bg-brea">
			<div class="container">
				<?php $this->load->view('breadcrumb'); ?>
				<div class="register-main">
					<h4>TẠO TÀI KHOẢN MỚI</h4>
					<div class="row">
						<div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 offset-xl-1 offset-lg-1 offset-md-1">
							<?php $this->load->view('layout/notify'); ?>
							<form id="f-register" action="<?php echo current_full_url(); ?>" method="post">
								<div class="form-group required<?php echo form_error('full_name') != '' ? ' has-error' : ''; ?>">
									<label for="full_name" class="control-label">Họ và tên</label>
									<input type="text" class="form-control" name="full_name">
									<?php echo form_error('full_name'); ?>
								</div>
								<div class="form-group">
									<div class="form-group required<?php echo form_error('username') != '' ? ' has-error' : ''; ?>">
										<label class="control-label">Tên đăng nhập</label>
										<input type="text" class="form-control" name="username" id="f-register-username">
										<?php echo form_error('username'); ?>
										<p class="note" style="font-size: .8em;">*Tên đăng nhập phải viết liền không dấu, tối đa 20 ký tự</p>
									</div>
								</div>
								<div class="form-group">
									<div class="form-group required<?php echo form_error('password') != '' ? ' has-error' : ''; ?>">
										<label for="password" class="control-label">Mật khẩu</label>
										<input type="password" maxlength="255" name="password" id="f-register-password" class="form-control">
										<?php echo form_error('password'); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="form-group required<?php echo form_error('password_confirm') != '' ? ' has-error' : ''; ?>">
										<label for="password_confirm" class="control-label">Nhập lại mật khẩu</label>
										<input type="password" maxlength="255" name="password_confirm" class="form-control">
										<?php echo form_error('password_confirm'); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="form-group required<?php echo form_error('email') != '' ? ' has-error' : ''; ?>">
										<label for="email" class="control-label">Email</label>
										<input type="text" maxlength="255" name="email" id="f-register-email" class="form-control">
										<?php echo form_error('email'); ?>
									</div>
								</div>
								<div class="form-group required<?php echo form_error('phone') != '' ? ' has-error' : ''; ?>">
									<label for="phone" class="control-label">Số điện thoại</label>
									<input type="text" maxlength="255" name="phone" id="f-register-phone" class="form-control">
									<?php echo form_error('phone'); ?>
									<p class="note" style="font-size: .8em;">*Số điện thoại chưa sử dụng đăng ký lần nào</p>
								</div>
								<div class="form-group">
									<label for="address" class="control-label">Địa chỉ</label>
									<input type="text" maxlength="255" name="address" class="form-control">
								</div>
								<div class="form-group">
									<label for="ref" class="control-label">Mã giới thiệu</label>
									<input type="text" class="form-control" maxlength="255" name="ref" value="<?php echo $is_ref ? $ref : ''; ?>"<?php echo $is_ref ? ' readonly="readonly"' : ''; ?>>
								</div>
								<div class="form-group required<?php echo form_error('identity_number_card') != '' ? ' has-error' : ''; ?>">
									<label for="identity_number_card" class="label">Số CMND/ Số thẻ căn cước</label>
									<input id="identity_number_card" name="identity_number_card" type="text" class="form-control">
									<?php echo form_error('identity_number_card'); ?>
									<p class="note" style="font-size: .8em;">*Chỉ được nhập định dạng số</p>
								</div>
								<div class="form-group">
									<div class="form-group">
										<label class="control-label">Mã số thuế cá nhân</label>
										<input type="text" class="form-control" name="tax_identification_number" maxlength="255">
									</div>
								</div>
								<!--
								<div class="group form-group">
									<label for="identity_card_front" class="label">Hình chứng minh nhân dân (mặt trước) *</label>
									<input name="identity_card_front[]" class="file" type="file" data-min-file-count="1">
								</div>
								<div class="group">
									<label class="label">Hình chứng minh nhân dân (mặt sau) *</label>
									<input name="identity_card_back[]" class="file" type="file" data-min-file-count="1">
								</div>
								-->
								<div class="form-group row mb-1">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
										<p class="note">Bạn đã có tài khoản rồi. Nhấp <a href="<?php echo site_url('dang-nhap'); ?>">vào đây</a> để đăng nhập</p>
										<div class="text-center">
											<button type="submit" class="btn btn-sm btn-success">Đăng ký</button>
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
