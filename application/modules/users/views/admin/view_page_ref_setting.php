<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-name"><em class="fa fa-table">&nbsp;</em>Cài đặt người giới thiệu cho người dùng <?php echo $row['full_name']; ?></h3>
            </div>
            <div class="box-body">
                <form id="f-content" action="<?php echo current_url(); ?>" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label class="control-label">Chọn người giới thiệu</label>
                                <select class="form-control chosen-select-enable-search" name="referred_by">
                                    <option value="0">-- Không có --</option>
                                    <?php echo display_option_select($users, 'userid', 'full_name', isset($row['referred_by']) ? $row['referred_by'] : 0); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <br/>
                            <div class="form-group" style="margin-top: 5px;">
                                <label class="control-label">&nbsp;</label>
                                <input class="btn btn-sm btn-success" type="submit" value="Lưu">
                                &nbsp;<a class="btn btn-sm btn-danger" href="<?php echo get_admin_url($module_slug); ?>">Hủy</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>