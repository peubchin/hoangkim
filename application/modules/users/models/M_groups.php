<?php

class M_groups extends CI_Model {

    private $_table_name = 'groups';

    function __construst() {
        parent::__construst();
    }

    function gets() {
        $this->db->select('*');
        $this->db->order_by('group_id ASC');
        $query = $this->db->get($this->_table_name);
        return $query->result_array();
    }

    function get($group_id = 0) {
        $this->db->select('*');
        $this->db->where('group_id', $group_id);
        $query = $this->db->get($this->_table_name);
        return $query->row_array();
    }

    function add($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    function update($id, $data) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('group_id', $id);
        $query = $this->db->update($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    function delete($id) {
        $this->db->where('group_id', $id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}
