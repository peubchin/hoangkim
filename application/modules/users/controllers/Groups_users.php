<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Groups_users extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('users/m_groups_users', 'M_groups_users');
        $this->_data['breadcrumbs_module_name'] = 'Tài khoản';
    }
    
    function default_args() {
        $order_by = array(
            'group_id' => 'ASC',
        );
        $args = array();
        //$args['order_by'] = $order_by;

        return $args;
    }

    function counts($args) {
        $this->load->model('users/m_groups_users', 'M_groups_users');
        return $this->M_groups_users->counts($args);
    }

    function get($id) {
        $this->load->model('users/m_groups_users', 'M_groups_users');
        return $this->M_groups_users->get($id);
    }

    function get_group_id($userid = 0) {
        return $this->M_groups_users->get_group_id($userid);
    }

    function add($data) {
        return $this->M_groups_users->add($data);
    }

    function update($id, $data) {
        return $this->M_groups_users->update($id, $data);
    }

    function delete($userid = 0) {
        return $this->M_groups_users->delete($userid);
    }

    function num_users_in_group($group_id = 0) {
        $args['group_id'] = $group_id;
        return $this->counts($args);
        //return $this->M_groups_users->num_users_in_group($group_id);
    }

}

/* End of file groups_users.php */
/* Location: ./application/modules/users/controllers/groups_users.php */