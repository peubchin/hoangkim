<?php
if(!isset($role)){
    $role = '';
}
$is_role_full_access = in_array($role, array('ADMIN', 'ACCOUNTANT')) ? TRUE : FALSE;
$is_admin = in_array($role, array('ADMIN')) ? TRUE : FALSE;
$users_qr_path = get_module_path('users_qr');
$cf_role = $this->config->item('role');
$cf_role_label = $this->config->item('role_label');
?>
<style type="text/css">
	.mr-20{
		margin-right: 20px;
	}
	.mt-15{
		margin-top: 15px;
	}
	.clearfix{
		clear: both;
	}
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form name="filter" method="get" action="<?php echo get_admin_url('users'); ?>">
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
                            Tìm thành viên theo
                        </div>
                        <div class="form-group mr-20">
                            <select class="form-control input-sm" name="method">
                                <option value="">---</option>
                                <?php if (isset($get['method']) && $get['method'] == 'userid') : ?>
                                    <option selected="selected" value="userid">ID thành viên</option>
                                <?php else : ?>
                                    <option value="userid">ID thành viên</option>
                                <?php endif; ?>

                                <?php if (isset($get['method']) && $get['method'] == 'username') : ?>
                                    <option selected="selected" value="username">Tài khoản thành viên</option>
                                <?php else : ?>
                                    <option value="username">Tài khoản thành viên</option>
                                <?php endif; ?>

                                <?php if (isset($get['method']) && $get['method'] == 'full_name') : ?>
                                    <option selected="selected" value="full_name">Tên thành viên</option>
                                <?php else : ?>
                                    <option value="full_name">Tên thành viên</option>
                                <?php endif; ?>

                                <?php if (isset($get['method']) && $get['method'] == 'email') : ?>
                                    <option selected="selected" value="email">Email thành viên</option>
                                <?php else : ?>
                                    <option value="email">Email thành viên</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group search_title">
                            Trạng thái
                        </div>
                        <div class="form-group mr-20">
                            <select class="form-control input-sm" name="usactive">
                                <?php if (isset($get['usactive']) && $get['usactive'] == 1) : ?>
                                    <option selected="selected" value="1">Tài khoản hoạt động</option>
                                <?php else : ?>
                                    <option value="1">Tài khoản hoạt động</option>
                                <?php endif; ?>

                                <?php if (isset($get['usactive']) && $get['usactive'] == 0) : ?>
                                    <option selected="selected" value="0">Tài khoản bị khóa</option>
                                <?php else : ?>
                                    <option value="0">Tài khoản bị khóa</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group search_title">
                            Số tài khoản hiển thị
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="per_page">
                                <?php echo get_option_per_page(isset($get['per_page']) ? (int) $get['per_page'] : $this->config->item('item', 'admin_list')); ?>
                            </select>
                        </div>
                        <div class="clearfix" style="margin-bottom: 10px;"></div>

                        <div class="form-group search_title">
                            Từ khóa tìm kiếm
                        </div>
                        <div class="form-group search_input">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" name="q" value="<?php echo isset($get['q']) ? $get['q'] : ''; ?>"  placeholder="Từ khóa tìm kiếm">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
                                    <a class="btn btn-danger btn-sm" href="<?php echo get_admin_url('users'); ?>">Xóa điều kiện lọc</a>
                                </span>
                            </div>
                        </div>
                        <br>
                        <label><em>Từ khóa tìm kiếm không ít hơn 3 ký tự, không lớn hơn 64 ký tự, không dùng các mã html</em></label>
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
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách thành viên</h3>
                <div class="pull-right">
                    <?php if($is_role_full_access): ?>
                    <a class="btn btn-warning" href="<?php echo get_admin_url('users' . '/' . 'reward') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>"><i class="fa fa-money"></i> Dữ liệu trả thưởng</a>
                    <a class="btn btn-danger" target="_blank" href="<?php echo get_admin_url('users' . '/' . 'inactive'); ?>"><i class="fa fa-lock"></i> Khóa thành viên không mua hàng</a>
                    <a class="btn btn-info" href="<?php echo get_admin_url('users' . '/' . 'export') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>"><i class="fa fa-cloud-download"></i> Xuất dữ liệu</a>
                    <a class="btn btn-primary" href="<?php echo get_admin_url('users' . '/' . 'export-commission') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>"><i class="fa fa-cloud-download"></i> Xuất dữ liệu hoa hồng</a>
                    <?php endif; ?>
                    <a class="btn btn-success" href="<?php echo get_admin_url('users' . '/' . 'content'); ?>"><i class="fa fa-plus"></i> Thêm</a>
                </div>
            </div>
            <div class="box-body">
                <?php if (empty($rows)): ?>
                    <div class="callout callout-warning">
                        <h4>Thông báo!</h4>
                        <p><b>Không</b> tìm thấy tài khoản nào!</p>
                    </div>
                <?php else: ?>
                    <form class="form-inline" name="block_list" action="#">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Tài khoản</th>
                                        <th>Họ tên</th>
                                        <th class="text-center" style="width: 88px;">QR</th>
                                        <!-- <th>Email</th> -->
                                        <th>Điện thoại</th>
                                        <?php if($is_role_full_access): ?>
                                        <th class="text-right">Cổ phần nội bộ</th>
                                        <th class="text-right">Cổ phần chính thức</th>
                                        <th class="text-right">Điểm HK tri ân</th>
                                        <?php endif; ?>
                                        <th class="text-center">Quyền</th>
                                        <th class="text-center">Ngày đăng ký</th>
                                        <th class="text-center">Hoạt động</th>
                                        <th class="text-center">Tài khoản sỉ</th>
                                        <th class="text-center" style="width: 100px;">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <?php 
                                            $is_agency = ($row['role'] == 'AGENCY') ? TRUE : FALSE;
                                            $qr_code = $users_qr_path . $row['username'] . '.png';
                                        ?>
                                        <tr>
                                            <td class="text-right">
                                                <?php echo $row['userid']; ?>
                                                <?php echo ($is_agency && $row['referred_by'] > 0) ? display_label($row['referred_by']) : ''; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['username']; ?>
                                                <?php echo $is_agency ? display_label($row['parent_username']) : ''; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['full_name']; ?>
                                                <?php if($is_agency): ?>
                                                    <?php if($is_role_full_access): ?>
                                                    <a class="btn btn-danger btn-xs" href="<?php echo get_admin_url('users/ref/setting/' . $row['userid']); ?>"><i class="fa fa-exchange"></i> Đổi</a>
                                                    <?php endif; ?>
                                                    <?php echo display_label($row['parent_full_name']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(validate_file_exists($qr_code)): ?>
                                                <a href="<?php echo get_admin_url('users/qr/download') . '?file=' . rawurlencode(base_url($qr_code)); ?>"><img src="<?php echo base_url($qr_code); ?>" alt="" class="img-thumbnail"></a>
                                                <?php elseif($is_role_full_access): ?>
                                                <button type="button" class="btn btn-xs btn-success btn-block btn-generate-qr-code" style="margin-top: 5px;" data-id="<?php echo $row['userid']; ?>"><i class="fa fa-plus"></i> QR</button>
                                                <?php endif; ?>
                                            </td>
                                            <!-- <td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td> -->
                                            <td><a href="sms:<?php echo $row['phone']; ?>"><?php echo $row['phone']; ?></a></td>
                                            <?php if($is_role_full_access): ?>
                                            <td class="text-right">
                                                <?php echo formatRice($row['stock']); ?>
                                                <a class="btn btn-danger btn-xs" href="<?php echo get_admin_url('users/stock/setting/' . $row['userid']); ?>"><i class="fa fa-exchange"></i> Cài đặt</a>
                                            </td>
                                            <td class="text-right">
                                                <?php echo formatRice($row['stock_official']); ?>
                                                <a class="btn btn-danger btn-xs" href="<?php echo get_admin_url('users/stock_official/setting/' . $row['userid']); ?>"><i class="fa fa-exchange"></i> Cài đặt</a>
                                            </td>
                                            <td class="text-right">
                                                <?php echo formatRice($row['dividend']); ?>
                                                <a class="btn btn-danger btn-xs" href="<?php echo get_admin_url('users/dividend/setting/' . $row['userid']); ?>"><i class="fa fa-exchange"></i> Cài đặt</a>
                                            </td>
                                            <?php endif; ?>
                                            <td class="text-center">
                                                <?php echo display_label(display_value_array($cf_role, $row['role']), display_value_array($cf_role_label, $row['role'])); ?>
                                            </td>
                                            <td class="text-center"><?php echo display_date($row['regdate']); ?></td>
                                            <td class="text-center">
                                                <?php
                                                $checked = '';
                                                $disabled = '';
                                                if ($row['active'] == 1) {
                                                    $checked = ' checked="checked"';
                                                }
                                                if ($row['role'] == 'ADMIN') {
                                                    $disabled = ' disabled="disabled"';
                                                }
                                                echo '<input type="checkbox" name="active" class="change_status flat-blue" id="change_status_' . $row['userid'] . '" value="' . $row['userid'] . '"' . $checked . $disabled . '>';
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="is_wholesale[]" class="change-is-wholesale flat-blue" value="<?php echo $row['userid']; ?>"<?php if ($row['is_wholesale'] == 1) echo ' checked="checked"'; ?> />
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Chức năng <span class="caret"></span></button>
                                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                        <li><a href="<?php echo get_admin_url('users/content/' . $row['userid']); ?>"><i class="fa fa-edit"></i> Sửa</a></li>
                                                        <li><a href="<?php echo get_admin_url('users/delete?id=' . $row['userid']); ?>" class="confirm_bootstrap"><i class="fa fa-trash"></i> Xóa</a></li>
                                                        <?php if($is_role_full_access): ?>
                                                        <li><a href="<?php echo get_admin_url('login-by/' . $row['userid']); ?>" target="_blank"><i class="fa fa-lock"></i> Đăng nhập</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
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