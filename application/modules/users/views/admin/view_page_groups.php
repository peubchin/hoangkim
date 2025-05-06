<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Nhóm thành viên</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($rows)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Tên nhóm</th>
                                    <th class="text-center">Hiệu lực đến</th>
                                    <th class="text-center">Thành viên</th>
                                    <th class="text-center">Hiệu lực</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($rows as $row) {
                                    $disabled = '';
                                    if ($row['group_id'] < 3) {
                                        $link = '#';
                                    } elseif ($row['group_id'] == 3) {
                                        $link = get_admin_url('users');
                                    } else {
                                        $link = get_admin_url('users/groups?userlist=' . $row['group_id']);
                                        $disabled = '" disabled=disabled"';
                                    }
                                    ?>
                                    <tr class="text-center">
                                        <td class="text-center">
                                            <?php echo $row['group_id']; ?>
                                        </td>
                                        <td class="text-left">
                                            <a title="<?php echo $row['title']; ?>" href="<?php echo $link; ?>"><?php echo $row['title']; ?></a>
                                        </td>
                                        <td>Không giới hạn</td>
                                        <td><?php echo $row['num_users']; ?></td>
                                        <td>
                                            <input name="a_6" type="checkbox" class="act flat-blue" value="<?php echo $row['group_id']; ?>" checked="checked"<?php echo $disabled; ?>>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <?php echo show_alert_warning('Chưa có nhóm nào!'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>