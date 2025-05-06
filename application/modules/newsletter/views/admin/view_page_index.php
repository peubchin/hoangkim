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
                            Số dòng hiển thị
                        </div>
                        <div class="form-group search_input">
                            <select class="form-control input-sm" name="per_page">
                                <?php
                                $records_in_page = 5;
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
                            <input class="form-control input-sm" type="text" value="<?php if (isset($get['q'])) echo $get['q']; ?>" maxlength="64" name="q" placeholder="Từ khóa tìm kiếm">
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
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Danh sách email đăng ký nhận thông báo</h3>
            </div>
            <div class="box-body">
                <?php if (!empty($rows)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Email</th>
                                    <th class="text-center">Ngày đăng ký</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($rows as $row) {
                                    ?>
                                    <tr>
                                        <td class="text-right">
                                            <?php echo $row['id']; ?>
                                            <input class="text-right form-control" name="ids[]" type="hidden" value="<?php echo $row['id']; ?>">
                                        </td>
                                        <td><?php echo $row['email_address']; ?></td>
                                        <td class="text-center"><?php echo display_date($row['add_time']); ?></td>
                                        <td class="text-center">
                                            <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="<?php echo get_admin_url($module_slug . '/delete?id=' . $row['id']); ?>" class="delete_bootbox">Xóa</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="callout callout-warning">
                        <h4>Thông báo!</h4>
                        <p><b>Không</b> có thông tin nào!</p>
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