<style type="text/css">
.file {
	visibility: hidden;
	position: absolute;
}
</style>
<article>
	<section>
		<div class="bg-brea">
			<div class="box-warpper">
				<div class="container">
					<?php $this->load->view('breadcrumb'); ?>
					<div class="profile-main">
						<div class="row">
							<div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
								<div class="row">
									<?php $this->load->view('block-left-admin'); ?>
									<div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
										<div class="box-profile">
											<?php $this->load->view('layout/notify'); ?>
											<form id="f-profile" action="<?php echo current_full_url(); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
												<h3 class="title">Chia sẻ đăng ký thành viên</h3>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header" style="font-weight: 700; color: #f00;">Mã chia sẻ của bạn</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<div class="input-group">
															<input class="form-control" type="text" value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" id="share-username" readonly>
															<span class="input-group-btn">
																<button type="button" class="btn btn-primary btn-copy" data-clipboard-action="copy" data-clipboard-target="#share-username"><i class="fas fa-paste"></i></button>
															</span>
														</div>
													</div>
												</div>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Link chia sẻ của bạn</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<div class="input-group">
															<input class="form-control" type="text" value="<?php echo isset($row['username']) ? site_url('dang-ky') . '?ref=' . $row['username'] : ''; ?>" id="link-share-username" readonly>
															<span class="input-group-btn">
																<button type="button" class="btn btn-primary btn-copy" data-clipboard-action="copy" data-clipboard-target="#link-share-username"><i class="fas fa-paste"></i></button>
															</span>
														</div>
													</div>
												</div>
												<?php if(isset($row['username']) && trim($row['username']) != ''): ?>
													<div class="row form-group">
														<div class="col-lg-4 col-md-4 col-sm-12">
															<p class="account-change-email_header">QR code cá nhân</p>
														</div>
														<div class="col-lg-8 col-md-8 col-sm-12">
															<a href="<?php echo get_admin_url('users/qr/download') . '?file=' . rawurlencode(base_url(get_module_path('users_qr') . $row['username'] . '.png')); ?>"><img src="<?php echo base_url(get_module_path('users_qr') . $row['username'] . '.png'); ?>" alt="" class="img-fluid"></a>
														</div>
													</div>
												<?php endif; ?>
												<h3 class="title">Thay đổi thông tin cá nhân</h3>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Địa chỉ email</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<input class="form-control" type="text" name="email" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" readonly>
													</div>
												</div>
												<div class="form-group row<?php echo form_error('full_name') != '' ? ' has-error' : ''; ?>">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Họ và tên*</p>
													</div>
													<!-- <label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Họ và tên <span style="color: #f00; font-weight: 700;">*</span></label> -->
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="text" class="form-control form-control-sm" name="full_name" value="<?php echo isset($row['full_name']) ? $row['full_name'] : ''; ?>" placeholder="Nhập họ và tên">
														<?php echo form_error('full_name'); ?>
													</div>
												</div>
												<div class="form-group row<?php echo form_error('address') != '' ? ' has-error' : ''; ?>">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Địa chỉ*</p>
													</div>
													<!-- <label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Địa chỉ <span style="color: #f00; font-weight: 700;">*</span></label> -->
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="text" class="form-control form-control-sm" name="address" value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>" placeholder="Nhập địa chỉ">
														<?php echo form_error('address'); ?>
													</div>
												</div>
												<div class="form-group row<?php echo form_error('phone') != '' ? ' has-error' : ''; ?>">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Điện thoại*</p>
													</div>
													<!-- <label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Điện thoại <span style="color: #f00; font-weight: 700;">*</span></label> -->
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="text" class="form-control form-control-sm" name="phone" value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>" placeholder="Nhập số điện thoại">
														<?php echo form_error('phone'); ?>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Ngày tháng năm sinh*</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<div class="input-group input-append date" id="datePicker">
															<?php $birthday = (isset($row['birthday']) && ($row['birthday'] != 0) ? date('d-m-Y', $row['birthday']) : ''); ?>
															<input type="text" class="form-control" name="birthday" value="<?php echo $birthday; ?>" />
															<div class="input-group-append">
															<span class="input-group-text add-on"><i class="fas fa-calendar-alt"></i></span>
														</div>
														</div>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Ngày cấp*</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<div class="input-group input-append date" id="datePicker-card-date">
															<?php $identity_card_date = (isset($row['identity_card_date']) && ($row['identity_card_date'] != 0) ? date('d-m-Y', $row['identity_card_date']) : ''); ?>
															<input type="text" class="form-control" name="identity_card_date" value="<?php echo $identity_card_date; ?>" />
															<div class="input-group-append">
																<span class="input-group-text add-on"><i class="fas fa-calendar-alt"></i></span>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Mã ID quản lý hoặc mã khuyến mãi (nếu có )</p>
													</div>
													<!-- <label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Mã ID quản lý hoặc mã khuyến mãi (nếu có )</label> -->
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="text" class="form-control form-control-sm" value="<?php echo isset($row['parent_username']) ? $row['parent_username'] : ''; ?>" readonly>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Chọn ảnh đại diện</p>
													</div>
													<!-- <label class="col-form-label col-form-label-sm col-xl-4 col-lg-4 col-md-4 col-sm-4">Chọn ảnh đại diện</label> -->
													<div class="col-lg-8 col-md-8 col-sm-8">
														<input type="file" id="photo" name="photo[]" class="file" accept="image/*">
														<div class="input-group">
															<input type="text" class="form-control" disabled placeholder="Chọn ảnh" id="file">
															<div class="input-group-append">
																<button type="button" class="browse btn btn-primary">Chọn ảnh...</button>
															</div>
														</div>
														<img src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>" id="preview" class="img-thumbnail my-3">
													</div>
												</div>
												<h3 class="title">Thông tin tài khoản ngân hàng</h3>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Tên chủ sở hữu</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<input class="form-control" type="text" name="account_holder" value="<?php echo isset($row['account_holder']) ? $row['account_holder'] : ''; ?>">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Số tài khoản</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<input class="form-control" type="text" name="account_number" value="<?php echo isset($row['account_number']) ? $row['account_number'] : ''; ?>">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Tên ngân hàng</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<select class="form-control" name="banker_id">
															<?php echo display_option_select($banker, 'id', 'name', isset($row['banker_id']) ? $row['banker_id'] : 0); ?>
														</select>
													</div>
												</div>
												<div class="row form-group">
													<div class="col-lg-4 col-md-4 col-sm-12">
														<p class="account-change-email_header">Chi nhánh ngân hàng</p>
													</div>
													<div class="col-lg-8 col-md-8 col-sm-12">
														<input class="form-control" type="text" name="branch_bank" value="<?php echo isset($row['branch_bank']) ? $row['branch_bank'] : ''; ?>">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4">
														<button type="submit" class="btn btn-sm btn-success btn-block">Lưu</button>
													</div>
												</div>
											</form>
											<!--<h3 class="title">Video hướng dẫn</h3>
											<div class="row form-group">
											<div class="col-lg-4 col-md-4 col-sm-12">
											<p class="account-change-email_header">Link video hướng dẫn</p>
										</div>
										<div class="col-lg-8 col-md-8 col-sm-12">
										<a href="<?php echo isset($info_link_video_none['content']) ? $info_link_video_none['content'] : ''; ?>"> <i class="fab fa-youtube"></i> </a>
									</div>
								</div>-->
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
					<div class="box-slidebar-profile">
						<div class="card-image pb-4">
							<img src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>" alt="" class="img-fluid rounded-circle">
						</div>
						<div class="card">
							<div class="card-body">
								<h3>TÀI KHOẢN CỦA TÔI</h3>
								<p>Tên tài khoản: <span><?php echo isset($row['full_name']) ? $row['full_name'] : ''; ?>!</span></p>
								<div class="block-content">
									<p>Địa chỉ: <?php echo isset($row['address']) ? $row['address'] : ''; ?></p>
									<p>Điện thoại: <?php echo isset($row['phone']) ? $row['phone'] : ''; ?></p>
									<p>Email: <?php echo isset($row['email']) ? $row['email'] : ''; ?></p>
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
