<?php

class M_info extends CI_Model {

    public $_table_name = 'info';

    public function __construst() {
        parent::__construst();
    }

    private function generate_where($args) {
        if (isset($args['title'])) {
            $this->db->where($this->_table_name . ".title", $args['title']);
        }

        if (isset($args['image'])) {
            $this->db->where($this->_table_name . ".image", $args['image']);
        }

        if (isset($args['post_type'])) {
            $this->db->where($this->_table_name . ".post_type", $args['post_type']);
        }

        if (isset($args['position'])) {
            $this->db->where($this->_table_name . ".position", $args['position']);
        }

        if (isset($args['status'])) {
            $this->db->where($this->_table_name . ".status", $args['status']);
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
        $status = isset($args['status']) ? $args['status'] : 1;
        $this->db->select($this->_table_name . '.*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.status', $status);

        $this->generate_where($args);
        $this->generate_order_by($args);
        if ($offset >= 0) {
            $this->db->limit($perpage, $offset);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    public function counts($args) {
        $status = isset($args['status']) ? $args['status'] : 1;
        $this->db->select($this->_table_name . '.*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.status', $status);

        $this->generate_where($args);

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get($id) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.id', $id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_info_position($post_type = 'banner', $position = 'none', $single = false) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.post_type', $post_type);
        $this->db->where($this->_table_name . ".position", $position);

        $query = $this->db->get();

        if ($single == FALSE) {
            return $query->result_array();
        } else {
            return $query->row_array();
        }
    }

    public function add($args = array()) {
        if (empty($args)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $args);

        $insert_id = $this->db->insert_id();

        return (isset($query)) ? $insert_id : 0;
    }

    public function update($id, $args) {
        if (empty($args)) {
            return false;
        }
        $this->db->where('id', $id);
        $query = $this->db->update($this->_table_name, $args);

        return (isset($query)) ? true : false;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_info.php */
/* Location: ./application/modules/info/models/m_info.php */