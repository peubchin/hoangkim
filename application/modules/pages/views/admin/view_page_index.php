<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form name="filter" method="get" action="<?php echo get_admin_url($module_slug); ?>">
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
                            Số bài viết hiển thị
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="per_page">
                                <?php
                                $records_in_page = 10;
                                for ($i = 1; $i <= 10; $i++) {
                                    $per_page = $i * $records_in_page;
                                    ?>
                                    <?php if (isset($get['per_page']) && $get['per_page'] == $per_page) : ?>
                                        <option selected="selected" value="<?php echo $per_page; ?>"><?php echo $per_page; ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $per_page; ?>"><?php echo $per_page; ?></option>
                                    <?php endif; ?>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group search_title">
                            Từ khóa tìm kiếm
                        </div>
                        <div class="form-group search_input">
                            <input class="form-control input-sm" type="text" value="<?php echo isset($get['q']) ? $get['q'] : ''; ?>" maxlength="64" name="q" placeholder="Từ khóa tìm kiếm">
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
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách trang tĩnh</h3>
                <a class="btn btn-success pull-right" href="<?php echo get_admin_url($module_slug . '/' . 'content'); ?>"><i class="fa fa-plus"></i> Thêm</a>
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
                                        <th class="text-center" style="width: 80px;">Sắp xếp</th>
                                        <th style="width:40px">&nbsp;</th>
                                        <th class="text-center">Tên bài viết</th>
                                        <th class="text-center">Thời gian đăng</th>
                                        <th class="text-center">Lượt xem</th>
                                        <th class="text-center" style="width: 160px;">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($rows as $row) {
                                        $link = site_url($row['alias'] . '-' . $row['id']);
                                        $src_img = get_image(get_module_path('posts') . (isset($row['homeimgfile']) ? $row['homeimgfile'] : ''), get_module_path('posts') . 'no-image.png');
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="flat-blue check" value="<?php echo $row['id']; ?>" name="idcheck[]">
                                            </td>
                                            <td class="text-center">
                                                <input style="width: 80px;" class="text-right form-control" name="order[]" type="text" value="<?php echo $row['order']; ?>">
                                                <input class="text-right form-control" name="ids[]" type="hidden" value="<?php echo $row['id']; ?>">
                                            </td>
                                            <td>
                                                <a class="img-fancybox" href="<?php echo $src_img; ?>" title="<?php echo $row['title']; ?>"><img width="40" class="img-rounded img-responsive" alt="<?php echo $row['homeimgalt']; ?>" src="<?php echo $src_img; ?>"></a>
                                            </td>
                                            <td>
                                                <p><a target="_blank" href="<?php echo $link; ?>"><?php echo $row['title']; ?></a></p>
                                                <div class="product-info">
                                                    Cập nhật: <span class="other"><?php echo display_date($row['edittime']); ?></span> |
                                                    Người tạo: <span class="other"><?php echo $row['full_name']; ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><?php echo display_date($row['addtime']); ?></td>
                                            <td class="text-center"><p class='text-bold text-primary'><p class='text-bold text-primary'><?php echo formatRice($row['hitstotal']); ?></p></td>
                                            <td class="text-center">
                                                <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/content/' . $row['id']); ?>">Sửa</a>
                                                &nbsp;-&nbsp;
                                                <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/delete?id=' . $row['id']); ?>" class="delete_bootbox">Xóa</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-left">
                                        <td colspan="4">
											<div class="input-group">
												<select class="form-control" name="action" id="action">
													<option value="update">Cập nhật</option>
													<option value="delete">Xóa</option>
													<option value="content">Đăng bài viết</option>
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
                        <p><b>Không</b> có bài viết nào!</p>
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
