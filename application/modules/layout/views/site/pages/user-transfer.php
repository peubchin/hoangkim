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
                  <h2 class="account-structure-page_title">Thông tin chuyển tiền <a href="<?php echo site_url('lich-su-chuyen-tien'); ?>" class="float-right"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử chuyển tiền</a></h2>
                  <div class="box-devision-col-mobile">
                    <?php $this->load->view('layout/notify'); ?>
                    <div class="account-change-email">
                      <form id="f-transfer" action="<?php echo current_full_url(); ?>" method="post">
                        <div class="row form-group<?php echo form_error('to_user_id') != '' ? ' has-error' : ''; ?>">
                          <div class="col-lg-3 col-md-3 col-sm-5">
                            <p class="account-change-email_header">Người nhận *</p>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            <select class="form-control" name="to_user_id">
                              <?php echo display_option_select($users, 'userid', 'username', 0); ?>
                            </select>
                            <?php echo form_error('to_user_id'); ?>
                          </div>
                        </div>
                        <div class="row form-group<?php echo form_error('amount') != '' ? ' has-error' : ''; ?>">
                          <div class="col-lg-3 col-md-3 col-sm-5">
                            <p class="account-change-email_header">Số tiền chuyển *</p>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            <input class="form-control mask-price" type="text" name="amount" value="" placeholder="Bội số của 100.000, ví dụ 100.000, 200.000,...">
                            <?php echo form_error('amount'); ?>
                          </div>
                        </div>
                        <div class="row form-group">
                          <div class="col-lg-3 col-md-3 col-sm-5">
                            <p class="account-change-email_header">Ghi chú</p>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            <textarea class="form-control" rows="3" name="note" placeholder="Ghi chú..."></textarea>
                          </div>
                        </div>
                        <div class="row form-group">
                          <div class="col-lg-4 col-md-5 col-sm-5 offset-lg-3 offset-md-3 offset-sm-5">
                            <a href="<?php echo site_url('hoa-hong'); ?>" class="btn btn-danger">Hủy</a>
                            <button type="submit" class="btn btn-warning account-change-email_btn-last">Chuyển tiền</button>
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
      </div>
    </div>
  </section>
</article>
