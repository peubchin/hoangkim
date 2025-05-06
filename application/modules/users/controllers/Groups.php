<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Groups extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('users/m_groups', 'M_groups');
        $this->load->model('users/m_groups_users', 'M_groups_users');
        $this->_data['breadcrumbs_module_name'] = 'Tài khoản';
    }

    function gets() {
        return $this->M_groups->gets();
    }
    
    function get($group_id) {
        return $this->M_groups->get($group_id);
    }

    function index() {
        $this->redirect_admin();
        $userlist = (int) $this->input->get('userlist');
        if ($userlist != 0) {
            if ($userlist < 4 || $userlist > 6) {
                redirect(current_url());
            }
            $this->_data['rows'] = modules::run('users/gets_in_group', array('group_id' => $userlist));
            $group = $this->get($userlist);
            $group_title = $group['title'];
            $this->_data['breadcrumbs_module_func'] = 'Danh sách thành viên của nhóm "' . $group_title . '"';
            $this->_data['title'] = 'Danh sách thành viên của nhóm "' . $group_title . '"';
            $this->_data['main_content'] = 'users/admin/view_page_userlist';
        } else {
            $all_data = $this->gets();
            $rows = array();
            foreach ($all_data as $value) {
                $value['num_users'] = Modules::run('users/groups_users/num_users_in_group', $value['group_id']);
                $rows[] = $value;
            }

            $this->_data['rows'] = $rows;

            $this->_data['breadcrumbs_module_func'] = 'Nhóm thành viên';
            $this->_breadcrumbs_admin[] = array(
                'url' => 'users',
                'name' => 'Tài khoản'
            );
            $this->_breadcrumbs_admin[] = array(
                'url' => 'users/groups',
                'name' => 'Nhóm thành viên'
            );
            $this->set_breadcrumbs_admin();

            $this->_data['title'] = 'Nhóm thành viên';
            $this->_data['main_content'] = 'users/admin/view_page_groups';
        }
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

}

/* End of file groups.php */
/* Location: ./application/modules/users/controllers/groups.php */