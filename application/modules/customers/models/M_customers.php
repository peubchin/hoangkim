<?php

class M_customers extends CI_Model {

    public $_table_name = 'customers';

    function __construst() {
        parent::__construst();
    }

    private function generate_where($args) {
        if (isset($args['q'])) {
            $this->db->group_start();
            $this->db->like($this->_table_name . ".full_name", $args['q']);
            $this->db->or_like($this->_table_name . ".phone", $args['q']);
            $this->db->or_like($this->_table_name . ".email", $args['q']);
            $this->db->or_like($this->_table_name . ".address", $args['q']);
            $this->db->group_end();
        }

        if (isset($args['user_id'])) {
            $this->db->where($this->_table_name . ".user_id", $args['user_id']);
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
        $this->db->where($this->_table_name . '.customer_id', $id);

        $query = $this->db->get();

        return $query->row_array();
    }

    function add($data = array()) {
        if (empty($data)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return (isset($query)) ? $this->db->insert_id() : 0;
    }

    function update($customer_id, $data) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->update($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    function delete($customer_id = 0) {
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_customers.php */
/* Location: ./application/modules/customers/models/m_customers.php */