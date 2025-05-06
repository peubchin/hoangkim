<?php echo isset($postcat_home) ? $postcat_home : ''; ?>
<?php if (isset($support_none) && !empty($support_none)): ?>
    <section>
        <div class="container">
            <div class="row">
                <?php foreach ($support_none as $value): ?>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="box-service">
                            <img class="img-responsive pull-left" src="<?php echo base_url(get_module_path('images') . $value['image']); ?>" />
                            <p><?php echo $value['title']; ?> <span><?php echo strip_tags($value['content']); ?></span></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>