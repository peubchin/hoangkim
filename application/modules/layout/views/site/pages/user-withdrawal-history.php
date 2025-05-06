<?php $bool = (isset($rows) && is_array($rows) && !empty($rows)) ? TRUE : FALSE; ?>
<article>
    <section class="user-manager-page">
        <div class="bg-brea">
            <div class="container">
                <?php $this->load->view('breadcrumb'); ?>
                <div class="users_withrawal-history">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="account-structure-page_main-content">
                                <div class="account-change-email">
                                    <h2 class="account-structure-page_title">Lịch sử rút tiền</h2>
                                    <div class="row">
                                        <?php if($bool): ?>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="card card-stats">
                                                <div class="card-header card-header-success card-header-icon">
                                                    <div class="card-icon">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                    <p class="card-category">Tổng số tiền đã rút thành công</p>
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
                                                    <p class="card-category">Tổng số tiền rút đang chờ xử lý</p>
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
                                    <?php if($bool): ?>
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
                                                    <?php foreach ($rows as $row): ?>
                                                        <?php
                                                        $status = ' text-warning';
                                                        $status_text = 'Chờ xử lý';
                                                        $status_value = '-' . formatRice($row['value_cost']);
                                                        if($row['status'] == 1){
                                                            $status = ' text-success';
                                                            $status_text = 'Đã rút';
                                                            $status_value = '-' . formatRice($row['value_cost']);
                                                        }elseif ($row['status'] == -1) {
                                                            $status = ' text-danger';
                                                            $status_text = 'Đã hủy';
                                                            $status_value = formatRice($row['value_cost']);
                                                        }
                                                        ?>
                                                        <tr>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td class="text-right<?php echo $status; ?>"><?php echo $status_value; ?></td>
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