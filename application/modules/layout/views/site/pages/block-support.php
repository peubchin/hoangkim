<?php if (isset($support_none) && !empty($support_none)): ?>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
            <div class="avarta-support">
                <img class="img-responsive" src="<?php echo base_url(get_module_path('images') . $support_none['image']); ?>" />
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9">
            <div class="support-text">
                <p><?php echo isset($support_none['title']) ? $support_none['title'] : ''; ?></p>
                <h4><?php echo isset($support_none['content']) ? strip_tags($support_none['content']) : ''; ?></h4>
            </div>
        </div>
    </div>
<?php endif; ?>