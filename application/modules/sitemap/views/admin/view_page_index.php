<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em><?php echo $action; ?> sitemap (<?php echo $sitemap_file; ?>)</h3>
            </div>
            <div class="box-body">
                <div class="panel-group">
                    <div class="panel panel-info">
                        <div class="panel-body">
                        	<form action="<?php echo current_url(); ?>" method="post">
                        		<input type="hidden" name="allowed_sitemap" id="allowed_sitemap" value="1" />
                        		<?php if ($sitemap_file_exists): ?>
                        			<div class="form-group">
		                                <label class="control-label text-danger"><?php echo ucfirst($sitemap_file); ?> đã tồn tại, nếu cập nhật sẽ mất dữ liệu hiện tại. Nhấp vào để tiếp tục thực hiện chức năng này</label>
		                                <br>
		                                <button class="btn btn-primary" type="submit"><i class="fa fa-sign-in"></i> <?php echo $action . ' ' . $sitemap_file; ?></button>
		                            </div>
                    			<?php else: ?>
                    				<div class="form-group">
		                                <label class="control-label text-primary">Nhấp vào để thực hiện chức năng tạo tập tin <?php echo $sitemap_file; ?></label>
		                                <br>
		                                <button class="btn btn-success" type="submit"><i class="fa fa-sign-in"></i> <?php echo $action . ' ' . $sitemap_file; ?></button>
		                            </div>
                    			<?php endif;?>
                        	</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>