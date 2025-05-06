<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Users_config extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('users/m_users_config', 'M_users_config');
        $this->_data['breadcrumbs_module_name'] = 'Tài khoản';
    }

    function admin_siteterms() {
        $this->redirect_admin();
        $post = $this->input->post();

        if ($this->input->post('config')) {//update
            $data = array(
                'content' => $this->input->post('siteterms'),
                'edit_time' => time()
            );
            if ($this->M_users_config->update($this->input->post('config'), $data)) {
                $notify_type = 'success';
                $notify_content = 'Dữ liệu đã lưu thay đổi!';
            } else {
                $notify_type = 'danger';
                $notify_content = 'Dữ liệu chưa lưu thay đổi!';
            }
            $this->set_notify_admin($notify_type, $notify_content);
        }
        $this->_data['config'] = $this->M_users_config->get_configs_single('siteterms');

        $_SESSION['KCFINDER'] = array();
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = '/uploads';
        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));

        $this->_data['breadcrumbs_module_func'] = 'Nội quy site';
        $this->_data['title'] = 'Nội quy site - ' . $this->config->item('site_name');
        $this->_data['main_content'] = 'users/admin/view_page_siteterms';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_config() {
        $this->redirect_admin();

        $post = $this->input->post();
        if (!empty($post)) {
            $edit_time = time();
            $data = array(
                'avatar_width' => array(
                    'content' => $this->input->post('avatar_width'),
                    'edit_time' => $edit_time
                ),
                'avatar_height' => array(
                    'content' => $this->input->post('avatar_height'),
                    'edit_time' => $edit_time
                ),
                'active_after_reg' => array(
                    'content' => $this->input->post('active_after_reg') ? 1 : 0,
                    'edit_time' => $edit_time
                )
            );
            $this->update_systems_users($data);

            $notify_type = 'success';
            $notify_content = 'Đã lưu cấu hình module tài khoản!';
            $this->set_notify_admin($notify_type, $notify_content);
        }
        
        $configs = $this->set_configs_users($this->M_users_config->get_configs());
        $this->_data['configs'] = $configs;

        $_SESSION['KCFINDER'] = array();
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = '/uploads';
        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));

        $this->_data['breadcrumbs_module_func'] = 'Cấu hình module tài khoản';
        $this->_data['title'] = 'Cấu hình module tài khoản - ' . $this->config->item('site_name');
        $this->_data['main_content'] = 'users/admin/view_page_config';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }
    
    function get_configs_users() {
        return $this->set_configs_users($this->M_users_config->get_configs());
    }

    function get_configs_single_users($content = '') {
        return $this->M_users_config->get_configs_single($content);
    }

    private function set_configs_users($data) {
        $configs = array();
        foreach ($data as $value) {
            $configs[$value['config']] = $value['content'];
        }
        return $configs;
    }

    private function update_systems_users($datas) {
        foreach ($datas as $config => $data) {
            $this->M_users_config->update($config, $data);
        }
    }

}

/* End of file users_config.php */
/* Location: ./application/modules/users/controllers/users_config.php */