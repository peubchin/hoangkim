<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-cog">&nbsp;</em>Email settings</h3>
            </div>
            <div class="box-body">
                <form id="f-content" role="form" action="<?php echo get_admin_url('emails'); ?>" method="post" autocomplete="off">
                    <?php $has_error = (form_error('protocol') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="protocol" class="control-label">Protocol</label>
                        <?php $configs_protocol = (set_value('protocol') != '') ? set_value('protocol') : $configs['protocol']; ?>
                        <input class="form-control" name="protocol" id="protocol" type="text" value="<?php echo $configs_protocol; ?>">
                        <?php echo form_error('protocol'); ?>
                    </div>
                    <?php $has_error = (form_error('smtp_host') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="smtp_host" class="control-label">SMTP Host</label>
                        <?php $configs_smtp_host = (set_value('smtp_host') != '') ? set_value('smtp_host') : $configs['smtp_host']; ?>
                        <input class="form-control" name="smtp_host" id="smtp_host" type="text" value="<?php echo $configs_smtp_host; ?>">
                        <?php echo form_error('smtp_host'); ?>
                    </div>

                    <?php $has_error = (form_error('smtp_port') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="smtp_port" class="control-label">SMTP Port</label>
                        <?php $configs_smtp_port = (set_value('smtp_port') != '') ? set_value('smtp_port') : $configs['smtp_port']; ?>
                        <input class="form-control" name="smtp_port" id="smtp_port" type="text" value="<?php echo $configs_smtp_port; ?>">
                        <?php echo form_error('smtp_port'); ?>
                    </div>

                    <?php $has_error = (form_error('smtp_user') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="smtp_user" class="control-label">SMTP User</label>
                        <?php $configs_smtp_user = (set_value('smtp_user') != '') ? set_value('smtp_user') : $configs['smtp_user']; ?>
                        <input class="form-control" name="smtp_user" id="smtp_user" type="text" value="<?php echo $configs_smtp_user; ?>">
                        <?php echo form_error('smtp_user'); ?>
                    </div>

                    <div class="form-group">
                        <label for="smtp_pass" class="control-label">SMTP Pass</label>
                        <input class="form-control" name="smtp_pass" id="smtp_pass" type="password" value="">
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>