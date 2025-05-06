<?php

class M_contact extends CI_Model {

    public $_table_name = 'contact';

    public function __construst() {
        parent::__construst();
    }

    private function generate_where($args) {
        if (isset($args['title'])) {
            $this->db->where($this->_table_name . ".title", $args['title']);
        }
        if (isset($args['is_view'])) {
            $this->db->where($this->_table_name . ".is_view", $args['is_view']);
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
        $this->db->where($this->_table_name . '.id', $id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function add($data = array()) {
        if (empty($data)) {
            return TRUE;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return (isset($query)) ? TRUE : FALSE;
    }
    
    public function update($id, $data) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('id', $id);
        $query = $this->db->update($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_contact.php */
/* Location: ./application/modules/contact/models/m_contact.php */