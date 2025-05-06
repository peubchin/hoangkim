<?php
if(!isset($role)){
    $role = '';
}
$is_role_full_access = in_array($role, array('ADMIN', 'ACCOUNTANT')) ? TRUE : FALSE;
$is_admin = isset($group_id) && in_array($group_id, array(6));
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form name="filter" method="get" action="<?php echo get_admin_url($module_slug); ?>" autocomplete="off">
            <nav class="search_bar navbar navbar-default" role="search">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#filter-bar-7adecd427b033de80d2a0e30cf74e735">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="filter-bar-7adecd427b033de80d2a0e30cf74e735">
                    <div class="navbar-form">
                        <div class="form-group search_title">
                            Hoạt động
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="action">
                                <option value="">Tất cả</option>
                                <?php echo get_option_select($this->config->item('users_modules_commission'), isset($get['action']) ? $get['action'] : '', TRUE); ?>
                            </select>
                        </div>

                        <div class="form-group search_title">
                            Trạng thái
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="status">
                                <option value="">Tất cả</option>
                                <?php echo get_option_select($this->config->item('users_modules_commission_status'), isset($get['status']) ? $get['status'] : '', TRUE); ?>
                            </select>
                        </div>

                        <div class="form-group search_title">
                            Từ ngày
                        </div>
                        <div class="form-group search_input">
                            <div class="input-group input-append date" id="datePickerFromday">
                                <input type="text" class="form-control input-sm" readonly id="fromday" name="fromday" value="<?php echo isset($get['fromday']) && ($get['fromday'] != '') ? $get['fromday'] : ''; ?>" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="form-group search_title">
                            Đến ngày
                        </div>
                        <div class="form-group search_input">
                            <div class="input-group input-append date" id="datePickerToday">
                                <input type="text" class="form-control input-sm" readonly id="today" name="today" value="<?php echo isset($get['today']) && ($get['today'] != '') ? $get['today'] : ''; ?>" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>

                        <div class="clearfix" style="margin-bottom: 10px;"></div>

                        <div class="form-group search_title">
                            Số dòng hiển thị
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="per_page">
                                <?php echo get_option_per_page(isset($get['per_page']) ? (int) $get['per_page'] : $this->config->item('item', 'admin_list')); ?>
                            </select>
                        </div>

                        <div class="form-group search_title">
                            Từ khóa
                        </div>
                        <div class="form-group search_input">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" name="q" value="<?php echo isset($get['q']) ? $get['q'] : ''; ?>"  placeholder="Từ khóa tìm kiếm">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
                                    <a class="btn btn-danger btn-sm" href="<?php echo get_admin_url($module_slug); ?>">Xóa điều kiện lọc</a>
                                </span>
                            </div>
                        </div>
                        <br>
                        <label><em>Từ khóa tìm kiếm không dùng các mã html</em></label>
                    </div>
                </div>
            </nav>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Lịch sử giao dịch</h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <?php if($is_role_full_access): ?>
                            <a class="btn btn-sm btn-info" href="<?php echo get_admin_url($module_slug . '/' . 'export') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>"><i class="fa fa-cloud-download"></i> Xuất dữ liệu</a>
                            <!-- <a class="btn btn-sm btn-success" href="<?php echo get_admin_url($module_slug . '/' . 'content'); ?>"><i class="fa fa-plus"></i> Nạp tiền cho thành viên</a> -->
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?php if (!empty($rows)): ?>
                    <form class="form-inline" name="main" method="post" action="<?php echo get_admin_url($module_slug . '/' . 'main'); ?>" autocomplete="off">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <?php if($is_admin): ?>
                                        <th class="text-center">
                                            <input class="flat-blue check-all" name="check_all[]" type="checkbox" value="yes">
                                        </th>
                                        <?php endif; ?>
                                        <th>#ID</th>
                                        <th>Người dùng</th>
                                        <th>Hoạt động</th>
                                        <th class="text-right">Giá trị</th>
                                        <th class="text-right">Hoa hồng</th>
                                        <th class="text-center">Thời gian</th>
                						<th class="text-center">Trạng thái</th>
                						<th>Ghi chú</th>
                                        <?php if($is_admin): ?>
                                        <th class="text-center">Chức năng</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // $this->config->set_item('role', array(
                                    //     //'MEMBER' => 'Thành viên',
                                    //     'AGENCY' => 'Đại lý',
                                    //     'ADMIN' => 'Hệ thống',
                                    // ));
                                    ?>
                                    <?php foreach ($rows as $row): ?>
                                        <?php $action = $row['action']; ?>
                                        <tr>
                                            <?php if($is_admin): ?>
                                            <td class="text-center">
                                                <input type="checkbox" class="check flat-blue" value="<?php echo $row['id']; ?>" name="idcheck[]">
                                            </td>
                                            <?php endif; ?>
                                            <td><?php echo $row['id']; ?></td>
                                            <td>
                                                <?php echo $row['full_name'] . ' (' . $row['username'] . ')'; ?>
                                                <?php echo display_label(display_value_array($this->config->item('role'), $row['role']), display_value_array($this->config->item('role_label'), $row['role'])); ?>
                                            </td>
                                            <td>
                                                <?php echo display_label(display_value_array($this->config->item('users_modules_commission'), $action), display_value_array($this->config->item('users_modules_commission_label'), $action)); ?>
                                                <?php if(in_array($action, array('SELL', 'BUY', 'SUB_BUY', 'SUB_BUY_ROOT'))): ?>
                                                    <p><?php echo $row['order_code']; ?><?php echo in_array($action, array('SUB_BUY', 'SUB_BUY_ROOT')) ? ' ' . display_label(formatRice($row['value_real']), 'default') : (in_array($action, array('BUY', 'SELL')) ? ' ' . display_label(formatRice($row['value_accumulated']), 'primary') : ''); ?></p>
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
                                                    echo display_label('Khả dụng');
                                                }elseif($row['status'] == 0){
                                                    echo display_label('Đã yêu cầu', 'warning');
                                                    // if(in_array($action, array('BUY'))){
                                                    //     echo ' <span class="label label-danger btn-status-cancel" data-id="' . $row['id'] . '" style="cursor: pointer;">Hủy</span>';
                                                    // }else
                                                    if($is_admin && in_array($action, array('PAY_IN', 'BUY', 'WITHDRAWAL', 'WITHDRAWAL_BONUS'))){
                                                        echo ' <span class="label label-primary btn-status-confirm" data-id="' . $row['id'] . '" style="cursor: pointer;">Xác nhận</span> <span class="label label-danger btn-status-cancel" data-id="' . $row['id'] . '" style="cursor: pointer;">Hủy</span>';
                                                    }
                                                }else{
                                                    echo display_label('Đã hủy yêu cầu', 'danger');
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row['message']; ?></td>
                                            <?php if($is_admin): ?>
                                            <td class="text-center">
                                                <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/delete?id=' . $row['id']); ?>" class="btn-delete-confirm">Xóa</a>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <?php if($is_admin): ?>
                                <tfoot>
                                    <tr class="text-left">
                                        <td colspan="3">
                                            <div class="input-group">
                                                <select class="form-control" name="action" id="action">
                                                    <option value="delete">Xóa</option>
                                                    <!-- <option value="content">Thêm</option> -->
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="submit">Thực hiện</button>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="callout callout-warning">
                        <h4>Thông báo!</h4>
                        <p><b>Không</b> có giao dịch nào!</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($pagination != ''): ?>
                <div class="box-footer clearfix">
                    <?php echo $pagination; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>