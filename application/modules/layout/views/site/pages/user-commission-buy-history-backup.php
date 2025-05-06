<?php $bool = (isset($rows) && is_array($rows) && !empty($rows)) ? TRUE : FALSE; ?>
<article>
  	<section class="user-manager-page">
	    <div class="container">
	      	<div class="row">
		        <?php $this->load->view('block-left-user'); ?>
		  		<div class="col-lg-10 col-md-10 col-sm-9">
		          	<div class="account-structure-page_main-content">
			            <div class="account-change-email">
							<h2 class="account-structure-page_title">Hoa hồng hệ thống sử dụng dịch vụ</h2>
							<div class="row">
								<?php if($bool): ?>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<div class="card card-stats">
										<div class="card-header card-header-success card-header-icon">
											<div class="card-icon">
												<i class="fa fa-check"></i>
											</div>
											<p class="card-category">Tổng số tiền hoa hồng hệ thống sử dụng dịch vụ thành công được hưởng</p>
											<h3 class="card-title"><?php echo formatRice($total_success); ?> VNĐ</h3>
										</div>
										<div class="card-footer">
											<div class="stats">
												<i class="fa fa-check"></i> Thành công
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<div class="card card-stats">
										<div class="card-header card-header-warning card-header-icon">
											<div class="card-icon">
												<i class="fa fa-clock-o"></i>
											</div>
											<p class="card-category">Tổng số tiền hoa hồng hệ thống sử dụng dịch vụ đang chờ xử lý</p>
											<h3 class="card-title"><?php echo formatRice($total_pending); ?> VNĐ</h3>
										</div>
										<div class="card-footer">
											<div class="stats">
												<i class="fa fa-clock-o"></i> Đang chờ
											</div>
										</div>
									</div>
								</div>
								<?php else: ?>
								<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
									<div class="card card-stats">
										<div class="card-header card-header-danger card-header-icon">
											<div class="card-icon">
												<i class="fa fa-archive"></i>
											</div>
											<p class="card-category">Chưa có giao dịch nào</p>
											<h3 class="card-title"></h3>
										</div>
										<div class="card-footer">
											<div class="stats">
												<i class="fa fa-archive"></i> Chưa có giao dịch nào được thực hiện
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>
							<div class="box-devision-col-mobile">
								<?php if($bool): ?>
						        <div class="table-responsive">
						            <table class="table table-striped table-bordered table-hover">
						                <thead>
						                    <tr>
						                        <th>Người dùng</th>
						                        <th class="text-right">Giá trị</th>
						                        <th class="text-center">Thời gian</th>
												<th class="text-center">Trạng thái</th>
												<th>Ghi chú</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    <?php foreach ($rows as $row): ?>
						                        <tr>
						                            <td><?php echo $row['full_name']; ?></td>
						                            <td class="text-right"><?php echo ($row['value_cost'] > 0 ? '+' : '') . formatRice($row['value_cost']); ?></td>
						                            <td class="text-center"><?php echo display_date($row['created']); ?></td>
						                            <td class="text-center"><?php echo $row['status'] == 1 ? display_label('Nhận thành công') : display_label('Chờ xử lý', 'warning'); ?></td>
						                            <td><?php echo $row['message']; ?></td>
						                        </tr>
						                    <?php endforeach; ?>
						                </tbody>
						            </table>
						        </div>
								<?php endif; ?>
							</div>
							<div class="clearfix"></div>
							<div class="box-pagination">
								<?php if (isset($pagination) && $pagination != ''): ?>
									<?php echo $pagination; ?>
								<?php endif; ?>
							</div>
			            </div>
		          	</div>
		        </div>
	      	</div>
	    </div>
  	</section>
</article>