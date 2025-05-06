<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-money"></i> Thưởng thêm doanh số trong tháng</h3>
            </div>
            <div class="box-body">
                <form role="form" action="<?php echo current_url(); ?>" method="GET" autocomplete="off">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Tháng</label>
                                <div class="input-group input-append date" id="datePickerMonth">
                                    <input type="text" class="form-control" name="month" value="<?php echo (isset($get['month']) && trim($get['month']) != '') ? $get['month'] : date('m-Y'); ?>" readonly="true" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <br/>
                            <div class="form-group" style="margin-top: 5px;">
                                <label class="control-label">&nbsp;</label>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-hand-o-right"></i> Xem</button>
                                &nbsp;<a class="btn btn-danger" href="<?php echo get_admin_url($module_slug); ?>">Thoát</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if(isset($rows) && is_array($rows) && !empty($rows)): ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Danh sách thành viên được trả thưởng tháng "<strong style="color: #f00;"><?php echo $get['month'] ?></strong>"</h3>
                <div class="pull-right">
                    <a class="btn btn-primary" href="<?php echo get_admin_url('users/reward/export') . ((isset($get) && !empty($get)) ? '?' . http_build_query($get, '', "&") : ''); ?>"><i class="fa fa-cloud-download"></i> Xuất dữ liệu</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th class="text-right">#ID</th>
                                <th>Tài khoản</th>
                                <th>Họ tên</th>
                                <th class="text-center">Điện thoại</th>
                                <th class="text-right">Tổng doanh thu trực tiếp</th>
                                <th class="text-right">Thưởng doanh thu trực tiếp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; foreach ($rows as $row): $i++; ?>
                            <tr>
                                <td class="text-right"><?php echo $i; ?></td>
                                <td class="text-right"><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['full_name']; ?></td>
                                <td class="text-right"><a href="sms:<?php echo $row['phone']; ?>"><?php echo $row['phone']; ?></a></td>
                                <td class="text-right"><?php echo formatRice($row['value_cost']); ?></td>
                                <td class="text-right"><?php echo formatRice($row['value']); ?></td>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>