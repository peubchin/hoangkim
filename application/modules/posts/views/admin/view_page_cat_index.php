<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách chủ đề</h3>
                <a class="btn btn-success pull-right" href="<?php echo get_admin_url($module_slug . '/' . 'content'); ?>"><i class="fa fa-plus"></i> Thêm</a>
            </div>
            <div class="box-body">
                <?php if (!empty($rows)): ?>
                <form class="form-inline" name="main" method="post" action="<?php echo get_admin_url($module_slug . '/' . 'main'); ?>" autocomplete="off">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Sắp xếp</th>
                                        <th>Tên chủ đề</th>
                                        <th class="text-center">Trang chủ</th>
                                        <th class="text-center">Ngày cập nhật</th>
                                        <th class="text-center" style="width: 160px;">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo display_table_cat_post(0, $menu_list, $menu_input, 0); ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-left">
                                        <td colspan="3">
											<div class="input-group">
												<select class="form-control" name="action" id="action">
													<option value="update">Cập nhật</option>
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
                        <p><b>Không</b> tìm thấy chủ đề nào!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>