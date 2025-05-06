<?php

class M_postcat extends CI_Model {

    public $_table_name = 'postcat';

    function __construst() {
        parent::__construst();
    }

    private function generate_where($args) {
        if (isset($args['position'])) {
            $this->db->where($this->_table_name . ".position", $args['position']);
        }
        
        if (isset($args['inhome'])) {
            $this->db->where($this->_table_name . ".inhome", $args['inhome']);
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
        //$this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');

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
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->where('id', $id);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_data_all() {
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_data_in_id($id) {
        $this->db->select();
        $this->db->from($this->_table_name);
        if ($id != 0) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();

        return $query->row_array();
    }
    
    public function get_in_alias($alias) {
        $this->db->select($this->_table_name . '.*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.alias', $alias);
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_max_order($parent = 0) {
        $this->db->select('order');
        $this->db->where('parent', $parent);
        $this->db->order_by('order', 'DESC');
        $query = $this->db->get($this->_table_name, 1);
        return $query->row_array();
    }

    function get_child($parent) {
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->where('parent', $parent);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function check_alias_availablity($alias, $id = 0) {
        if (trim($alias) == '') {
            return FALSE;
        }

        $this->db->select();
        if ($id != 0) {
            $this->db->where('id', $id);
            $this->db->or_where('alias', $alias);
        } else {
            $this->db->where('alias', $alias);
        }

        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($id != 0) {            
            if ($query->num_rows() == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($query->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    function add($data = array()) {
        if (empty($data)) {
            return false;
        }
        $this->db->insert($this->_table_name, $data);
        $id = $this->db->insert_id();

        return (isset($id)) ? true : false;
    }

    function update($id, $data) {
        if (empty($data)) {
            return false;
        }
        $this->db->where('id', $id);
        $query = $this->db->update($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}
