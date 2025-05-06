<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['asset_path'] = 'assets/';
$config['css_path'] = 'assets/css/';
$config['js_path'] = 'assets/js/';
$config['img_path'] = 'assets/images/';
$config['style_path'] = 'assets/style/';
$config['xml_path'] = 'assets/xml/';
$config['upload_path'] = 'uploads/';
$config['temp_path'] = 'temps/';
$config['modules_path'] = array(
    'captcha' => 'uploads/captcha/',
    'banker' => 'uploads/banker/',
    'users' => 'uploads/users/',
    'users_qr' => 'uploads/users/qr/',
    'shops' => 'uploads/shops/',
    'shops_thumbnais' => 'uploads/shops/thumb/',
    'shops_cat' => 'uploads/shops/catalogs/',
    'posts' => 'uploads/posts/',
    'posts_thumbnais' => 'uploads/posts/thumbnais/',
    'pages' => 'uploads/pages/',
    'images' => 'uploads/images/',
    'watermark' => 'uploads/watermark/',
    'logo' => 'uploads/logo/',
    'menu' => 'uploads/menu/',
    'videos' => 'uploads/videos/',
);


/* End of file asset.php */