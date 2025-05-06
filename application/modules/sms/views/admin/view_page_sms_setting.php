<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cấu hình SMS</h3>
            </div>
            <div class="box-body">
                <form id="f-content" role="form" action="<?php echo get_admin_url('sms/setting'); ?>" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group required<?php echo form_error('sms_api_key') != '' ? ' has-error' : ''; ?>">
                                <label for="sms_api_key" class="control-label">SMS API Key</label>
                                <input class="form-control" name="sms_api_key" type="text" value="<?php echo isset($configs['sms_api_key']) ? $configs['sms_api_key'] : ''; ?>">
                                <?php echo form_error('sms_api_key'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required<?php echo form_error('sms_secret_key') != '' ? ' has-error' : ''; ?>">
                                <label for="sms_secret_key" class="control-label">SMS SECRET Key</label>
                                <input class="form-control" name="sms_secret_key" type="text" value="<?php echo isset($configs['sms_secret_key']) ? $configs['sms_secret_key'] : ''; ?>">
                                <?php echo form_error('sms_secret_key'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group required<?php echo form_error('sms_brandname') != '' ? ' has-error' : ''; ?>">
                                <label for="sms_brandname" class="control-label">Brandname</label>
                                <input class="form-control" name="sms_brandname" type="text" value="<?php echo isset($configs['sms_brandname']) ? $configs['sms_brandname'] : ''; ?>">
                                <?php echo form_error('sms_brandname'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group required<?php echo form_error('sms_expired') != '' ? ' has-error' : ''; ?>">
                                <label for="sms_expired" class="control-label">Hiệu lực OTP (giây)</label>
                                <input class="form-control" name="sms_expired" type="text" value="<?php echo isset($configs['sms_expired']) ? $configs['sms_expired'] : 120; ?>">
                                <?php echo form_error('sms_expired'); ?>
                            </div>
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