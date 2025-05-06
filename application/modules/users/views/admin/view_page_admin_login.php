<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <title><?php echo $title; ?></title>
        <link rel="icon" href="<?php echo base_url('assets/AdminLTE/favicon.ico'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/AdminLTE/bootstrap/css/bootstrap.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/AdminLTE/font-awesome-4.3.0/css/font-awesome.css'); ?>" />
        <script src="<?php echo base_url('assets/AdminLTE/plugins/jQuery/jQuery-2.1.3.min.js'); ?>"></script>
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="background-color: #F4F5F5;">
        <div class="container">
            <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Đăng nhập</div>
                    </div>

                    <div style="padding-top:30px" class="panel-body" >
                        <?php
                        if (isset($messing)) {
                            echo '<div id="login-alert" class="alert alert-danger"><p>' . $messing . '</p></div>';
                        }
                        ?>

                        <form id="loginform" role="form" action="<?php echo get_admin_url(); ?>" method="post">
                            <?php $has_error = (form_error('username') != '' ? ' has-error' : ''); ?>
                            <div class="form-group<?php echo $has_error; ?>">
                                <label class="control-label" for="username">Tên đăng nhập:</label>                                
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <?php $username = (set_value('username') != '' ? set_value('username') : ''); ?>
                                    <input id="username" type="text" class="form-control" name="username" value="<?php echo $username; ?>" placeholder="Tên đăng nhập..." />
                                </div>
                                <span class="help-block"><?php echo form_error('username'); ?></span>
                            </div>

                            <?php $has_error = (form_error('password') != '' ? ' has-error' : ''); ?>
                            <div class="form-group<?php echo $has_error; ?>">
                                <label class="control-label" for="password">Mât khẩu:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Mật khẩu..." />
                                </div>
                                <span class="help-block"><?php echo form_error('password'); ?></span>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input id="login-remember" type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập
                                    </label>
                                </div>
                            </div>

                            <div style="margin-top:10px" class="form-group">
                                <button type="submit" class="btn btn-success">Đăng nhập</button>
                            </div>

                            <div class="form-group">
                                <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                    <a href="<?php echo base_url(); ?>" target="_blank">Về trang chủ</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url() . 'assets/plugins/jquery-validation' . '/' . 'jquery.validate' . '.js'; ?>" type="text/javascript"></script>
        <script src="<?php echo base_url() . 'assets/plugins/jquery-validation' . '/localization/' . 'messages_vi' . '.js'; ?>" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#loginform").validate({
                    rules: {
                        username: {
                            required: true,
                            minlength: 5,
                            maxlength: 20
                        },
                        password: {
                            required: true,
                            minlength: 6,
                            maxlength: 25
                        }
                    },
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    },
                    errorElement: 'span',
                    errorClass: 'help-block',
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
    </body>
</html>