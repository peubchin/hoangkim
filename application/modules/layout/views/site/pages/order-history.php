<?php $bool = (isset($rows) && is_array($rows) && !empty($rows)) ? TRUE : FALSE; ?>
<style type="text/css">
    .input-group-addon {
        padding: .375rem .75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-color: #d2d6de;
        background-color: #fff;
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
    }
    .pull-right{
        float: right;
    }
    .no-padding{
        padding: 0;
    }
    .no-padding-left{
        padding-left: 0;
    }
</style>
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
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h2 class="account-structure-page_title" style="padding-top: 10px; font-weight: 700;">Đơn hàng của tôi</h2>
                                        </div>
                                        <div class="col-md-10">
                                            <form method="get" action="<?php echo site_url($module_slug); ?>" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div >
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label">Ngày</label>
                                                                <div class="col-sm-9">
                                                                    <div class="input-group input-append date" id="datePickerFromday">
                                                                        <input type="text" class="form-control input-sm" readonly id="fromday" name="fromday" value="<?php echo isset($get['fromday']) && ($get['fromday'] != '') ? $get['fromday'] : ''; ?>" />
                                                                        <span class="input-group-addon add-on"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label">Đến ngày</label>
                                                                <div class="col-sm-9">
                                                                    <div class="input-group input-append date" id="datePickerToday">
                                                                        <input type="text" class="form-control input-sm" readonly id="today" name="today" value="<?php echo isset($get['today']) && ($get['today'] != '') ? $get['today'] : ''; ?>" />
                                                                        <span class="input-group-addon add-on"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label no-padding-left" style="padding-right: 0; text-align: right;">Trạng thái</label>
                                                            <div class="col-sm-9">
                                                                <select class="form-control input-sm" name="status">
                                                                    <option value="">Tất cả</option>
                                                                    <?php echo get_option_select($this->config->item('orders_transaction_status'), isset($get['status']) ? $get['status'] : ''); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group pull-right">
                                                            <button type="submit" class="btn btn-primary">Áp dụng</button>
                                                            <!-- &nbsp;<a class="btn btn-danger" href="<?php echo base_url($module_slug . '/' . 'export') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>">Xuất dữ liệu</a> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php $this->load->view('layout/notify'); ?>
                                    <?php if($bool): ?>
                                    <div class="box-devision-col-mobile">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#ID</th>
                                                        <th>Mã đơn hàng</th>
                                                        <th class="text-center">Ngày đặt</th>
                                                        <th class="text-center">Tổng tiền (vnđ)</th>
                                                        <th class="text-center">Trạng thái thanh toán</th>
                                                        <th>Ghi chú</th>
                                                        <th class="text-center">Chức năng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rows as $row): ?>
                                                        <tr>
                                                            <td><?php echo $row['order_id']; ?></td>
                                                            <td>
                                                                <?php echo $row['order_code']; ?>
                                                            </td>
                                                            <td class="text-center"><?php echo display_date($row['created']); ?></td>
                                                            <td class="text-right"><?php echo formatRice($row['order_amount']); ?></td>
                                                            <td class="text-center">
                                                                <?php echo show_badge(display_value_array($this->config->item('orders_transaction_status'), $row['transaction_status']), display_value_array($this->config->item('orders_transaction_status_label'), $row['transaction_status'])); ?>
                                                            </td>
                                                            <td><?php echo nl2br($row['order_note']); ?></td>
                                                            <td class="text-center">
                                                                <div class="btn-group-vertical">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                            Hành động
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?php echo site_url('chi-tiet-don-hang/' . $row['order_id']); ?>">Xem</a>
                                                                            <?php if (FALSE && $row['transaction_status'] == 0): ?>
                                                                            <a class="dropdown-item btn-status-cancel" href="<?php echo base_url('huy-don-hang/' . $row['order_id']); ?>">Huỷ</a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
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