<article>
  <section class="user-manager-page">
    <div class="bg-brea">
    <div class="container">
      <?php $this->load->view('breadcrumb'); ?>
      <div class="user-commission-buy-package-history">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="account-structure-page_main-content">
            <div class="account-change-email">
              <h2 class="account-structure-page_title">Hoa hồng giới thiệu mở thẻ</h2>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                      <div class="card-icon">
                        <i class="fa fa-check"></i>
                      </div>
                      <p class="card-category">Tổng số tiền hoa hồng giới thiệu mở thẻ thành công được hưởng</p>
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
                        <i class="fa far fa-clock"></i>
                      </div>
                      <p class="card-category">Tổng số tiền hoa hồng giới thiệu mở thẻ đang chờ xử lý</p>
                      <h3 class="card-title"><?php echo formatRice($total_pending); ?> VNĐ</h3>
                    </div>
                    <div class="card-footer">
                      <div class="stats">
                        <i class="fa far fa-clock"></i> Đang chờ
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
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
                </div> -->
              </div>
              <div class="box-devision-col-mobile">
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
                      <tr>
                          <td>Le Du</td>
                          <td class="text-right">+3.000.000</td>
                          <td class="text-center">15:00 31/10/2019</td>
                          <td class="text-center"><span class="badge badge-warning">Chờ xử lý</span></td>
                          <td>Người dùng được hưởng hoa hồng từ người dùng cấp dưới trực tiếp mua gói bạc 3 triệu đồng</td>
                      </tr>
                      <tr>
                          <td>Le Du</td>
                          <td class="text-right">+3.000.000</td>
                          <td class="text-center">15:53 29/10/2019</td>
                          <td class="text-center"><span class="badge badge-warning">Chờ xử lý</span></td>
                          <td>Người dùng được hưởng hoa hồng từ người dùng cấp dưới trực tiếp mua gói bạc 3 triệu đồng</td>
                      </tr>
                      <tr>
                          <td>Le Du</td>
                          <td class="text-right">+6.000.000</td>
                          <td class="text-center">12:02 05/11/2019</td>
                          <td class="text-center"><span class="badge badge-success">Nhận thành công</span></td>
                          <td>Người dùng được hưởng hoa hồng từ người dùng cấp dưới trực tiếp mua gói vàng 6 triệu đồng</td>
                      </tr>
                      <!-- <?php foreach ($rows as $row): ?>
                        <tr>
                          <td><?php echo $row['full_name']; ?></td>
                          <td class="text-right"><?php echo ($row['value_cost'] > 0 ? '+' : '') . formatRice($row['value_cost']); ?></td>
                          <td class="text-center"><?php echo display_date($row['created']); ?></td>
                          <td class="text-center"><?php echo $row['status'] == 1 ? display_label('Nhận thành công') : display_label('Chờ xử lý', 'warning'); ?></td>
                          <td><?php echo $row['message']; ?></td>
                        </tr>
                      <?php endforeach; ?> -->
                    </tbody>
                  </table>
                </div>
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
    </div>
    </div>
  </section>
</article>
