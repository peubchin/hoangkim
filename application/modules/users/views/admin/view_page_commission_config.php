<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cog"></i> Cấu hình</h3>
            </div>
            <div class="box-body">
                <form id="f-config" action="<?php echo current_full_url(); ?>" method="post" autocomplete="off">
                    <input type="hidden" name="token" value="<?php echo random_string('alnum', 16); ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- <label class="control-label">Rút tiền</label> -->
                                <div class="checkbox">
                                    <label for="enable_withdrawal">
                                        <input class="flat-blue" name="enable_withdrawal" id="enable_withdrawal" type="checkbox" value="1"<?php echo (isset($row['enable_withdrawal']) && ($row['enable_withdrawal'] == 1)) ? ' checked' : ''; ?>> <strong>Cho phép rút tiền</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- <label class="control-label">Rút tiền thưởng</label> -->
                                <div class="checkbox">
                                    <label for="enable_withdrawal_bonus">
                                        <input class="flat-blue" name="enable_withdrawal_bonus" id="enable_withdrawal_bonus" type="checkbox" value="1"<?php echo (isset($row['enable_withdrawal_bonus']) && ($row['enable_withdrawal_bonus'] == 1)) ? ' checked' : ''; ?>> <strong>Cho phép rút tiền thưởng</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>