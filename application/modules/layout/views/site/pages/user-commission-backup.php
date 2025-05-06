<article>
  <section class="user-manager-page">
    <div class="container">
      <div class="row">
        <?php $this->load->view('block-left-user'); ?>
        <div class="col-lg-10 col-md-10 col-sm-9">
          <div class="account-structure-page_main-content">
            <div class="account-change-email">
              <h2 class="account-structure-page_title">Hoa hồng <a href="<?php echo site_url('rut-tien'); ?>" class="pull-right"><?php echo $total_commission >= CASH_DRAWING_MIN ? '<i class="fa fa-check text-success" aria-hidden="true"></i> ' : ''; ?>Rút tiền</a></h2>
              <div class="box-devision-col-mobile">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green">
                        <a href="<?php echo site_url('rut-tien'); ?>" target="_blank"><i class="fa fa-credit-card"></i></a>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">TỔNG SỐ TIỀN
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding"><?php echo formatRice($total_revenue); ?> VNĐ</span>
                        <div class="progress">
                          <div class="progress-bar" style="width:0"></div>
                        </div>
                        <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span><?php echo formatRice($cash_drawinged); ?> VNĐ</span>
                      </div>
                    </div>
                  </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-purple">
                                <a href="<?php echo site_url('lich-su-nap-tien'); ?>" target="_blank"><i class="fa fa-money"></i></a>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">TỔNG SỐ TIỀN ĐÃ NẠP
                                    <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                                </span>
                                <span class="info-box-number ng-binding"><?php echo formatRice($total_pay_in); ?> VNĐ</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width:0"></div>
                                </div>
                                <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-purple">
                                <a href="<?php echo site_url('lich-su-mua-hang'); ?>" target="_blank"><i class="fa fa-shopping-cart"></i></a>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">TỔNG SỐ TIỀN ĐÃ MUA
                                    <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                                </span>
                                <span class="info-box-number ng-binding"><?php echo formatRice($total_buy); ?> VNĐ</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width:0"></div>
                                </div>
                                <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Hoa hồng cho hệ thống: </span><?php echo formatRice($total_buy_system); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-lightgreen">
                                <a href="<?php echo site_url('lich-su-rut-tien'); ?>" target="_blank"><i class="fa fa-money"></i></a>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">TỔNG SỐ TIỀN ĐÃ RÚT
                                    <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                                </span>
                                <span class="info-box-number ng-binding"><?php echo formatRice($total_withdrawal); ?> VNĐ</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width:0"></div>
                                </div>
                                <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span>
                            </div>
                        </div>
                    </div>
                  <div class="clearfix"></div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-lightgreen">
                        <a href="<?php echo site_url('hoa-hong-gioi-thieu-mo-the'); ?>" target="_blank"><i class="fa fa-diamond"></i></a>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">HOA HỒNG GIỚI THIỆU MỞ THẺ
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding"><?php echo formatRice($total_commission_package); ?> VNĐ</span>
                        <div class="progress">
                          <div class="progress-bar" style="width:0"></div>
                        </div>
                        <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-blue">
                        <a href="<?php echo site_url('hoa-hong-he-thong-su-dung-dich-vu'); ?>" target="_blank"><i class="fa fa-shopping-cart"></i></a>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">HOA HỒNG HỆ THỐNG SỬ DỤNG DỊCH VỤ
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding"><?php echo formatRice($total_commission_buy); ?> VNĐ</span>
                        <div class="progress">
                          <div class="progress-bar" style="width:0"></div>
                        </div>
                        <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>
