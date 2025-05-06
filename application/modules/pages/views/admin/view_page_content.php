<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Thông tin bài viết</h3>
            </div>
            <div class="box-body">
                <?php
                $action_id = '';
                $input_id = '';
                if (isset($row['id'])) {
                    $action_id = '/' . $row['id'];
                    $input_id = '<input type="hidden" value="' . $row['id'] . '" id="id" name="id" class="form-control" />';
                }
                ?>
                <form id="f-content" action="<?php echo get_admin_url($module_slug . '/' . 'content' . $action_id); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php echo $input_id; ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">                           
                            <?php $has_error = (form_error('title') != '' ? ' has-error' : ''); ?>
                            <div class="form-group required<?php echo $has_error; ?>">
                                <label for="title" class="control-label">Tiêu đề</label>
                                <input type="text" maxlength="255" value="<?php if (isset($row['title'])) echo $row['title']; ?>" name="title" id="title" class="form-control">
                                <?php echo form_error('title'); ?>
                            </div>

                            <?php $has_error = (form_error('alias') != '' ? ' has-error' : ''); ?>
                            <div class="form-group required<?php echo $has_error; ?>">
                                <label for="alias" class="control-label">Liên kết tĩnh</label>
                                <input class="form-control" name="alias" type="text" id="alias" value="<?php if (isset($row['alias'])) echo $row['alias']; ?>" maxlength="255">
                                <?php echo form_error('alias'); ?>
                            </div>

                            <div class="form-group">
                                <label for="homeimg" class="control-label">Hình minh họa cho phần giới thiệu</label>
                                <input id="homeimg" name="homeimg[]" class="file" type="file">
								<?php if(isset($row['homeimgfile']) && trim($row['homeimgfile'])!= ''): ?>
								<div style="margin-top: 10px;">
									<img width="100" src="<?php echo get_image(get_module_path('posts') . (isset($row['homeimgfile']) ? $row['homeimgfile'] : ''), get_module_path('posts') . 'no-image.png'); ?>" alt="" class="img-thumbnail img-responsive">
								</div>
								<?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="homeimgalt" class="control-label">Chú thích cho hình minh họa (phần chi tiết bài viết)</label>
                                <input class="form-control" type="text" maxlength="255" value="<?php if (isset($row['homeimgalt'])) echo $row['homeimgalt']; ?>" name="homeimgalt">
                            </div>                            

                            <div class="form-group">
                                <label for="hometext" class="control-label">Giới thiệu ngắn gọn</label>
                                <textarea id="hometext" name="hometext" data-autoresize rows="3" class="form-control"><?php if (isset($row['hometext'])) echo $row['hometext']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="bodyhtml" class="control-label">Nội dung chi tiết</label>
                                <?php
                                $bodyhtml = isset($row['bodyhtml']) ? $row['bodyhtml'] : '';
                                $config_mini = array();
                                $config_mini['language'] = 'vi';
                                $config_mini['filebrowserBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
                                $config_mini['filebrowserImageBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images&dir=images/news';
                                $config_mini['filebrowserFlashBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
                                $config_mini['filebrowserUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
                                $config_mini['filebrowserImageUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images&dir=images/news';
                                $config_mini['filebrowserFlashUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
                                echo $this->ckeditor->editor("bodyhtml", $bodyhtml, $config_mini);
                                ?>
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
                        </div>
                    </div>        

                    <div class="row">
                        <div class="text-center">
                            <?php if (isset($row['id'])) : ?>
                                <input class="btn btn-primary" type="submit" value="Lưu thay đổi">
                            <?php else : ?>
                                <input class="btn btn-success" type="submit" value="Đăng trang tĩnh">
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>