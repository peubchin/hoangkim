<section>
	<div class="row">
		<div class="col-xl-9 col-lg-8 col-md-0">
		 <?php echo isset($posts_home) ? $posts_home : ''; ?>
		</div>
		<div class="col-xl-3 col-lg-4 col-md-5 offset-xl-0 offset-lg-0 offset-md-4">
			<div class="support-online">
				<p class="block-title">HỖ TRỢ KHÁCH HÀNG</p>
				<p class="block-phone">Hotline : <?php echo isset($info_hotline_dvkh_none['content']) ? strip_tags($info_hotline_dvkh_none['content'], "<span>") : ''; ?></p>
				<p class="block-mail">E-mail : <?php echo isset($info_email_dvkh_none['content']) ? strip_tags($info_email_dvkh_none['content'], "<span>") : ''; ?></p>
				<p class="block-zalo">Zalo : <?php echo isset($info_skype_dvkh_none['content']) ? strip_tags($info_skype_dvkh_none['content'], "<span>") : ''; ?></p>
			</div>
		</div>
	</div>
</section>
