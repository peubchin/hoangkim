<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <title><?php echo $title; ?></title>
        <link rel="icon" href="<?php echo base_url('assets/AdminLTE/favicon.ico'); ?>" />
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url('assets/AdminLTE/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url('assets/AdminLTE/font-awesome-4.3.0/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <!-- Theme style -->
        <link href="<?php echo base_url('assets/AdminLTE/dist/css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/AdminLTE/dist/css/skins/_all-skins.min.css'); ?>" rel="stylesheet" type="text/css" />
        <?php if(isset($plugins_css_admin) && is_array($plugins_css_admin) && !empty($plugins_css_admin)): ?>
    			<?php foreach ($plugins_css_admin as $value):?>
            <link rel="stylesheet" href="<?php echo base_url() . 'assets/plugins/' . $value['folder'] . '/' . $value['name'] . '.css'; ?>" />
    			<?php endforeach; ?>
        <?php endif; ?>
        <?php if(isset($modules_css) && is_array($modules_css) && !empty($modules_css)): ?>
          <?php foreach ($modules_css as $value):?>
            <link rel="stylesheet" href="<?php echo base_url() . 'assets/modules/' . $value['folder'] . '/css/' . $value['name'] . '.css'; ?>" />
          <?php endforeach; ?>
        <?php endif; ?>
        <link href="<?php echo base_url('assets/AdminLTE/style.css'); ?>" rel="stylesheet" type="text/css" />
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <!-- Start Main Header -->
            <?php $this->load->view('view_main_header'); ?>
            <!-- End Main Header -->

            <!-- Start Left Side -->
            <?php $this->load->view('view_left_side'); ?>
            <!-- End Left Side -->

            <!-- Start Content Wrapper -->
            <?php $this->load->view('view_content_wrapper'); ?>
            <!-- End Content Wrapper -->

            <!-- Start Main Footer -->
            <?php $this->load->view('view_main_footer'); ?>
            <!-- End Main Footer -->
        </div><!-- ./wrapper -->

        <!-- jQuery 2.1.3 -->
        <script src="<?php echo base_url('assets/AdminLTE/plugins/jQuery/jQuery-2.1.3.min.js'); ?>" type="text/javascript"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo base_url('assets/AdminLTE/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
        <!-- FastClick -->
        <script src="<?php echo base_url('assets/AdminLTE/plugins/fastclick/fastclick.min.js'); ?>"></script>
        <!-- AdminLTE App cần -->
        <script src="<?php echo base_url('assets/AdminLTE/dist/js/app.min.js'); ?>" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo base_url('assets/AdminLTE/dist/js/demo.js'); ?>"></script>
        <script type="text/javascript">
            base_url = '<?php echo base_url(); ?>';
            var delay = (function () {
                var timer = 0;
                return function (callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();
            jQuery.each(jQuery('textarea[data-autoresize]'), function () {
                var offset = this.offsetHeight - this.clientHeight;

                var resizeTextarea = function (el) {
                    jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
                };
                jQuery(this).on('keyup input', function () {
                    resizeTextarea(this);
                }).removeAttr('data-autoresize');
            });
        </script>
		<script>
            if (($(window).height() + 100) < $(document).height()) {
                $('#top-link-block').removeClass('hidden').affix({
                    offset: {top: 100}
                });
            }
            $(document).ready(function () {
                $('#top-link-block').on('click', function () {
                    $('html,body').animate({scrollTop: 0}, 'slow');
                    return false;
                });
            });
            function get_slug(str) {
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                str = str.replace(/[\W_]+/g, "-"); //thay thế các kí tự không thuộc alpha characters thành -
                str = str.replace(/-+-/g, "-"); //thay thế x- thành 1-
                str = str.replace(/^\-+|\-+$/g, ""); //cắt bỏ ký tự - ở đầu và cuối chuỗi
                return str;
            }
        </script>
        <!-- add dynamic js files -->
		<?php if(isset($plugins_script_admin) && is_array($plugins_script_admin) && !empty($plugins_script_admin)): ?>
			<?php foreach ($plugins_script_admin as $value):?>
            <script src="<?php echo base_url() . 'assets/plugins/' . $value['folder'] . '/' . $value['name'] . '.js'; ?>" type="text/javascript"></script>
			<?php endforeach; ?>
        <?php endif; ?>
		<?php if(isset($modules_script) && is_array($modules_script) && !empty($modules_script)): ?>
			<?php foreach ($modules_script as $value):?>
            <script src="<?php echo base_url() . 'assets/modules/' . $value['folder'] . '/js/' . $value['name'] . '.js?v=' . rand(); ?>" type="text/javascript"></script>
			<?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>
