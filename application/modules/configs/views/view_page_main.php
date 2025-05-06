<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cấu hình site</h3>
            </div>
            <div class="box-body">
                <form id="f-cat" role="form" action="<?php echo get_admin_url('settings/main'); ?>" method="post" autocomplete="off"  enctype="multipart/form-data">
                    <?php $has_error = (form_error('site_name') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="site_name" class="control-label">Tên gọi của site</label>
                        <?php $configs_site_name = (set_value('site_name') != '') ? set_value('site_name') : $configs['site_name']; ?>
                        <input class="form-control" name="site_name" id="site_name" type="text" value="<?php echo $configs_site_name; ?>">
                        <?php echo form_error('site_name'); ?>
                    </div>

                    <?php $has_error = (form_error('site_address') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="site_address" class="control-label">Địa chỉ</label>
                        <?php $configs_site_address = (set_value('site_address') != '') ? set_value('site_address') : $configs['site_address']; ?>
                        <input class="form-control" name="site_address" id="site_address" type="text" value="<?php echo $configs_site_address; ?>">
                        <?php echo form_error('site_address'); ?>
                    </div>
                    
                    <div class="form-group">
                        <?php $favicon = (isset($configs['favicon']) && ($configs['favicon'] != '') ? $configs['favicon'] : 'logo_default.png'); ?>
                        <label for="favicon" class="control-label">Hình favicon (Nên chọn ICO kích thước 16x16)</label>
                        <input id="favicon" name="favicon[]" class="file" type="file">
                        <div id="current-favicon" style="margin-top: 10px;">
                            <img src="<?php echo base_url(get_module_path('logo') . $favicon); ?>" alt="" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="form-group">
                        <?php $site_logo = (isset($configs['site_logo']) && ($configs['site_logo'] != '') ? $configs['site_logo'] : 'logo_default.png'); ?>
                        <label for="site_logo" class="control-label">Logo (Nên chọn PNG)</label>
                        <input id="site_logo" name="site_logo[]" class="file" type="file">
                        <div id="current-site_logo" style="margin-top: 10px;">
                            <img src="<?php echo base_url(get_module_path('logo') . $site_logo); ?>" width="100px" alt="" class="img-thumbnail">
                        </div>
                    </div>
					
					<!--<div class="form-group">
                        <?php $watermark_image = (isset($configs['watermark_image']) && ($configs['watermark_image'] != '') ? $configs['watermark_image'] : 'logo_default.png'); ?>
                        <label for="watermark_image" class="control-label">Ảnh đóng dấu (Nên chọn PNG)</label>
                        <input id="watermark_image" name="watermark_image[]" class="file" type="file">
                        <div style="margin-top: 10px;">
                            <img src="<?php echo base_url(get_module_path('logo') . $watermark_image); ?>" width="100px" alt="" class="img-thumbnail">
                        </div>
                    </div>-->

                    <!--<div class="form-group">
                        <label for="watermark_opacity" class="control-label">Độ trong suốt của ảnh đóng dấu (1 đến 100)</label>
                        <?php $configs_watermark_opacity = (set_value('watermark_opacity') != '') ? set_value('watermark_opacity') : $configs['watermark_opacity']; ?>
                        <input class="form-control" name="watermark_opacity" id="watermark_opacity" type="number" value="<?php echo $configs_watermark_opacity; ?>" max="100" min="1">
                    </div>-->
                    
                    <div class="form-group">
                        <label for="sitemap" class="control-label">File sitemap.xml</label>
                        <input id="sitemap" name="sitemap[]" class="file" type="file">
                    </div>
                    
                    <div class="form-group">
                        <label for="robots" class="control-label">File robots.txt</label>
                        <input id="robots" name="robots[]" class="file" type="file">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label for="display_copyright_developer">
                                <?php $checked = ((isset($configs['display_copyright_developer']) && ($configs['display_copyright_developer'] == 1)) ? ' checked' : ''); ?>
                                <input class="flat-blue" name="display_copyright_developer" id="display_copyright_developer" type="checkbox" value="1"<?php echo $checked; ?>> <strong>Hiển thị bản quyền nhà phát triển</strong>
                            </label>
                        </div>                        
                    </div>

                    <div class="form-group">
                        <label for="analytics_UA_code" class="control-label">UA code (Analytics)</label>
                        <?php $configs_analytics_UA_code = (set_value('analytics_UA_code') != '') ? set_value('analytics_UA_code') : $configs['analytics_UA_code']; ?>
                        <input class="form-control" name="analytics_UA_code" id="analytics_UA_code" type="text" value="<?php echo $configs_analytics_UA_code; ?>">
                    </div>

                    <?php $has_error = (form_error('title_seo') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="title_seo" class="control-label">Title (SEO)</label>
                        <?php $configs_title_seo = (set_value('title_seo') != '') ? set_value('title_seo') : $configs['title_seo']; ?>
                        <input class="form-control" name="title_seo" id="title_seo" type="text" value="<?php echo $configs_title_seo; ?>">
                        <?php echo form_error('title_seo'); ?>
                    </div>

                    <div class="form-group">
                        <label for="site_description" class="control-label">Mô tả của site (SEO)</label>
                        <textarea class="form-control" data-autoresize name="site_description" id="site_description"><?php echo $configs['site_description']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="site_keywords" class="control-label">Từ khóa cho máy chủ tìm kiếm (SEO)</label>
                        <textarea class="form-control" data-autoresize name="site_keywords" id="site_keywords"><?php echo $configs['site_keywords']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="other_seo" class="control-label">Other (SEO)</label>
                        <textarea class="form-control" data-autoresize name="other_seo" id="other_seo"><?php echo $configs['other_seo']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="h1_seo" class="control-label">H1 (SEO)</label>
                        <input class="form-control" name="h1_seo" type="text" id="h1_seo" value="<?php if (isset($configs['h1_seo'])) echo $configs['h1_seo']; ?>" maxlength="255">
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.box -->
    </div>
</div>