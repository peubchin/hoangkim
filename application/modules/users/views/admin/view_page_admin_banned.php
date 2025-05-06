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
        <style type="text/css">
            .text-blue{
                color: #3f51d0;
                font-weight: 600;
            }
            .text-expired{
                color: #f00;
                font-weight: 600;
            }
        </style>
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
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
            var ip = '<?php echo $ip; ?>';
            var timeleft = parseInt('<?php echo $max_time_in_seconds; ?>');
            function runTimer(){
                if($('#countdown').length){
                    var downloadTimer = setInterval(function() {
                        $('#countdown').html(timeleft + 's');
                        timeleft -= 1;
                        if (timeleft <= 0) {
                            clearInterval(downloadTimer);
                            $.ajax({
                                url: base_url + 'admin/users/login-attempt-reset-ajax',
                                data: {
                                    'ip': ip
                                },
                                type: 'POST',
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        location.reload();
                                    }
                                },
                                error: function(e) {
                                    console.log('Error processing your request: ' + e.responseText);
                                }
                            });
                            return false;
                        }
                    }, 1000);
                }
            }
            $(document).ready(function() {
                runTimer();
            });
        </script>
    </body>
</html>