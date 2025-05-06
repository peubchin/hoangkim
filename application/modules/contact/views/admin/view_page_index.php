<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách liên hệ</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($rows)): ?>
                    <form class="form-inline" name="main" method="post" action="<?php echo get_admin_url($module_slug . '/' . 'main'); ?>">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <input class="flat-blue check-all" name="check_all[]" type="checkbox" value="yes">
                                        </th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th class="text-center">Thời gian</th>
                                        <th class="text-center">Trạng thái</th>                                
                                        <th class="text-center">Xem lúc</th>
                                        <th class="text-center">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($rows as $row) {
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="flat-blue check" value="<?php echo $row['id']; ?>" name="idcheck[]">
                                            </td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td class="text-center"><?php echo display_date($row['add_time']); ?></td>
                                            <td class="text-center">
                                                <?php
                                                if ($row['is_view'] == 0) {
                                                    echo display_label("Chưa xem", "danger");
                                                } else {
                                                    echo display_label("Đã xem", "success");
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center"><?php if ($row['view_time'] != 0) echo display_date($row['view_time']); ?></td>
                                            <td class="text-center">
                                                <em class="fa fa-eye fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/view?id=' . $row['id']); ?>">Xem</a>&nbsp;-&nbsp;
                                                <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/delete?id=' . $row['id']); ?>" class="confirm_bootstrap" title="Xóa">Xóa</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-left">
                                        <td colspan="3">
											<div class="input-group">
												<select class="form-control" name="action" id="action">
													<option value="delete">Xóa</option>
												</select>
												<span class="input-group-btn">
													<button class="btn btn-primary" type="submit">Thực hiện</button>
												</span>
											</div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="callout callout-warning">
                        <h4>Thông báo!</h4>
                        <p><b>Không</b> tìm thấy liên hệ nào!</p>
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