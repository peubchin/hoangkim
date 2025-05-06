<article>
	<section>
		<div class="bg-brea">
			<div class="container">
				<?php $this->load->view('breadcrumb'); ?>
				<div class="forget-password-main">
					<h4>QUÊN MẬT KHẨU</h4>
					<?php $this->load->view('layout/notify'); ?>
					<form id="f-forget-password" action="<?php echo current_full_url(); ?>" method="post">
						<div class="form-group row">
							<label for="email" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-form-label col-form-label-sm">Email</label>
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="form-group">
									<div class="form-group required<?php echo form_error('email') != '' ? ' has-error' : ''; ?>">
										<!-- <label for="password_confirm" class="control-label">Email</label> -->
										<input type="text" maxlength="255" name="email" class="form-control">
										<div class="input-group-append pt-2">
											<button class="btn btn-sm btn-success" type="submit">Gửi</button>
										</div>
										<?php echo form_error('email'); ?>
									</div>
								</div>
								<!-- <div class="input-group">
								<input type="text" class="form-control form-control-sm" id="f-forget-password-email" placeholder="Email">
								<div class="input-group-append">
								<button class="btn btn-sm btn-success" type="submit">Gửi</button>
							</div>
						</div> -->
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</section>
</article>
