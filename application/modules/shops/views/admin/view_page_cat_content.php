<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                if (!isset($row['id'])) {
                    echo '<h3 class="box-title"><em class="fa fa-file-text-o">&nbsp;</em>Thêm danh mục sản phẩm</h3>';
                    $action = get_admin_url($module_slug . '/content');
                } else {
                    echo '<h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cập nhật danh mục sản phẩm</h3>';
                    $action = get_admin_url($module_slug . '/content/' . $row['id']);
                }
                ?>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo $action; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="parent" class="control-label">Thuộc danh mục sản phẩm</label>
                        <select class="form-control" name="parent" id="parent">
                            <option value="0">Là danh mục sản phẩm chính</option>
                            <?php echo multilevel_option_parent(0, $data_list, $data_input, 0, $id, $parent); ?>
                        </select>
                    </div> 

                    <?php $has_error = (form_error('name') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="name" class="control-label">Tên danh mục sản phẩm</label>
                        <?php $row_name = (set_value('name') != '') ? set_value('name') : (isset($row['name']) ? $row['name'] : ''); ?>
                        <input class="form-control" name="name" id="name" type="text" value="<?php echo $row_name; ?>">
                        <?php echo form_error('name'); ?>
                    </div>

                    <?php $has_error = (form_error('alias') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="alias" class="control-label">Liên kết tĩnh</label>
                        <?php $row_alias = (set_value('alias') != '') ? set_value('alias') : (isset($row['alias']) ? $row['alias'] : ''); ?>
                        <input class="form-control" name="alias" id="alias" type="text" value="<?php echo $row_alias; ?>">
                        <?php echo form_error('alias'); ?>
                    </div>

                    <div class="form-group">
                        <label for="slogun" class="control-label">Slogun</label>
                        <input class="form-control" name="slogun" id="slogun" type="text" value="<?php echo (isset($row['slogun']) ? $row['slogun'] : ''); ?>">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label for="inhome">
                                <?php $checked = ((isset($row['inhome']) && ($row['inhome'] == 1)) ? ' checked' : ''); ?>
                                <input class="flat-blue" name="inhome" id="inhome" type="checkbox" value="1"<?php echo $checked; ?>> <strong>Hiển thị trang chủ</strong>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image" class="control-label">Chọn ảnh</label>
                        <input id="image" name="image[]" class="file" type="file">
                        <?php if(isset($row['image']) && trim($row['image']) != ''): ?>
                        <div style="margin-top: 10px;">
                            <img width="100" src="<?php echo get_image(get_module_path('shops_cat') . $row['image'], get_module_path('shops_cat') . 'no-image.png'); ?>" alt="" class="img-thumbnail img-responsive">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="title_seo" class="control-label">Title (SEO)</label>
                        <input class="form-control" name="title_seo" type="text" id="title_seo" value="<?php if (isset($row['title_seo'])) echo $row['title_seo']; ?>" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label for="keywords" class="control-label">Keywords (SEO)</label>
                        <textarea class="form-control" data-autoresize name="keywords" id="keywords"><?php if (isset($row['keywords'])) echo $row['keywords']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label">Description (SEO)</label>
                        <textarea class="form-control" data-autoresize name="description" id="description"><?php if (isset($row['description'])) echo $row['description']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="other_seo" class="control-label">Other (SEO)</label>
                        <textarea class="form-control" data-autoresize name="other_seo" id="other_seo"><?php if (isset($row['other_seo'])) echo $row['other_seo']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="h1_seo" class="control-label">H1 (SEO)</label>
                        <input class="form-control" name="h1_seo" type="text" id="h1_seo" value="<?php if (isset($row['h1_seo'])) echo $row['h1_seo']; ?>" maxlength="255">
                    </div>

                    <div class="text-center">
                        <?php
                        if (isset($row['id'])) {
                            echo '<button class="btn btn-primary" type="submit">Lưu thay đổi</button>';
                        } else {
                            echo '<button class="btn btn-primary" type="submit">Thêm</button>';
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div><!-- /.box -->
    </div>
</div>