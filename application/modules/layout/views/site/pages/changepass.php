<article>
	<section>
		<div class="bg-brea">
			<div class="box-warpper">
				<div class="container">
					<?php $this->load->view('breadcrumb'); ?>
					<div class="change-password">
						<div class="row">
							<div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
								<div class="row">
									<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 offset-xl-2 offset-lg-2 offset-md-2 offset-sm-2">
										<div class="box-profile">
											<?php $this->load->view('layout/notify'); ?>
											<form action="<?php echo current_full_url(); ?>" method="post">
												<h3 class="title">Thay đổi mật khẩu</h3>
												<div class="form-group row">
													<label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Mật khẩu hiện tại</label>
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="password" class="form-control form-control-sm" name="current_password" placeholder="Mật khẩu hiện tại">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Mật khẩu mới</label>
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="password" class="form-control form-control-sm" name="password" placeholder="Mật khẩu mới">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Nhập lại mật khẩu mới</label>
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="password" class="form-control form-control-sm" name="password_confirm" placeholder="Nhập lại mật khẩu mới">
													</div>
												</div>
												<div class="form-group row">
													<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4">
														<button type="submit" class="btn btn-sm btn-success btn-block">Lưu</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
								<div class="box-slidebar-profile">
									<div class="card">
										<div class="card-body">
											<h3>TÀI KHOẢN CỦA TÔI</h3>
											<p>Tên tài khoản: <span><?php echo isset($user['full_name']) ? $user['full_name'] : ''; ?>!</span></p>
											<div class="block-content">
												<p>Địa chỉ: <?php echo isset($user['address']) ? $user['address'] : ''; ?></p>
												<p>Điện thoại: <?php echo isset($user['phone']) ? $user['phone'] : ''; ?></p>
												<p>Email: <?php echo isset($user['email']) ? $user['email'] : ''; ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</article>
