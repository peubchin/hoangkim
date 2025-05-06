<article>
  <section class="user-manager-page">
    <div class="bg-brea">
      <div class="container">
        <?php $this->load->view('breadcrumb'); ?>
        <div class="users_cash-drawing">
          <div class="row">
            <?php $this->load->view('block-left-admin'); ?>
            <div class="col-lg-9 col-md-9 col-sm-9">
              <div class="account-structure-page_main-content">
                <div class="account-change-email">
                  <h2 class="account-structure-page_title">Thông tin rút tiền thưởng <!-- <a href="<?php echo site_url('lich-su-rut-tien'); ?>" class="float-right"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử rút tiền thưởng</a> --></h2>
                  <div class="box-devision-col-mobile">
                    <?php $this->load->view('layout/notify'); ?>
                    <div class="account-change-email">
                      <form id="f-withdrawal" action="<?php echo current_full_url(); ?>" method="post">
                        <div class="row form-group<?php echo form_error('amount') != '' ? ' has-error' : ''; ?>">
                          <div class="col-lg-3 col-md-3 col-sm-5">
                            <p class="account-change-email_header">Số tiền rút *</p>
                          </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form-control mask-price" name="amount" id="amount" value="" placeholder="Bội số của 1.000, tối thiểu 200.000, ví dụ 200.000, 201.000, ...">
                                <?php echo form_error('amount'); ?>
                                <!-- <p style="color: #2c1c1c; font-size: 13px; margin-bottom: 0;">Thuế thu nhập cá nhân là 10%</p>
                                <p id="total" class="d-none" style="color: #2c1c1c; font-size: 13px; margin-bottom: 0;">Số tiền ước tính: <strong></strong></p> -->
                            </div>
                        </div>
                        <!--
                        <div class="row form-group">
                        <div class="col-lg-3 col-md-3 col-sm-5">
                        <p class="account-change-email_header">Chọn phương thức rút tiền thưởng: </p>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6">
                      <select class="form-control border-cart valid" name="txtTypeWithdraw" style="width: 100%; padding: 5px 10px;">
                      <option value="0">Rút nhanh - Phí 3% - Thời gian trong 60 giây</option>
                      <option value="1">Rút chậm - Phí 0% - Thời gian trong 24 giờ làm việc</option>
                    </select>
                  </div>
                </div>
              -->
              <div class="row form-group">
                <div class="col-lg-3 col-md-3 col-sm-5">
                  <p class="account-change-email_header">Số điện thoại *</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <input class="form-control" type="text" value="<?php echo isset($user['phone']) ? $user['phone'] : ''; ?>" disabled>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-lg-3 col-md-3 col-sm-5">
                  <p class="account-change-email_header">Tên chủ sở hữu *</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <input class="form-control" type="text" value="<?php echo isset($user['account_holder']) ? $user['account_holder'] : ''; ?>" disabled>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-lg-3 col-md-3 col-sm-5">
                  <p class="account-change-email_header">Số tài khoản *</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <input class="form-control" type="text" value="<?php echo isset($user['account_number']) ? $user['account_number'] : ''; ?>" disabled>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-lg-3 col-md-3 col-sm-5">
                  <p class="account-change-email_header">Tên ngân hàng *</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <select class="form-control" disabled>
                    <?php echo display_option_select($banker, 'id', 'name', isset($user['banker_id']) ? $user['banker_id'] : 0); ?>
                  </select>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-lg-3 col-md-3 col-sm-5">
                  <p class="account-change-email_header">Chi nhánh ngân hàng *</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <input class="form-control" type="text" value="<?php echo isset($user['branch_bank']) ? $user['branch_bank'] : ''; ?>" disabled>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-lg-4 col-md-5 col-sm-5 offset-lg-3 offset-md-3 offset-sm-5">
                  <a href="<?php echo site_url('vi-ca-nhan'); ?>" class="btn btn-danger">Hủy</a>
                  <button type="submit" class="btn btn-warning account-change-email_btn-last">Rút tiền thưởng</button>
                </div>
              </div>
            </form>
            <div style="text-align:center; border: solid 1px grey;margin:20px;">
                        <p>(*)Lưu ý: Thành viên vui lòng nhập thông tin tài khoản ngân hàng trong phần tài khoản cá nhân<br>
                          Lệnh rút tiền thưởng được thực hiện vào ngày 5 và 6 hàng tháng <br>Thời gian nhận thanh toán từ 1h đến 48h<br>
                          Nếu sau 48h mà tài khoản chưa có tiền vui lòng liên hệ Bộ phần kế toán: 035 875 7452 để được hỗ trợ.
                        </p>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</section>
</article>
