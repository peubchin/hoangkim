<?php $bool = (isset($rows) && is_array($rows) && !empty($rows)) ? TRUE : FALSE; ?>
<article>
    <section class="user-manager-page">
        <div class="bg-brea">
            <div class="container">
                <?php $this->load->view('breadcrumb'); ?>
                <div class="users-commission-buy-history">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="account-structure-page_main-content">
                                <div class="account-change-email">
                                    <h2 class="account-structure-page_title">Hoa hồng hệ thống mua hàng</h2>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="card card-stats">
                                                <div class="card-header card-header-success card-header-icon">
                                                    <div class="card-icon">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                    <p class="card-category">Tổng số tiền hoa hồng hệ thống đặt hàng thành công</p>
                                                    <h3 class="card-title">
                                                        <?php echo formatRice($total_success); ?> VNĐ</h3>
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
                                                    <p class="card-category">Tổng số tiền hoa hồng hệ thống mua hàng đang chờ xử lý</p>
                                                    <h3 class="card-title">
                                                        <?php echo formatRice($total_pending); ?> VNĐ</h3>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="stats">
                                                        <i class="fa far fa-clock"></i> Đang chờ
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($bool): ?>
                                    <div class="box-devision-col-mobile">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#ID</th>
                                                        <th>Người dùng</th>
                                                        <th class="text-right">Doanh số nhận thưởng</th>
                                                        <th class="text-right">Giá trị được nhận</th>
                                                        <th class="text-center">Thời gian</th>
                                                        <th class="text-center">Trạng thái</th>
                                                        <th>Ghi chú</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rows as $row): ?>
                                                    <?php
                                                    $percent = $row['percent'];
                                                    $status = ' text-warning';
                                                    $status_text = 'Chờ xử lý';
                                                    $status_value = formatRice(abs($row['value_cost']));
                                                    $status_value_promotion = (abs($row['value']) > 0 ? '+' : '') . formatRice(abs($row['value']));
                                                    if($row['status'] == 1){
                                                        $status = ' text-success';
                                                        $status_text = 'Đã nhận';
                                                    }elseif ($row['status'] == -1) {
                                                        $status = ' text-danger';
                                                        $status_text = 'Đã hủy';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['id']; ?></td>
                                                        <td><?php echo $row['customer_full_name'] . " (" . $row['customer_username'] . ")"; ?></td>
                                                        <td class="text-right<?php echo $status; ?>"><?php echo $status_value; ?></td>
                                                        <td class="text-right<?php echo $status; ?>"><?php echo $status_value_promotion; ?></td>
                                                        <td class="text-center"><?php echo display_date($row['created']); ?></td>
                                                        <td class="text-center<?php echo $status; ?>"><?php echo $status_text; ?></td>
                                                        <td><?php echo $row['message']; ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if (isset($pagination) && trim($pagination) != ''): ?>
                                    <div class="box-pagination">
                                        <?php echo $pagination; ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>