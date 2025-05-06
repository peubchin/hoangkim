<?php
if(!isset($role)){
    $role = '';
}
$is_role_orders_access = in_array($role, array('ADMIN', 'SUPPORT', 'HOTLINE', 'ACCOUNTANT')) ? TRUE : FALSE;
$is_role_users_access = in_array($role, array('ADMIN', 'SUPPORT', 'HOTLINE', 'ACCOUNTANT')) ? TRUE : FALSE;
$is_role_admin_accountant = in_array($role, array('ADMIN', 'ACCOUNTANT')) ? TRUE : FALSE;
$is_admin = in_array($role, array('ADMIN')) ? TRUE : FALSE;
// $is_admin = isset($group_id) && in_array($group_id, array(6));
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Chào <?php echo $full_name; ?>!</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MENU</li>
            <li<?php if ($menu_admin_active == '') echo ' class="active"'; ?>>
                <a href="<?php echo get_admin_url(); ?>">
                    <i class="fa fa-dashboard"></i> <span>Bảng điều khiển</span>
                </a>
            </li>
            <?php if($is_admin): ?>
            <li class="treeview<?php if ($menu_admin_active == 'settings') echo ' active'; ?>">
                <a href="<?php echo get_admin_url('settings'); ?>">
                    <i class="fa fa-cogs"></i> <span>Cấu hình</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('settings'); ?>"><i class="fa fa-angle-double-right"></i> Cấu hình chung</a></li>
                    <li><a href="<?php echo get_admin_url('settings/main'); ?>"><i class="fa fa-angle-double-right"></i> Cấu hình site</a></li>
                    <li><a href="<?php echo get_admin_url('settings/popup'); ?>"><i class="fa fa-angle-double-right"></i> Cấu hình thông báo</a></li>
                </ul>
            </li>
            <li class="treeview<?php if ($menu_admin_active == 'images') echo ' active'; ?>">
                <a href="<?php echo get_admin_url('images'); ?>"><i class="fa fa-photo"></i> Hình ảnh</a>
            </li>
            <li class="treeview<?php if ($menu_admin_active == 'info') echo ' active'; ?>">
                <a href="#">
                    <i class="fa fa-phone"></i>
                    <span>Thông tin</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('info'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách thông tin</a></li>
                    <li><a href="<?php echo get_admin_url('info/content'); ?>"><i class="fa fa-angle-double-right"></i> Thêm thông tin</a></li>
                </ul>
            </li>
            <li class="treeview<?php if ($menu_admin_active == 'emails') echo ' active'; ?>">
                <a href="#">
                    <i class="fa fa-envelope-o"></i>
                    <span>Email</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('emails/logs'); ?>"><i class="fa fa-angle-double-right"></i> Nhật ký gửi mail</a></li>
                    <li><a href="<?php echo get_admin_url('emails'); ?>"><i class="fa fa-angle-double-right"></i> Cấu hình</a></li>
                </ul>
            </li>
            <li class="treeview<?php if ($menu_admin_active == 'menu') echo ' active'; ?>">
                <a href="#">
                    <i class="fa fa-bars"></i>
                    <span>Menu</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('menu'); ?>"><i class="fa fa-angle-double-right"></i> Các menu</a></li>
                    <li><a href="<?php echo get_admin_url('menu/content'); ?>"><i class="fa fa-angle-double-right"></i> Thêm menu</a></li>
                </ul>
            </li>
            <li class="treeview<?php if ($menu_admin_active == 'pages') echo ' active'; ?>">
                <a href="<?php echo get_admin_url('pages'); ?>"><i class="fa fa-file"></i> Trang tĩnh</a>
            </li>
            <li class="treeview<?php if ($menu_admin_active == 'posts') echo ' active'; ?>">
                <a href="#">
                    <i class="fa fa-edit"></i>
                    <span>Tin tức</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('posts/cat'); ?>"><i class="fa fa-angle-double-right"></i> Chủ đề</a></li>
                    <li><a href="<?php echo get_admin_url('posts'); ?>"><i class="fa fa-angle-double-right"></i> Bài viết</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if($is_role_admin_accountant): ?>
            <li class="treeview<?php echo in_array($menu_admin_active, array('shops', 'comments')) ? ' active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> <span>Sản phẩm</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('shops/items'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách sản phẩm</a></li>
                    <li><a href="<?php echo get_admin_url('shops/content'); ?>"><i class="fa fa-angle-double-right"></i> Thêm sản phẩm</a></li>
                    <li><a href="<?php echo get_admin_url('shops/cat'); ?>"><i class="fa fa-angle-double-right"></i> Danh mục sản phẩm</a></li>
                    <li><a href="<?php echo get_admin_url('comments'); ?>"><i class="fa fa-angle-double-right"></i> Đánh giá</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if($is_role_orders_access): ?>
            <li class="treeview<?php echo ($menu_admin_active == 'orders') ? ' active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Đơn đặt hàng</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('orders'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách</a></li>
                    <li><a href="<?php echo get_admin_url('orders/content'); ?>"><i class="fa fa-angle-double-right"></i> Thêm</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if($is_admin): ?>
            <li class="treeview<?php echo ($menu_admin_active == 'banker') ? ' active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-bank"></i>
                    <span>Ngân hàng</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('banker'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách ngân hàng</a></li>
                    <li><a href="<?php echo get_admin_url('banker/content'); ?>"><i class="fa fa-angle-double-right"></i> Thêm ngân hàng</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if($is_role_users_access): ?>
            <li class="treeview<?php echo ($menu_admin_active == 'users') ? ' active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Người dùng</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('users'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách</a></li>
                    <li><a href="<?php echo get_admin_url('users/content'); ?>"><i class="fa fa-angle-double-right"></i> Thêm</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <!-- <li class="treeview<?php echo ($menu_admin_active == 'sms') ? ' active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-envelope-o"></i>
                    <span>SMS</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('sms'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách SMS</a></li>
                    <li><a href="<?php echo get_admin_url('sms/setting'); ?>"><i class="fa fa-angle-double-right"></i> Cấu hình SMS</a></li>
                </ul>
            </li> -->
            <?php if($is_role_admin_accountant): ?>
            <li class="treeview<?php echo ($menu_admin_active == 'commission') ? ' active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-history"></i>
                    <span>Giao dịch</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo get_admin_url('commission'); ?>"><i class="fa fa-angle-double-right"></i> Danh sách</a></li>
                    <?php if($is_admin): ?>
                    <li><a href="<?php echo get_admin_url('commission/config'); ?>"><i class="fa fa-angle-double-right"></i> Cấu hình</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <?php if($is_admin): ?>
			<li class="treeview<?php echo ($menu_admin_active == 'newsletter') ? ' active' : ''; ?>">
                <a href="<?php echo get_admin_url('newsletter'); ?>"><i class="fa fa-envelope-o"></i> Newsletter</a>
            </li>
            <li<?php if ($menu_admin_active == 'contact' || $menu_admin_active == 'view_contact') echo ' class="active"'; ?>>
                <a href="<?php echo get_admin_url('contact'); ?>">
                    <i class="fa fa-envelope"></i> <span>Liên hệ</span>
                    <small class="badge pull-right bg-yellow"><?php echo $num_rows_contact; ?></small>
                </a>
            </li>
            <li class="treeview<?php echo ($menu_admin_active == 'sitemap') ? ' active' : ''; ?>">
                <a href="<?php echo get_admin_url('sitemap'); ?>"><i class="fa fa-sitemap"></i> Sitemap</a>
            </li>            
            <?php endif; ?>
        </ul>
    </section>
</aside>