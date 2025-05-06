<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cấu hình chung</h3>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo get_admin_url('settings'); ?>" method="post" autocomplete="off">
                    <?php $has_error = (form_error('site_email') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="site_email" class="control-label">Email của site</label>
                        <?php $configs_site_email = (set_value('site_email') != '') ? set_value('site_email') : $configs['site_email']; ?>
                        <input class="form-control" name="site_email" id="site_email" type="text" value="<?php echo $configs_site_email; ?>">
                        <?php echo form_error('site_email'); ?>
                    </div>
                    
                    <?php $has_error = (form_error('facebook_fanpage') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="facebook_fanpage" class="control-label">Facebook</label>
                        <?php $configs_facebook_fanpage = (set_value('facebook_fanpage') != '') ? set_value('facebook_fanpage') : $configs['facebook_fanpage']; ?>
                        <input class="form-control" name="facebook_fanpage" id="facebook_fanpage" type="text" value="<?php echo $configs_facebook_fanpage; ?>">
                        <?php echo form_error('facebook_fanpage'); ?>
                    </div>
                    
                    <?php $has_error = (form_error('google_plus') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="google_plus" class="control-label">G+</label>
                        <?php $configs_google_plus = (set_value('google_plus') != '') ? set_value('google_plus') : $configs['google_plus']; ?>
                        <input class="form-control" name="google_plus" id="google_plus" type="text" value="<?php echo $configs_google_plus; ?>">
                        <?php echo form_error('google_plus'); ?>
                    </div>
                    
                    <?php $has_error = (form_error('skype_page') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="skype_page" class="control-label">Skype</label>
                        <?php $configs_skype_page = (set_value('skype_page') != '') ? set_value('skype_page') : $configs['skype_page']; ?>
                        <input class="form-control" name="skype_page" id="skype_page" type="text" value="<?php echo $configs_skype_page; ?>">
                        <?php echo form_error('skype_page'); ?>
                    </div>
                    
                    <?php $has_error = (form_error('twitter_page') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="twitter_page" class="control-label">Twitter</label>
                        <?php $configs_twitter_page = (set_value('twitter_page') != '') ? set_value('twitter_page') : $configs['twitter_page']; ?>
                        <input class="form-control" name="twitter_page" id="twitter_page" type="text" value="<?php echo $configs_twitter_page; ?>">
                        <?php echo form_error('twitter_page'); ?>
                    </div>
                    
                    <?php $has_error = (form_error('linkedin_page') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="linkedin_page" class="control-label">Linked In</label>
                        <?php $configs_linkedin_page = (set_value('linkedin_page') != '') ? set_value('linkedin_page') : $configs['linkedin_page']; ?>
                        <input class="form-control" name="linkedin_page" id="linkedin_page" type="text" value="<?php echo $configs_linkedin_page; ?>">
                        <?php echo form_error('linkedin_page'); ?>
                    </div>
                    
                    <?php $has_error = (form_error('youtube_page') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="youtube_page" class="control-label">Youtube</label>
                        <?php $configs_youtube_page = (set_value('youtube_page') != '') ? set_value('youtube_page') : $configs['youtube_page']; ?>
                        <input class="form-control" name="youtube_page" id="youtube_page" type="text" value="<?php echo $configs_youtube_page; ?>">
                        <?php echo form_error('youtube_page'); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="fb_page" class="control-label">Facebook fanpage code</label>
                        <textarea class="form-control" data-autoresize name="fb_page" id="fb_page"><?php echo $configs['fb_page']; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="iframe_map" class="control-label">Iframe bản đồ trang liên hệ</label>
                        <textarea class="form-control" data-autoresize name="iframe_map" id="iframe_map"><?php echo $configs['iframe_map']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Thông tin chuyển khoản ngân hàng</label>
                        <?php
                        $payment_info = isset($configs['payment_info']) ? $configs['payment_info'] : '';
                        $config_mini = array();
                        $config_mini['language'] = 'vi';
                        $config_mini['filebrowserBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
                        $config_mini['filebrowserUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
                        echo $this->ckeditor->editor("payment_info", $payment_info, $config_mini);
                        ?>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Nội dung thông tin liên hệ</label>
                        <?php
                        $site_content_contact = isset($configs['site_content_contact']) ? $configs['site_content_contact'] : '';
                        $config_mini = array();
                        $config_mini['language'] = 'vi';
                        $config_mini['filebrowserBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashBrowseUrl'] = base_url() . 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
                        $config_mini['filebrowserUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
                        $config_mini['filebrowserImageUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images&dir=images/news';
                        $config_mini['filebrowserFlashUploadUrl'] = base_url() . 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
                        echo $this->ckeditor->editor("site_content_contact", $site_content_contact, $config_mini);
                        ?>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.box -->
    </div>
</div>