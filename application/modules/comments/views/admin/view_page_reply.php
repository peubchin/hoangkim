<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
				<h3 class="box-title"><em class="fa fa-edit">&nbsp;</em>Cập nhật bình luận</h3>
            </div>
            <div class="box-body">
                <form id="f-content" role="form" action="<?php echo get_admin_url($module_slug . '/reply/' . $row['id']); ?>" method="post" autocomplete="off">
                    <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
					<div class="form-group">
                        <label class="control-label text-success"><i class="fa fa-angle-double-right"></i> <?php echo $row['type'] == 'post' ? $row['post_title'] : $row['work_title']; ?></label>
						<br/>
						<label class="control-label"><i class="fa fa-comments"></i> <?php echo $row['comment']; ?></label>
                    </div>
					
                    <?php $has_error = (form_error('comment') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="comment" class="control-label">Trả lời bình luận</label>
                        <textarea class="form-control" data-autoresize name="comment" id="comment"></textarea>
                    </div>

                    <div class="text-center">
						<button class="btn btn-primary" type="submit">Trả lời</button>
                    </div>
                </form>
            </div>
        </div><!-- /.box -->
    </div>
</div>