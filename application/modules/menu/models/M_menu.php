<?php

class M_menu extends CI_Model {

    public $_table_name = 'menu';

    function __construst() {
        parent::__construst();
    }
    
    private function generate_where($args) {
        if (isset($args['position'])) {
            $this->db->where($this->_table_name . ".position", $args['position']);
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
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');

        $this->generate_where($args);
        $this->generate_order_by($args);
        if ($offset >= 0) {
            $this->db->limit($perpage, $offset);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    public function counts($args) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');

        $this->generate_where($args);
 
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get($id) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');
        if ($id != 0) {
            $this->db->where($this->_table_name . '.id', $id);
        }
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_data_in_id($id) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');
        if ($id != 0) {
            $this->db->where($this->_table_name . '.id', $id);
        }
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_max_order($parent = 0, $position) {
        $this->db->select('order');
        $this->db->where('parent', $parent);
        $this->db->where($this->_table_name . '.position', $position);
        $this->db->order_by('order', 'DESC');
        $query = $this->db->get($this->_table_name, 1);
        return $query->row_array();
    }

    function get_data_position($position) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name, type');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');
        $this->db->where($this->_table_name . '.position', $position);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_child($parent) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');
        $this->db->where('parent', $parent);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_child_position($parent, $position) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');
        $this->db->where('parent', $parent);
        $this->db->where($this->_table_name . '.position', $position);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
	
	function get_child_position_menu_top($parent, $position) {
        $this->db->select($this->_table_name . '.*,' . 'menu_type.id as menu_type_id, menu_type.name as menu_type_name, type');
        $this->db->from($this->_table_name);
        $this->db->join('menu_type', 'menu_type.id = ' . $this->_table_name . '.menu_type', 'left');
        $this->db->where('parent', $parent);
        $this->db->where($this->_table_name . '.position', $position);
        $this->db->order_by('parent', 'ASC');
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function add($data = array()) {
        if (empty($data)) {
            return 0;
        }
        $this->db->insert($this->_table_name, $data);
        $id = $this->db->insert_id();

        return (isset($id)) ? $id : 0;
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
