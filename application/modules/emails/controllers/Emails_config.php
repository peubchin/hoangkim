<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Emails_config extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('emails/m_emails_config', 'M_emails_config');
        $this->_data['breadcrumbs_module_name'] = 'Email';
        
        $this->_breadcrumbs_admin[] = array(
            'url' => 'emails',
            'name' => 'Email'
        );
        $this->set_breadcrumbs_admin(); 
    }

    function admin_config() {
        $this->_initialize_admin();
        $this->redirect_admin();
        
        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );

        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'emails',
            'name' => 'admin-configs-validate'
        );
        $this->set_modules();
        
        $this->load->library('encrypt');

        $post = $this->input->post();
        if (!empty($post)) {            
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('protocol', 'Protocol', 'trim|required|xss_clean');
            $this->form_validation->set_rules('smtp_host', 'SMTP Host', 'trim|required|xss_clean');
            $this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|required|xss_clean');
            $this->form_validation->set_rules('smtp_user', 'SMTP User', 'trim|required|xss_clean');

            if ($this->form_validation->run($this)) {                
                $edit_time = time();
                $data = array(
                    'protocol' => array(
                        'content' => $this->input->post('protocol'),
                        'edit_time' => $edit_time
                    ),
                    'smtp_host' => array(
                        'content' => $this->input->post('smtp_host'),
                        'edit_time' => $edit_time
                    ),
                    'smtp_port' => array(
                        'content' => $this->input->post('smtp_port'),
                        'edit_time' => $edit_time
                    ),
                    'smtp_user' => array(
                        'content' => $this->input->post('smtp_user'),
                        'edit_time' => $edit_time
                    )
                );

                if (trim($this->input->post('smtp_pass')) != '') {
                    $data['smtp_pass'] = array(
                        'content' => $this->encrypt->encode($this->input->post('smtp_pass')),
                        'edit_time' => $edit_time
                    );
                }

                $this->update_systems_emails($data);

                $notify_type = 'success';
                $notify_content = 'Email configuration saved!';
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url('emails'));
            }
        }

        $configs = $this->get_configs_emails();
        $this->_data['configs'] = $configs;
        
        $this->_data['breadcrumbs_module_func'] = 'Email settings';
        $this->_breadcrumbs_admin[] = array(
            'url' => 'emails',
            'name' => $this->_data['breadcrumbs_module_func']
        );
        $this->set_breadcrumbs_admin();
        
        $this->_data['title'] = 'Email settings - ' . $this->_data['title'] . ' Admin';
        $this->_data['main_content'] = 'emails/admin/view_page_config';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function get_configs_emails() {
        return $this->set_configs_emails($this->M_emails_config->get_configs());
    }

    function get_configs_single_emails($content = '') {
        return $this->M_emails_config->get_configs_single($content);
    }

    function set_configs_emails($data) {
        $configs = array();
        foreach ($data as $value) {
            $configs[$value['config']] = $value['content'];
        }
        return $configs;
    }

    function update_systems_emails($datas) {
        foreach ($datas as $config => $data) {
            $this->M_emails_config->update($config, $data);
        }
    }

}

/* End of file emails_config.php */
/* Location: ./application/modules/emails/controllers/emails_config.php */