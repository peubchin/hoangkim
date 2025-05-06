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
                    <div class="form-group required<?php echo form_error('name') != '' ? ' has-error' : ''; ?>">
                        <label for="name" class="control-label">Tên ngân hàng</label>
                        <input class="form-control" name="name" id="name" type="text" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
                        <?php echo form_error('name'); ?>
                    </div>

                    <div class="form-group required<?php echo form_error('code') != '' ? ' has-error' : ''; ?>">
                        <label for="code" class="control-label">Mã code giao dịch ngân hàng</label>
                        <input class="form-control" name="code" id="code" type="text" value="<?php echo isset($row['code']) ? $row['code'] : ''; ?>">
                        <?php echo form_error('code'); ?>
                    </div>

                    <div class="form-group">
                        <label for="image" class="control-label">Logo (Kích thước 40x40)</label>
                        <input id="image" name="image[]" class="file" type="file">
                        <?php if(isset($row['image']) && trim($row['image']) != ''): ?>
                        <div style="margin-top: 10px;">
                            <img src="<?php echo get_image(get_module_path('banker') . $row['image'], get_module_path('banker') . 'no-image.png'); ?>" alt="" class="img-thumbnail">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="company" class="control-label">Tên đầy đủ ngân hàng</label>
                        <input class="form-control" name="company" id="company" type="text" value="<?php echo isset($row['company']) ? $row['company'] : ''; ?>">
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