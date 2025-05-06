<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Configs extends Layout {

    public $_logo = '';

    function __construct() {
        parent::__construct();
        $this->_logo = get_module_path('logo');
        $this->_data['breadcrumbs_module_name'] = 'Cấu hình';

        $this->_breadcrumbs_admin[] = array(
            'url' => 'settings',
            'name' => $this->_data['breadcrumbs_module_name']
        );
        $this->set_breadcrumbs_admin();
    }

    function index() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));

        $post = $this->input->post();
        if (!empty($post)) {
            $data = array(
                'site_email' => $this->input->post('site_email'),
                'facebook_fanpage' => $this->input->post('facebook_fanpage'),
                'google_plus' => $this->input->post('google_plus'),
                'skype_page' => $this->input->post('skype_page'),
                'twitter_page' => $this->input->post('twitter_page'),
                'linkedin_page' => $this->input->post('linkedin_page'),
                'youtube_page' => $this->input->post('youtube_page'),
                'fb_page' => $this->input->post('fb_page'),
                'iframe_map' => $this->input->post('iframe_map'),
                'payment_info' => $this->input->post('payment_info'),
                'site_content_contact' => $this->input->post('site_content_contact'),
            );
            $this->update_systems($data);

            $notify_type = 'success';
            $notify_content = 'Đã lưu cấu hình chung!';
            $this->set_notify_admin($notify_type, $notify_content);

            redirect(current_url());
        }

        $this->_data['breadcrumbs_module_func'] = 'Cấu hình chung';
        $this->_breadcrumbs_admin[] = array(
            'url' => 'settings',
            'name' => $this->_data['breadcrumbs_module_func']
        );
        $this->set_breadcrumbs_admin();

        $this->_data['title'] = 'Cấu hình chung - ' . $this->_data['title'];
        $configs = $this->M_configs->get_configs();
        $this->_data['configs'] = $this->set_configs($configs);
        $this->_data['main_content'] = 'configs/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function main() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi'
        );

        $this->_plugins_css_admin[] = array(
            'folder' => 'bootstrap-fileinput/css',
            'name' => 'fileinput'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'bootstrap-fileinput/js',
            'name' => 'fileinput.min'
        );

        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'configs',
            'name' => 'admin-main-validate'
        );
        $this->set_modules();

        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));

        $post = $this->input->post();
        if (!empty($post)) {
            $data = array(
                'site_name' => $this->input->post('site_name'),
                'site_address' => $this->input->post('site_address'),
				'watermark_opacity' => $this->input->post('watermark_opacity'),
                'analytics_UA_code' => $this->input->post('analytics_UA_code'),
                'title_seo' => $this->input->post('title_seo'),
                'site_description' => $this->input->post('site_description'),
                'site_keywords' => $this->input->post('site_keywords'),
                'other_seo' => $this->input->post('other_seo'),
                'h1_seo' => $this->input->post('h1_seo'),
                'display_copyright_developer' => ($this->input->post('display_copyright_developer') ? 1 : 0),
            );
            $this->update_systems($data);

            /*
             * upload favicon
             */
            $input_name_favicon = 'favicon';
            $this->_upload_image_systems($input_name_favicon, $this->_logo, array('allowed_types' => 'ico'));

            /*
             * upload logo image
             */
            $input_name_site_logo = 'site_logo';
            $this->_upload_image_systems($input_name_site_logo, $this->_logo);

			/*
             * upload watermark image
             */
            $input_name_watermark_image = 'watermark_image';
            $this->_upload_image_systems($input_name_watermark_image, $this->_logo);

            /*
			$watermark_image = $this->get_config_value('watermark_image');
            $file = $this->_logo . $watermark_image;
            $newfile = 'ckeditor/kcfinder/images/logo.png';
            modules::run('files/copy_file', $file, $newfile);
			*/
            
            /*
             * upload sitemap
             */
            $input_name_sitemap = 'sitemap';
            modules::run('files/upload_systems', $input_name_sitemap, '/', array('allowed_types' => 'xml'));

            /*
             * upload robots
             */
            $input_name_robots = 'robots';
            modules::run('files/upload_systems', $input_name_robots, '/', array('allowed_types' => 'txt'));

            $notify_type = 'success';
            $notify_content = 'Đã lưu cấu hình site!';
            $this->set_notify_admin($notify_type, $notify_content);
            
            redirect(current_url());
        }

        $this->_data['breadcrumbs_module_func'] = 'Cấu hình site';
        $this->_breadcrumbs_admin[] = array(
            'url' => 'settings',
            'name' => $this->_data['breadcrumbs_module_func']
        );
        $this->set_breadcrumbs_admin();

        $this->_data['title'] = 'Cấu hình site - ' . $this->_data['title'];
        $configs = $this->M_configs->get_configs();
        $this->_data['configs'] = $this->set_configs($configs);
        $this->_data['main_content'] = 'configs/view_page_main';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_popup() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi'
        );

        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'configs',
            'name' => 'admin-main-validate'
        );
        $this->set_modules();

        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));

        $post = $this->input->post();
        if (!empty($post)) {
            $data = array(
                'popup_title' => $this->input->post('popup_title'),
                'popup_content' => $this->input->post('popup_content'),
                'popup_status' => $this->input->post('popup_status') ? 1 : 0,
            );
            $this->update_systems($data);

            $notify_type = 'success';
            $notify_content = 'Đã lưu cấu hình thông báo!';
            $this->set_notify_admin($notify_type, $notify_content);
            
            redirect(current_url());
        }

        $this->_data['breadcrumbs_module_func'] = 'Cấu hình thông báo';
        $this->_breadcrumbs_admin[] = array(
            'url' => 'settings',
            'name' => $this->_data['breadcrumbs_module_func']
        );
        $this->set_breadcrumbs_admin();

        $this->_data['title'] = 'Cấu hình thông báo - ' . $this->_data['title'];
        $configs = $this->M_configs->get_configs();
        $this->_data['configs'] = $this->set_configs($configs);
        $this->_data['main_content'] = 'configs/view_page_popup';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function get($config_name) {
        return $this->M_configs->get($config_name);
    }

    function get_config_value($config_name) {
        $data = $this->get($config_name);
        return isset($data['config_value']) ? $data['config_value'] : '';
    }

    function set_configs($data) {
        $configs = array();
        foreach ($data as $value) {
            $configs[$value['config_name']] = $value['config_value'];
        }
        return $configs;
    }

    function update_systems($data) {
        foreach ($data as $key => $value) {
            $this->M_configs->update($key, array('config_value' => $value));
        }
    }

    function _upload_image_systems($input_name = 'watermark_image', $upload_path = '', $options = array('allowed_types' => 'gif|jpg|png|jpeg')) {
        $config_value = $this->get_config_value($input_name);
        $info = modules::run('files/index', $input_name, $upload_path, $options);
        if (isset($info['uploads'])) {
            $upload_images = $info['uploads']; // thông tin ảnh upload
            if ($_FILES[$input_name]['size'] != 0) {
                foreach ($upload_images as $value) {
                    $this->update_systems(array($input_name => $value['file_name']));
                }
                @unlink(FCPATH . $upload_path . $config_value);
            }
        }
    }

}

/* End of file configs.php */
/* Location: ./application/modules/configs/controllers/configs.php */