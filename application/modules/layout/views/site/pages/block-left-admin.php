<?php
$enable_withdrawal = filter_var(get_config_value('enable_withdrawal'), FILTER_VALIDATE_BOOLEAN);
$enable_withdrawal_bonus = filter_var(get_config_value('enable_withdrawal_bonus'), FILTER_VALIDATE_BOOLEAN);
?>
<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 p-0 block-left-admin">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('vi-ca-nhan'); ?>"><i class="fas fa-tachometer-alt"></i> Thống kê</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('quan-ly-don-hang'); ?>"><i class="fas fa-cart-plus"></i> Đơn mua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('hoa-hong-he-thong-su-dung-dich-vu'); ?>"><i class="fas fa-dollar-sign"></i> Hoa hồng hệ thống </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('he-thong'); ?>"><i class="fa fa-users"></i> Hệ thống</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#item-1"><i class="fas fa-exchange-alt"></i> Giao dịch <i class="fa fa-caret-down float-right" aria-hidden="true"></i></a>
            <div id="item-1" class="collapse">
                <ul class="nav flex-column">
                  <!--<li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('nap-tien'); ?>">Nạp tiền</a>
                  </li>
                    -->
                    <?php if($enable_withdrawal): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('rut-tien'); ?>">Rút tiền</a>
                    </li>
                    <?php endif; ?>
                    <?php if($enable_withdrawal_bonus): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('rut-tien-thuong'); ?>">Rút tiền thưởng</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('lich-su-giao-dich'); ?>">Lịch sử giao dịch</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('trang-ca-nhan'); ?>"><i class="fa fa-user" aria-hidden="true"></i> Thông tin cá nhân</a>
        </li>
    </ul>
</div>