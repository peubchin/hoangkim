<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Thông tin</h3>
            </div>
            <div class="box-body">
                <form id="f-content" action="<?php echo get_admin_url($module_slug . '/content' . (isset($row['id']) ? ('/' . $row['id']) : '')); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php
                    if (isset($row['id'])) {
                        echo '<input type="hidden" value="' . $row['id'] . '" id="id" name="id" class="form-control" />';
                    }
                    ?>
                    <div class="form-group required">
                        <label for="title" class="control-label">Tiêu đề</label>
                        <?php $title = (isset($row['title']) ? $row['title'] : ''); ?>
                        <input class="form-control" name="title" id="title" type="text" value="<?php echo $title; ?>">
                        <?php echo form_error('title'); ?>
                    </div>

                    <div class="form-group required">
                        <label for="module" class="control-label">Loại</label>
                        <select class="form-control" name="module">
							<?php echo get_option_select($this->config->item('info_modules'), isset($row['post_type']) ? $row['post_type'] : ''); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="position" class="control-label">Vị trí</label>
                        <select class="form-control" name="position" id="position">
                            <option value="none">None</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="content" class="control-label">Nội dung</label>
                        <?php
                        $content = isset($row['content']) ? $row['content'] : '';
                        $config_mini = array();
                        $config_mini['language'] = 'vi';
                        $config_mini['filebrowserBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
                        $config_mini['filebrowserUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
                        echo $this->ckeditor->editor("content", $content, $config_mini);
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="link" class="control-label">Liên kết</label>
                        <input class="form-control" name="link" id="link" type="text" value="<?php echo isset($row['link']) ? $row['link'] : ''; ?>">
                    </div>

                    <div class="text-center">
                        <?php if (isset($row['id'])) : ?>
                            <input class="btn btn-primary" type="submit" value="Lưu thay đổi">
                        <?php else : ?>
                            <input class="btn btn-success" type="submit" value="Thêm thông tin">
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>