<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-name"><em class="fa fa-table">&nbsp;</em>Thông tin</h3>
            </div>
            <div class="box-body">
                <form id="f-content" action="<?php echo get_admin_url($module_slug . '/content' . (isset($row['id']) ? ('/' . $row['id']) : '')); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php if (isset($row['id'])): ?>
                        <input type="hidden" value="<?php echo $row['id']; ?>" id="id" name="id">
                    <?php endif;?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group required<?php echo form_error('name') != '' ? ' has-error' : ''; ?>">
                                <label for="name" class="control-label">Tên gói sản phẩm</label>
                                <input class="form-control" name="name" id="name" type="text" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
                                <?php echo form_error('name'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required<?php echo form_error('price') != '' ? ' has-error' : ''; ?>">
                                <label for="price" class="control-label">Giá</label>
                                <input class="form-control text-right mask-price" name="price" type="text" value="<?php echo isset($row['price']) ? $row['price'] : ''; ?>">
                                <?php echo form_error('price'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group required<?php echo form_error('F0') != '' ? ' has-error' : ''; ?>">
                                <label for="F0" class="control-label">F0 (%)</label>
                                <input class="form-control text-right" name="F0" id="F0" type="text" value="<?php echo isset($row['F0']) ? $row['F0'] : ''; ?>">
                                <?php echo form_error('F0'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required<?php echo form_error('F1') != '' ? ' has-error' : ''; ?>">
                                <label for="F1" class="control-label">F1 (%)</label>
                                <input class="form-control text-right" name="F1" id="F1" type="text" value="<?php echo isset($row['F1']) ? $row['F1'] : ''; ?>">
                                <?php echo form_error('F1'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required<?php echo form_error('F2') != '' ? ' has-error' : ''; ?>">
                                <label for="F2" class="control-label">F2 (%)</label>
                                <input class="form-control text-right" name="F2" id="F2" type="text" value="<?php echo isset($row['F2']) ? $row['F2'] : ''; ?>">
                                <?php echo form_error('F2'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <?php if (isset($row['id'])): ?>
                            <input class="btn btn-primary" type="submit" value="Lưu thay đổi">
                        <?php else: ?>
                            <input class="btn btn-success" type="submit" value="Thêm">
                        <?php endif;?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>