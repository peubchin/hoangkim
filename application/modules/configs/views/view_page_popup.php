<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cấu hình thông báo</h3>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo current_url(); ?>" method="post" autocomplete="off">
                    <?php $has_error = (form_error('popup_title') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="popup_title" class="control-label">Tiêu đề thông báo</label>
                        <?php $configs_popup_title = (set_value('popup_title') != '') ? set_value('popup_title') : $configs['popup_title']; ?>
                        <input class="form-control" name="popup_title" id="popup_title" type="text" value="<?php echo $configs_popup_title; ?>">
                        <?php echo form_error('popup_title'); ?>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Nội dung thông báo</label>
                        <?php
                        $popup_content = isset($configs['popup_content']) ? $configs['popup_content'] : '';
                        $config_mini = array();
                        $config_mini['language'] = 'vi';
                        $config_mini['filebrowserBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
                        $config_mini['filebrowserUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
                        echo $this->ckeditor->editor("popup_content", $popup_content, $config_mini);
                        ?>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label for="popup_status">
                                <?php $checked = ((isset($configs['popup_status']) && ($configs['popup_status'] == 1)) ? ' checked' : ''); ?>
                                <input class="flat-blue" name="popup_status" id="popup_status" type="checkbox" value="1"<?php echo $checked; ?>> <strong>&nbsp;Hiển thị thông báo trang chủ</strong>
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>