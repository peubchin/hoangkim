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
                  <h2 class="account-structure-page_title">Thông tin nạp tiền <a href="<?php echo site_url('lich-su-nap-tien'); ?>" class="float-right"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử nạp tiền</a></h2>
                  <div class="box-devision-col-mobile">
                    <?php $this->load->view('layout/notify'); ?>
                    <div class="account-change-email">
                      <form id="f-pay-in" action="<?php echo current_full_url(); ?>" method="post">
                        <div class="row form-group<?php echo form_error('amount') != '' ? ' has-error' : ''; ?>">
                          <div class="col-lg-3 col-md-3 col-sm-5">
                            <p class="account-change-email_header">Số tiền nạp *</p>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            <input class="form-control mask-price" type="text" name="amount" value="" placeholder="Bội số của 100.000, ví dụ 100.000, 200.000,...">
                            <?php echo form_error('amount'); ?>
                          </div>
                        </div>
                        <div class="row form-group">
                          <div class="col-lg-4 col-md-5 col-sm-5 offset-lg-3 offset-md-3 offset-sm-5">
                            <a href="<?php echo site_url('hoa-hong'); ?>" class="btn btn-danger">Hủy</a>
                            <button type="submit" class="btn btn-warning account-change-email_btn-last">Nạp tiền</button>
                          </div>
                        </div>
                      </form>
                      <div style="text-align:center; border: solid 1px grey;margin:20px;">
                        <p>Sau khi nhập lệnh nạp tiền vui lòng chuyển khoản theo đúng số tiền cần nạp vào số tài khoản sau:<br>
                          Số tài khoản: <strong>5678979668</strong> <br>
                          Tên tài khoản: <strong>CÔNG TY CỔ PHẦN THƯƠNG MẠI VÀ DỊCH VỤ HOÀNG KIM GROUP</strong> <br>
                          Ngân hàng: <strong>Ngân hàng ACB - Chi nhánh Tp.Hồ Chí Minh</strong> <br>
                          Nội dung: <strong>username</strong> <br>
                          Sau khi nhận được thanh toán, hoangkimonline.net sẽ nạp tiền tài khoản cho thành viên. Nếu sau 30 phút mà tài khoản chưa có tiền vui lòng liên hệ Bộ phần kế toán: 090 823 4437 .
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
