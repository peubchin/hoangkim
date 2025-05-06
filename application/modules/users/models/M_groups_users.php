<?php

class M_groups_users extends CI_Model {

    private $_table_name = 'groups_users';

    function __construst() {
        parent::__construst();
    }
    
    private function generate_where($args) {
        if (isset($args['group_id'])) {
            $this->db->where($this->_table_name . ".group_id", $args['group_id']);
        }
    }

    private function generate_order_by($args) {
        $allow_sort = array("DESC", "ASC");

        if (isset($args['order_by']) && is_array($args['order_by']) && !empty($args['order_by'])) {
            foreach ($args['order_by'] as $key => $value) {
                $sort = in_array($value, $allow_sort) ? $value : "DESC";
                $this->db->order_by($key, $sort);
            }
        }
    }

    public function gets($args, $perpage = 5, $offset = -1) {
        $this->db->select($this->_table_name . '.*');
        $this->db->from($this->_table_name);

        $this->generate_where($args);
        $this->generate_order_by($args);
        if ($offset >= 0) {
            $this->db->limit($perpage, $offset);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    public function counts($args) {
        $this->db->select($this->_table_name . '.*');
        $this->db->from($this->_table_name);

        $this->generate_where($args);

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get($id) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.userid', $id);

        $query = $this->db->get();

        return $query->row_array();
    }
    
    function get_group_id($userid = 0) {
        $this->db->select('group_id');
        $this->db->where('userid', $userid);
        $query = $this->db->get($this->_table_name, 1);
        return $query->row_array();
    }

    public function add($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    public function update($userid, $data) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('userid', $userid);
        $query = $this->db->update($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    public function delete($userid) {
        $this->db->where('userid', $userid);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}
