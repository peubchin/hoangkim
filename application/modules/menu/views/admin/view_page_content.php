<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                if (!isset($row['id'])) {
                    echo '<h3 class="box-title"><em class="fa fa-file-text-o">&nbsp;</em>Thêm menu</h3>';
                    $action = get_admin_url('menu/content');
                    if (isset($menu_parent)) {
                        $action .= '?parent=' . $menu_parent;
                    }
                } else {
                    echo '<h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cập nhật menu</h3>';
                    $action = get_admin_url('menu/content?id=' . $row['id'] . '&parent=' . $row['parent']);
                }
                ?>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php
                    $parent = 0;
                    $id = 0;
                    if (isset($row['id'])) {
                        $parent = $row['parent'];
                        $id = $row['id'];
                        echo "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"{$row['id']}\" />";
                    } elseif (isset($row_parent)) {
                        $parent = $row_parent;
                    }
                    ?>

                    <?php $has_error = (form_error('name') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="name" class="control-label">Tên menu</label>
                        <?php $row_name = (set_value('name') != '') ? set_value('name') : (isset($row['name']) ? $row['name'] : ''); ?>
                        <input class="form-control" name="name" id="name" type="text" value="<?php echo $row_name; ?>">
                        <?php echo form_error('name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="position" class="control-label">Vị trí</label>
                        <select class="form-control" name="position" id="position">
							<?php echo get_option_select($this->config->item('menu_modules'), isset($row['position']) ? $row['position'] : ''); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parent" class="control-label">Thuộc menu</label>
                        <select class="form-control" name="parent" id="parent">
                            <option value="0">Là menu chính</option>
                            <?php
                            if (isset($row["position"]) && $row["position"] == "Top") {
                                echo multilevelOptionParentid(0, $menu_top_list, $menu_top_input, 0, $id, $parent);
                            } elseif (isset($row["position"]) && $row["position"] == "Bottom") {
                                echo multilevelOptionParentid(0, $menu_bottom_list, $menu_bottom_input, 0, $id, $parent);
                            } elseif (isset($row["position"]) && $row["position"] == "Left") {
                                echo multilevelOptionParentid(0, $menu_left_list, $menu_left_input, 0, $id, $parent);
                            } elseif (isset($row["position"]) && $row["position"] == "Right") {
                                echo multilevelOptionParentid(0, $menu_right_list, $menu_right_input, 0, $id, $parent);
                            } else {
                                echo multilevelOptionParentid(0, $menu_main_list, $menu_main_input, 0, $id, $parent);
                            }
                            ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="menu_type" class="control-label">Loại menu</label>
                        <select class="form-control" name="menu_type" id="menu_type">
                            <?php echo get_menutype($menu_type_rows, isset($row['menu_type']) ? $row['menu_type'] : 0); ?>
                        </select>
                    </div>

                    <div class="form-group" id="content_menu_type">
                        <?php
                        if (isset($row['id'])) {
                            $this->load->view('view_menu_type');
                        }
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="image" class="control-label">Chọn ảnh</label>
                        <input id="image" name="image[]" class="file" type="file">
						<?php if (isset($row['image']) && trim($row['image']) != ''): ?>
						<div style="margin-top: 10px;">
							<img src="<?php echo get_image(get_module_path('menu') . $row['image'], get_module_path('menu') . 'no-image.png'); ?>" alt="" class="img-thumbnail">
						</div>
						<?php endif;?>
                    </div>

                    <div class="text-center">
                        <?php if (isset($row['id'])): ?>
                            <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                        <?php else: ?>
                            <button class="btn btn-success" type="submit">Thêm</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div><!-- /.box -->
    </div>
</div>
