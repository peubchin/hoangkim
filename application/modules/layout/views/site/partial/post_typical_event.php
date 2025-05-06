<?php if (isset($data) && is_array($data) && !empty($data)): ?>
<div class="vc_row wpb_row vc_row-fluid kiki vc_custom_1477226774447">
	<div class="vc_column_container vc_col-sm-12">
		<div class="wpb_wrapper">
			<div class="vc_separator vc_text_separator vc_separator_align_center vc_sep_width_100 vc_sep_pos_align_center">
				<span class="vc_sep_holder vc_sep_holder_l">
					<span style="background-image: -webkit-linear-gradient(left, transparent, sandy_brown);
					background-image: linear-gradient(to right, transparent, sandy_brown);height:3px;" class="vc_sep_line">
					</span>
				</span>
				<h4 class="koiki">SỰ KIỆN TIÊU BIỂU</h4>    
				<span class="vc_sep_holder vc_sep_holder_r">
					<span style="background-image: -webkit-linear-gradient(left, sandy_brown, transparent);
					background-image: linear-gradient(to right, sandy_brown, transparent);height:3px;" class="vc_sep_line">
					</span>
				</span>
			</div>
			<br>        
			<div class="bt368-nav-ptt jps_bete_custom_css3378 grid-style-box-nav" id= "jps_bete_custom_css3378" data-style="bt-post-grid-3">               
				<div class="bt368-nav-ptt-content  bt-fix-top-filter">
					<!--data-->
					<div class="bt-control-wrap  ">                  
						<!--item-->
						<div class="bt-slider-item"> 
							<div class="bt-slider-item-content">
								<div class="bt-ajax-listing bt-option-3">
								<?php
								foreach ($data as $value):
									$data_id = $value['id'];
									$data_title = word_limiter($value['title'], 50);
									$data_hometext = word_limiter($value['hometext'], 30);
									$data_link = site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $data_id);
									$data_image = array(
										'src' => get_image(get_module_path('posts') . $value['homeimgfile'], get_module_path('posts') . 'no-image-thumb.png'),
										'alt' => $value['homeimgalt']
									);
									?>
									<div class="bt-post-item bt-ready-parallax bt-no-effect">
										<div class="bt-post-item-content">
											<div class="bt-picture-item">
												<a href="<?php echo $data_link; ?>">
													<div class="bt-img-parallax">
														<img src="<?php echo base_url('timthumb.php?src=' . $data_image['src'] . '&w=350&h=180&zc=1&q=85'); ?>" alt="" width="350" height="180">
													</div>
													<div class="bt-absolute-gradient-overlay"></div>
													<div class="bt-hover-grid-overlay"></div>
												</a>
											</div>
											<div class="bt-post-content bt-white-div">
												<div class="bt-h6 bt-title-config">
													<a href="<?php echo $data_link; ?>" class="bt-font-heading-1 bt-color-title "><?php echo $data_title; ?></a>
												</div>
												<div class="bt-get-excerpt bt-font-main-1">
													<?php echo $data_hometext; ?>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
								</div>               
							</div>                                           
						</div>
						<!--item-->        
					</div>
					<!--data-->
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<?php endif; ?>