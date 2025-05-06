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
                            Loại
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="post_type">
                                <option value="">Tất cả</option>
                                <?php echo get_option_select($this->config->item('info_modules'), isset($get['post_type']) ? $get['post_type'] : ''); ?>
                            </select>
                        </div>

                        <div class="form-group search_action pull-right">                                
                            <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
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
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách thông tin</h3>
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
                                        <th>Tiêu đề</th>
                                        <th>Liên kết</th>
                                        <th class="text-center">Loại</th>                                        
                                        <th class="text-center">Vị trí</th>                                        
                                        <th class="text-center">Thời gian đăng</th>
                                        <th class="text-center" style="width: 160px;">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input style="width: 80px;" class="text-right form-control" name="order[]" type="text" value="<?php echo $row['order']; ?>">
                                                <input class="text-right form-control" name="ids[]" type="hidden" value="<?php echo $row['id']; ?>">
                                            </td>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php echo $row['link']; ?></td>
                                            <td class="text-center"><span class="label label-info"><?php echo display_value_array($this->config->item('info_modules'), $row['post_type']); ?></span></td>
                                            <td class="text-center"><?php echo display_label($row['position'], 'success'); ?></td>
                                            <td class="text-center"><?php echo display_date($row['add_time']); ?></td>
                                            <td class="text-center">
                                                <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/content/' . $row['id']); ?>">Sửa</a>
                                                &nbsp;-&nbsp;
                                                <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/delete?id=' . $row['id']); ?>" class="delete_bootbox">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-left">
                                        <td colspan="3">
											<div class="input-group">
												<select class="form-control" name="action" id="action">
													<option value="update">Cập nhật</option>
													<!--
													<option value="delete">Xóa</option>
													<option value="content">Đăng bài viết</option>
													-->
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
                        <p><b>Không</b> có thông tin nào!</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (isset($pagination) && trim($pagination) != ''): ?>
                <div class="box-footer clearfix">
                    <?php echo $pagination; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>