<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-name"><em class="fa fa-table">&nbsp;</em>Cài đặt cổ phần nội bộ cho người dùng <?php echo $row['full_name']; ?></h3>
            </div>
            <div class="box-body">
                <form id="f-content" action="<?php echo current_url(); ?>" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('stock') != '' ? ' has-error' : ''; ?>">
                                <label for="stock" class="control-label">Số lượng</label>
                                <input class="form-control text-right" name="stock" id="stock" type="text" value="<?php echo isset($row['stock']) ? $row['stock'] : 0; ?>">
                                <?php echo form_error('stock'); ?>
                            </div>
                        </div>
                        <div class="col-md-9">
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