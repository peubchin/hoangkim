<?php
if(!isset($role)){
    $role = '';
}
$is_admin = in_array($role, array('ADMIN')) ? TRUE : FALSE;
if(!$is_admin){
    $this->config->set_item('role', array(
        'AGENCY' => 'Đại lý',
        // 'SUPPORT' => 'Hoàng Kim hỗ trợ',
        // 'HOTLINE' => 'Hoàng Kim hotline',
    ));
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-file-text-o">&nbsp;</em>Thông tin tài khoản</h3>
            </div>
            <div class="box-body">
                <?php
                if (isset($row['userid'])) {
                    $action = get_admin_url('users/content/' . $row['userid']);
                } else {
                    $action = get_admin_url('users/content');
                }
                ?>
                <form id="f-user-add" role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php
                    if (isset($row['userid'])) {
                        echo '<input type="hidden" value="' . $row['userid'] . '" id="userid" name="userid" class="form-control" />';
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="role" class="control-label">Quyền</label>
                                <select class="form-control" name="role" id="role">
                                    <?php echo get_option_select($this->config->item('role'), isset($row['role']) ? $row['role'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('username') != '' ? ' has-error' : ''; ?>">
                                <label for="username" class="control-label">Tài khoản</label>
                                <?php $ad_username = (isset($row['username']) ? $row['username'] : ((form_error('username') != '') ? set_value('username') : '')); ?>
                                <input class="form-control" name="username" id="username" type="text" value="<?php echo $ad_username; ?>">
                                <?php echo form_error('username'); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('email') != '' ? ' has-error' : ''; ?>">
                                <label for="email" class="control-label">Email</label>
                                <?php $ad_email = (isset($row['email']) ? $row['email'] : ((form_error('email') != '') ? set_value('email') : '')); ?>
                                <input class="form-control" name="email" id="email" type="text" value="<?php echo $ad_email; ?>">
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phone" class="control-label">Điện thoại</label>
                                <input class="form-control" name="phone" id="phone" type="text" value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('password') != '' ? ' has-error' : ''; ?>">
                                <label for="password" class="control-label">Mật khẩu</label>
                                <input class="form-control" name="password" id="password" type="password" value="">
                                <?php echo form_error('password'); ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('passconf') != '' ? ' has-error' : ''; ?>">
                                <label for="passconf" class="control-label">Lặp lại mật khẩu</label>
                                <input class="form-control" name="passconf" id="passconf" type="password" value="">
                                <?php echo form_error('passconf'); ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('full_name') != '' ? ' has-error' : ''; ?>">
                                <label for="full_name" class="control-label">Họ tên</label>
                                <?php $ad_full_name = (isset($row['full_name']) ? $row['full_name'] : ((form_error('full_name') != '') ? set_value('full_name') : '')); ?>
                                <input class="form-control" name="full_name" id="full_name" type="text" value="<?php echo $ad_full_name; ?>">
                                <?php echo form_error('full_name'); ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender" class="control-label">Giới tính</label>
                                <select class="form-control" name="gender">
                                    <?php echo get_option_gender(isset($row['gender']) ? $row['gender'] : 'N'); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="control-label">Địa chỉ</label>
                                <input class="form-control" name="address" id="address" type="text" value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <br>
                                    <label for="view_mail">
                                        <?php $checked = ((isset($row['view_mail']) && ($row['view_mail'] == 0)) ? '' : ' checked'); ?>
                                        <input class="flat-blue" name="view_mail" id="view_mail" type="checkbox" value="1"<?php echo $checked; ?>> Hiển thị email
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <br>
                                    <label for="is_wholesale">
                                        <input class="flat-blue" name="is_wholesale" id="is_wholesale" type="checkbox" value="1"<?php echo (isset($row['is_wholesale']) && ($row['is_wholesale'] == 1)) ? ' checked' : ''; ?>> Tài khoản sỉ
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Ảnh đại diện</label>
                        <input type="file" class="file" name="photo[]">
                        <?php if(isset($row['photo']) && trim($row['photo']) != ''): ?>
                        <div style="margin-top: 10px;">
                            <img src="<?php echo get_image(get_module_path('users') . $row['photo'], get_module_path('users') . 'no-image.png'); ?>" width="100px" alt="" class="img-thumbnail">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group required<?php echo form_error('identity_number_card') != '' ? ' has-error' : ''; ?>">
                                <label for="identity_number_card" class="control-label">Số chứng minh thư</label>
                                <input class="form-control" name="identity_number_card" id="identity_number_card" type="text" value="<?php echo isset($row['identity_number_card']) ? $row['identity_number_card'] : ''; ?>">
                                <?php echo form_error('identity_number_card'); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                        	<div class="form-group">
                                <label for="birthday" class="control-label">Ngày tháng năm sinh</label>
                                <div class="input-group input-append date" id="datePicker">
                                    <?php $birthday = (isset($row['birthday']) && ($row['birthday'] != 0) ? date('d-m-Y', $row['birthday']) : ''); ?>
                                    <input type="text" class="form-control" name="birthday" value="<?php echo $birthday; ?>" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
						<div class="col-md-3">
                        	<div class="form-group">
                                <label for="identity_card_date" class="control-label">Ngày cấp CMND</label>
                                <div class="input-group input-append date" id="datePicker-card-date">
                                    <?php $identity_card_date = (isset($row['identity_card_date']) && ($row['identity_card_date'] != 0) ? date('d-m-Y', $row['identity_card_date']) : ''); ?>
                                    <input type="text" class="form-control" name="identity_card_date" value="<?php echo $identity_card_date; ?>" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Mã số thuế cá nhân</label>
                                <input type="text" class="form-control" name="tax_identification_number" value="<?php echo isset($row['tax_identification_number']) ? $row['tax_identification_number'] : ''; ?>">
                            </div>
                        </div>
                        <!-- <div class="col-md-5 offset-md-1">
                            <div class="form-group">
                                <label class="control-label">Hình chứng minh nhân dân (mặt trước)</label>
                                <input name="identity_card_front[]" class="file" type="file">
                                <?php if(isset($row['identity_card_front']) && trim($row['identity_card_front']) != ''): ?>
                                <div style="margin-top: 10px;">
                                    <img src="<?php echo get_image(get_module_path('users') . $row['identity_card_front'], get_module_path('users') . 'no-image.png'); ?>" width="100px" alt="" class="img-thumbnail">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Hình chứng minh nhân dân (mặt sau)</label>
                                <input name="identity_card_back[]" class="file" type="file">
                                <?php if(isset($row['identity_card_back']) && trim($row['identity_card_back']) != ''): ?>
                                <div style="margin-top: 10px;">
                                    <img src="<?php echo get_image(get_module_path('users') . $row['identity_card_back'], get_module_path('users') . 'no-image.png'); ?>" width="100px" alt="" class="img-thumbnail">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div> -->
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
        </div>
    </div>
</div>
