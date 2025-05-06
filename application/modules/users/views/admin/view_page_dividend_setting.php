<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-name"><em class="fa fa-table">&nbsp;</em>Cài đặt cổ tức cổ phần nội bộ cho người dùng <?php echo $row['full_name']; ?></h3>
            </div>
            <div class="box-body">
                <form id="f-content" action="<?php echo current_url(); ?>" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('dividend') != '' ? ' has-error' : ''; ?>">
                                <label class="control-label">Số lượng</label>
                                <input type="text" class="form-control text-right" id="dividend" name="dividend" value="<?php echo isset($row['dividend']) ? $row['dividend'] : 0; ?>">
                                <?php echo form_error('dividend'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Ghi chú</label>
                                <input type="text" class="form-control" name="dividend_note" value="<?php echo isset($row['dividend_note']) ? html_escape($row['dividend_note']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
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