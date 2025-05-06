<?php
$total_commission = $total_commission_price + $total_users_referred + $total_users_referred_commission - $cash_drawinged;
$total_commission_limited = $total_commission - ($total_commission % CASH_DRAWING_MULTIPLES);
?>
<article>
  <section class="user-manager-page">
    <div class="container">
      <div class="row">
        <?php $this->load->view('block-left-user'); ?>
        <div class="col-lg-10 col-md-10 col-sm-9">
          <div class="account-structure-page_main-content">
            <div class="account-change-email">
              <h2 class="account-structure-page_title">Rút tiền <a href="<?php echo site_url('rut-tien/lich-su'); ?>" class="pull-right"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử rút tiền</a></h2>
              <div class="box-devision-col-mobile">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-green">
                        <i class="fa fa-credit-card"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">TỔNG SỐ TIỀN HOA HỒNG CỦA BẠN
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding<?php echo $total_commission >= CASH_DRAWING_MIN ? ' text-success' : ' text-danger'; ?>"><?php echo formatRice($total_commission); ?> VNĐ</span>
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
                        <i class="fa fa-money"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">HOA HỒNG BÁN HÀNG CỦA BẠN
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding"><?php echo formatRice($total_commission_price); ?> VNĐ</span>
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
                        <a href="<?php echo site_url('hoa-hong-thuong-them'); ?>" target="_blank"><i class="fa fa-diamond"></i></a>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text"><a href="<?php echo site_url('hoa-hong-thuong-them'); ?>" target="_blank">HOA HỒNG THƯỜNG THÊM</a>
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding"><?php echo formatRice($total_users_referred); ?> VNĐ</span>
                        <div class="progress">
                          <div class="progress-bar" style="width:0"></div>
                        </div>
                        <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span><?php echo display_label($referred_count_censor, 'warning') . ' ' . display_label($referred_count_censorred); ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-blue">
                        <a href="<?php echo site_url('hoa-hong-thanh-vien'); ?>" target="_blank"><i class="fa fa-shopping-cart"></i></a>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text"><a href="<?php echo site_url('hoa-hong-thanh-vien'); ?>" target="_blank">HOA HỒNG BÁN HÀNG CỦA THÀNH VIÊN</a>
                          <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span>
                        </span>
                        <span class="info-box-number ng-binding"><?php echo formatRice($total_users_referred_commission); ?> VNĐ</span>
                        <div class="progress">
                          <div class="progress-bar" style="width:0"></div>
                        </div>
                        <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span>
                      </div>
                    </div>
                  </div>
                </div>
                <h2 class="account-structure-page_title">Thông tin rút tiền</h2>
                  <?php $this->load->view('layout/notify'); ?>
                  <div class="account-change-email">
                    <form action="<?php echo current_full_url(); ?>" method="post">
                      <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                          <p class="account-change-email_header">Số tiền rút *</p>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-5">
                          <input class="form-control" type="number" name="amount" value="<?php echo $total_commission_limited; ?>" step="<?php echo CASH_DRAWING_MULTIPLES; ?>" min="<?php echo CASH_DRAWING_MIN; ?>" max="<?php echo $total_commission_limited; ?>"<?php echo $total_commission >= CASH_DRAWING_MIN ? '' : ' disabled="disabled"'; ?>>
                          <?php echo form_error('amount'); ?>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                          <p class="account-change-email_header">Số điện thoại *</p>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-5">
                          <input class="form-control" type="text" name="phone" value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>">
                          <?php echo form_error('phone'); ?>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                          <p class="account-change-email_header">Tên chủ sở hữu</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                          <input class="form-control" type="text" name="account_holder" value="<?php echo isset($row['account_holder']) ? $row['account_holder'] : ''; ?>">
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                          <p class="account-change-email_header">Số tài khoản</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                          <input class="form-control" type="text" name="account_number" value="<?php echo isset($row['account_number']) ? $row['account_number'] : ''; ?>">
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                          <p class="account-change-email_header">Tên ngân hàng</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                          <select class="form-control" name="banker">
                            <?php echo get_option_select($this->config->item('banker'), isset($row['banker']) ? $row['banker'] : ''); ?>
                          </select>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                          <p class="account-change-email_header">Chi nhánh ngân hàng</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                          <input class="form-control" type="text" name="branch_bank" value="<?php echo isset($row['branch_bank']) ? $row['branch_bank'] : ''; ?>">
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-4 col-md-5 col-sm-5 col-lg-offset-3 col-md-offset-3 col-sm-offset-5">
                            <a href="<?php echo site_url('hoa-hong'); ?>" class="btn btn-default">Hủy</a>
                            <button type="submit" class="btn btn-warning account-change-email_btn-last"<?php echo $total_commission >= CASH_DRAWING_MIN ? '' : ' disabled="disabled"'; ?>>Rút tiền</button>
                        </div>
                      </div>
                    </form>
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
