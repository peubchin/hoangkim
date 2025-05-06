<style type="text/css">
    .mgl-5{
        margin-left: 5px !important;
    }
</style>
<article>
    <section class="user-manager-page">
        <div class="bg-brea">
            <div class="container">
                <?php $this->load->view('breadcrumb'); ?>
                <div class="users_commission">
                    <div class="row">
                        <?php $this->load->view('block-left-admin'); ?>
                        <?php
                        $enable_withdrawal = filter_var(get_config_value('enable_withdrawal'), FILTER_VALIDATE_BOOLEAN);
                        $enable_withdrawal_bonus = filter_var(get_config_value('enable_withdrawal_bonus'), FILTER_VALIDATE_BOOLEAN);
                        ?>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <div class="account-structure-page_main-content">
                                <div class="account-change-email">
                                    <h2 class="account-structure-page_title">Ví cá nhân 
                                        <?php if($enable_withdrawal): ?>
                                            <a href="<?php echo site_url('rut-tien'); ?>" class="float-right mgl-5">Rút tiền</a>
                                        <?php endif; ?>
                                        <?php if($enable_withdrawal_bonus): ?>
                                            <a class="float-right" href="<?php echo site_url('rut-tien-thuong'); ?>">Rút tiền thưởng</a>
                                        <?php endif; ?>
                                    </h2>
                                    <div class="box-devision-col-mobile">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="<?php echo site_url('lich-su-giao-dich'); ?>" target="_blank"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Thu nhập được rút</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_revenue); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="<?php echo site_url('lich-su-thuong-doanh-so'); ?>" target="_blank"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tiền thưởng KPI được rút<br>(Update...)</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_current_revenue_bonus); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="javascript:void(0)" target="_blank"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Thuế thu nhập cá nhân</span>
                                                        <span class="info-box-number ng-binding"><?php //echo formatRice($total_withdrawal_fee); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div> -->
                                           <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="<?php echo site_url('hoa-hong-he-thong-su-dung-dich-vu'); ?>" target="_blank"><i class="far fa-gem"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Trả thưởng kết nối tài khoản mua sắm
                                                            <!-- <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span> -->
                                                        </span>
                                                        <!--<span class="info-box-number ng-binding"><?php echo formatRice($total_sub_buy_root + $total_sub_buy); ?> VNĐ</span>
                                                        <span class="info-box-number ng-binding"><?php //echo formatRice($total_commission_buy); ?> VNĐ</span>-->
                                                        <!-- <div class="progress">
                                                            <div class="progress-bar" style="width:0"></div>
                                                        </div> -->
                                                        <!-- <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <i class="far fa-money-bill-alt"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Trả thưởng Đơn hàng trực tiếp
                                                            <!-- <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-info-circle tipso tipso_style " data-original-title="<div style='text-transform: initial;text-align: left;'>Hoa hồng được tính từ tất <br>cả các chuyển đổi được phát sinh, <br>không phân biệt trạng thái.</div> " data-html="true"></span> -->
                                                        </span>
                                                        <!-- <span class="info-box-number ng-binding"><?php //echo formatRice($total_buy); ?> VNĐ</span> 
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_sub_buy_root); ?> VNĐ</span>-->
                                                        <!-- <div class="progress">
                                                            <div class="progress-bar" style="width:0"></div>
                                                        </div> -->
                                                        <!-- <span class="progress-description ng-binding"><span data-toggle="tooltip" data-placement="top">Chuyển đổi: </span>0</span> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <i class="far fa-money-bill-alt"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Trả thưởng Đơn hàng gián tiếp</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_sub_buy); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="<?php echo site_url('lich-su-rut-tien'); ?>" target="_blank"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Lịch sử rút tiền</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_withdrawal); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="<?php echo site_url('nap-tien'); ?>" target="_blank"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Lịch sử thanh toán đơn hàng</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_pay_in); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="javascript:void(0)"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">CPNB sở hữu</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($stock); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="javascript:void(0)"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">CPNB chính thức</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($stock_official); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="javascript:void(0)"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Điểm HK tri ân</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($dividend); ?></span>
                                                    </div>
                                                </div>
                                            </div>
											<!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="javascript:void(0)"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Doanh số cá nhân từ 25/10</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_buy_current); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <a href="javascript:void(0)"><i class="far fa-money-bill-alt"></i></a>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tổng doanh số cá nhân cộng dồn</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_buy); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <i class="far fa-money-bill-alt"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Doanh số cá nhân từ 01/08</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_buy_connect); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <i class="far fa-money-bill-alt"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Doanh số trực tiếp trong tháng</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_accumulated_F1); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-lightgreen">
                                                        <i class="far fa-money-bill-alt"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">(update)Thưởng KPI trong tháng</span>
                                                        <span class="info-box-number ng-binding"><?php echo formatRice($total_revenue_bonus); ?> VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
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