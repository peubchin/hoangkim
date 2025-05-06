<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-name"><em class="fa fa-table">&nbsp;</em>Thông tin</h3>
            </div>
            <div class="box-body">
                <form id="f-content" action="<?php echo get_admin_url($module_slug . '/content'); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('user_id') != '' ? ' has-error' : ''; ?>">
                                <label for="user_id" class="control-label">Thành viên</label>
                                <select class="form-control chosen-select-enable-search" tabindex="1" data-placeholder="Chọn thành viên" name="user_id" id="user_id">
                                    <?php echo display_option_select($users, 'userid', 'full_name', 0); ?>
                                </select>
                                <?php echo form_error('user_id'); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('amount') != '' ? ' has-error' : ''; ?>">
                                <label for="amount" class="control-label">Số tiền</label>
                                <input class="form-control text-right mask-price" name="amount" id="amount" type="text" value="">
                                <?php echo form_error('amount'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="message" class="control-label">Ghi chú</label>
                                <input class="form-control" name="message" id="message" type="text" value="Người dùng nạp tiền vào tài khoản bởi admin">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <input class="btn btn-success" type="submit" value="Nạp tiền">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>