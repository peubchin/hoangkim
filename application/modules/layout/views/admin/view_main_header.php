<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo base_url(); ?>" class="logo" target="_blank">Administrator</a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>" class="user-image" alt="<?php echo $full_name; ?>"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo $username; ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>" class="img-circle" alt="<?php echo $full_name; ?>" />
                            <p>
                                <?php echo $full_name; ?>
                                <small>Tham gia từ <?php echo date('d.m.Y', $regdate); ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo get_admin_url('users/profile'); ?>" class="btn btn-default btn-flat">Thông tin cá nhân</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo get_admin_url('logout'); ?>" class="btn btn-default btn-flat">Đăng xuất</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>