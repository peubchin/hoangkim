<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-file-text-o">&nbsp;</em>Thông tin tài khoản</h3>
            </div>
            <div class="box-body">
                <?php
                $action = get_admin_url('users/profile');
                ?>
                <form id="f-user-add" role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php
                    if (isset($row['userid'])) {
                        echo '<input type="hidden" value="' . $row['userid'] . '" id="userid" name="userid" class="form-control" />';
                    }
                    ?>
                    <?php $has_error = (form_error('username') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="username" class="control-label">Tài khoản</label>
                        <?php $ad_username = (isset($row['username']) ? $row['username'] : ((form_error('username') != '') ? set_value('username') : '')); ?>
                        <input class="form-control" name="username" id="username" type="text" value="<?php echo $ad_username; ?>">
                        <?php echo form_error('username'); ?>
                    </div>

                    <?php $has_error = (form_error('email') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="email" class="control-label">Email</label>
                        <?php $ad_email = (isset($row['email']) ? $row['email'] : ((form_error('email') != '') ? set_value('email') : '')); ?>
                        <input class="form-control" name="email" id="email" type="text" value="<?php echo $ad_email; ?>">
                        <?php echo form_error('email'); ?>
                    </div>

                    <?php $has_error = (form_error('password') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="password" class="control-label">Mật khẩu</label>
                        <input class="form-control" name="password" id="password" type="password" value="">
                        <?php echo form_error('password'); ?>
                    </div>

                    <?php $has_error = (form_error('passconf') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="passconf" class="control-label">Lặp lại mật khẩu</label>
                        <input class="form-control" name="passconf" id="passconf" type="password" value="">
                        <?php echo form_error('passconf'); ?>
                    </div>

                    <div class="form-group">
                        <label for="question" class="control-label">Câu hỏi bảo mật</label>
                        <?php $question = (isset($row['question']) ? $row['question'] : ''); ?>
                        <input class="form-control" name="question" id="question" type="text" value="<?php echo $question; ?>">
                    </div>

                    <div class="form-group">
                        <label for="answer" class="control-label">Câu trả lời</label>
                        <?php $answer = (isset($row['answer']) ? $row['answer'] : ''); ?>
                        <input class="form-control" name="answer" id="answer" type="text" value="<?php echo $answer; ?>">
                    </div>

                    <?php $has_error = (form_error('full_name') != '' ? ' has-error' : ''); ?>
                    <div class="form-group required<?php echo $has_error; ?>">
                        <label for="full_name" class="control-label">Họ tên</label>
                        <?php $ad_full_name = (isset($row['full_name']) ? $row['full_name'] : ((form_error('full_name') != '') ? set_value('full_name') : '')); ?>
                        <input class="form-control" name="full_name" id="full_name" type="text" value="<?php echo $ad_full_name; ?>">
                        <?php echo form_error('full_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="control-label">Giới tính</label>
                        <select class="form-control" name="gender">
                            <?php
                            $gender = isset($row['gender']) ? $row['gender'] : 'N';
                            echo get_option_gender($gender);
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="photo" class="control-label">Ảnh đại diện</label>
                        <input id="photo" name="photo[]" class="file" type="file">
                        <?php if(isset($photo) && trim($photo) != ''): ?>
                        <div style="margin-top: 10px;">
                            <img src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>" width="100px" alt="" class="img-thumbnail">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="birthday" class="control-label">Ngày tháng năm sinh</label>
                        <div class="input-group input-append date" id="datePicker">
                            <?php $birthday = (isset($row['birthday']) && ($row['birthday'] != 0) ? date('d-m-Y', $row['birthday']) : ''); ?>
                            <input type="text" class="form-control" name="birthday" value="<?php echo $birthday; ?>" />
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sig" class="control-label">Chữ ký</label>
                        <textarea class="form-control" data-autoresize name="sig" id="sig"><?php if (isset($row['sig'])) echo $row['sig']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label for="view_mail">
                                <?php $checked = ((isset($row['view_mail']) && ($row['view_mail'] == 0)) ? '' : ' checked'); ?>
                                <input class="flat-blue" name="view_mail" id="view_mail" type="checkbox" value="1"<?php echo $checked; ?>> Hiển thị email
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <?php
                        if (isset($row['userid'])) {
                            echo '<button class="btn btn-primary" type="submit">Lưu thay đổi</button>';
                        } else {
                            echo '<button class="btn btn-primary" type="submit">Thêm</button>';
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div><!-- /.box -->
    </div>
</div>