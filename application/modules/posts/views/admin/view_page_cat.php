<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                if (!isset($row['id'])) {
                    echo '<h3 class="box-title"><em class="fa fa-file-text-o">&nbsp;</em>Thêm chủ đề</h3>';
                    $action = get_admin_url('posts/cat/content');
                    if (isset($menu_parent)) {
                        $action .= '?parent=' . $menu_parent;
                    }
                } else {
                    echo '<h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cập nhật chủ đề</h3>';
                    $action = get_admin_url('posts/cat/content/' . $row['id'] . '?parent=' . $row['parent']);
                }
                ?>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo $action; ?>" method="post" autocomplete="off">
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
                        <label for="name" class="control-label">Tên chủ đề</label>
                        <?php $row_name = (set_value('name') != '') ? set_value('name') : (isset($row['name']) ? $row['name'] : ''); ?>
                        <input class="form-control" name="name" id="name" type="text" value="<?php echo $row_name; ?>">
                        <?php echo form_error('name'); ?>
                    </div>

                    <?php $has_error = (form_error('alias') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="alias" class="control-label">Liên kết tĩnh</label>
                        <input class="form-control" name="alias" type="text" id="alias" value="<?php if (isset($row['alias'])) echo $row['alias']; ?>" maxlength="255">
                        <?php echo form_error('alias'); ?>
                    </div>                    

                    <div class="form-group">
                        <label for="parent" class="control-label">Thuộc chủ đề</label>
                        <select class="form-control" name="parent" id="parent">
                            <option value="0">Là chủ đề chính</option>
                            <?php echo multilevelOptionParentid(0, $postcat_list, $postcat_input, 0, $id, $parent); ?>
                        </select>
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
                        <label for="hometext" class="control-label">Giới thiệu ngắn gọn</label>
                        <textarea id="hometext" name="hometext" data-autoresize rows="3" class="form-control"><?php if (isset($row['hometext'])) echo $row['hometext']; ?></textarea>
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