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
        <form name="filter" method="get" action="<?php echo get_admin_url('users/inactive'); ?>">
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
                            Số tài khoản hiển thị
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="per_page">
                                <?php echo get_option_per_page(isset($get['per_page']) ? (int) $get['per_page'] : $this->config->item('item', 'admin_list')); ?>
                            </select>
                        </div>

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
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách thành viên không mua hàng</h3>
                <div class="pull-right">
                    <a class="btn btn-danger btn-confirm-lock-all" href="<?php echo get_admin_url('users' . '/' . 'inactive-all') . '?token=' . random_string('alnum', 16); ?>"><i class="fa fa-lock"></i> Khóa tất cả</a>
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
                                        <th>Điện thoại</th>
                                        <th class="text-center">Ngày đăng ký</th>
                                        <th class="text-center">Lần mua hàng gần nhất</th>
                                        <th class="text-center">Lần đăng nhập cuối</th>
                                        <th class="text-center">Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <?php
                                            $is_agency = ($row['role'] == 'AGENCY') ? TRUE : FALSE;
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
                                                <a class="btn btn-danger btn-xs" href="<?php echo get_admin_url('users/ref/setting/' . $row['userid']); ?>"><i class="fa fa-exchange"></i> Đổi</a>
                                                <?php echo display_label($row['parent_full_name']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><a href="sms:<?php echo $row['phone']; ?>"><?php echo $row['phone']; ?></a></td>
                                            <td class="text-center"><?php echo display_date($row['regdate']); ?></td>
                                            <td class="text-center"><?php echo ($row['last_order_date'] == $row['regdate']) ? display_label('Chưa mua', 'danger') : ($row['last_order_date'] > 0 ? display_date($row['last_order_date']) : 'None'); ?></td>
                                            <td class="text-center"><?php echo $row['last_login'] > 0 ? display_date($row['last_login']) : 'None'; ?></td>
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