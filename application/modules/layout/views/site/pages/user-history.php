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
                                            <h2 class="account-structure-page_title" style="padding-top: 10px; font-weight: 700;">Lịch sử giao dịch</h2>
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
                                                            <label class="col-sm-3 col-form-label no-padding-left" style="padding-right: 0; text-align: right;">Hoạt động</label>
                                                            <div class="col-sm-9">
                                                                <select class="form-control input-sm" name="action">
                                                                    <option value="">Tất cả</option>
                                                                    <?php echo get_option_select($this->config->item('users_modules_commission'), isset($get['action']) ? $get['action'] : '', TRUE); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group pull-right">
                                                            <button type="submit" class="btn btn-primary">Áp dụng</button>
                                                            &nbsp;<a class="btn btn-danger" href="<?php echo base_url($module_slug . '/' . 'export') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>">Xuất dữ liệu</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if($bool): ?>
                                        <div class="box-devision-col-mobile">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#ID</th>
                                                            <th>Hoạt động</th>
                                                            <th class="text-right">Giá trị</th>
                                                            <th class="text-right">Hoa hồng</th>
                                                            <th class="text-center">Thời gian</th>
                                                            <th class="text-center">Trạng thái</th>
                                                            <th>Ghi chú</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($rows as $row): ?>
                                                            <?php $action = $row['action']; ?>
                                                            <tr>
                                                                <td><?php echo $row['id']; ?></td>
                                                                <td>
                                                                    <?php echo display_label(display_value_array($this->config->item('users_modules_commission'), $action), display_value_array($this->config->item('users_modules_commission_label'), $action), 4); ?>
                                                                    <?php if(in_array($action, array('SELL', 'BUY', 'SUB_BUY', 'SUB_BUY_ROOT'))): ?>
                                                                        <p><?php echo display_label($row['order_code'], 'primary', 4); ?><?php echo in_array($action, array('SUB_BUY', 'SUB_BUY_ROOT')) ? ' ' . display_label(formatRice($row['value_real']), 'info', 4) : (in_array($action, array('BUY', 'SELL')) ? ' ' . display_label(formatRice($row['value_accumulated']), 'primary', 4) : ''); ?></p>
                                                                    <?php elseif(in_array($action, array('WITHDRAWAL', 'WITHDRAWAL_BONUS'))): ?>
                                                                        <?php
                                                                        $info_withdrawal = get_info_withdrawal_user($row['user_id']);
                                                                        if($info_withdrawal){
                                                                            echo "<p>Điện thoại: $info_withdrawal[phone]";
                                                                            echo "<br>Thông tin: $info_withdrawal[account_holder], số tk $info_withdrawal[account_number], $info_withdrawal[bank_name], $info_withdrawal[bank_branch]</p>";
                                                                        }
                                                                        ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-right"><?php echo (in_array($action, array('WITHDRAWAL', 'WITHDRAWAL_BONUS')) ? '-' : ($row['value_cost'] > 0 ? '+' : '')) . formatRice($row['value_cost']); ?></td>
                                                                <td class="text-right"><?php echo !in_array($action, array('SUB_BUY', 'SUB_BUY_ROOT', 'REVENUE_BONUS')) ? '' : ($row['value'] > 0 ? '+' : '') . formatRice($row['value']); ?></td>
                                                                <td class="text-center"><?php echo display_date($row['created']); ?></td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    if($row['status'] == 1){
                                                                        echo display_label('Khả dụng', 'success', 4);
                                                                    }elseif($row['status'] == 0){
                                                                        echo display_label('Đã yêu cầu', 'warning', 4);
                                                                    }else{
                                                                        echo display_label('Đã hủy yêu cầu', 'danger', 4);
                                                                    }
                                                                    ?>
                                                                </td>
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