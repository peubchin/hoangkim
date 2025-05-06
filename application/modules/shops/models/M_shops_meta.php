<?php

class M_shops_meta extends CI_Model {

    private $_table_name = 'shops_meta';

    public function __construst() {
        parent::__construst();
    }
    
    public function get($product_id, $meta_key) {
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->where('product_id', $product_id);
        $this->db->where('meta_key', $meta_key);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function gets($product_id) {
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function add($args = array()) {
        if (empty($args)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $args);

        $insert_id = $this->db->insert_id();

        return (isset($query)) ? $insert_id : 0;
    }

    public function update($product_id, $meta_key, $meta_value) {
        $this->db->where('product_id', $product_id);
        $this->db->where('meta_key', $meta_key);
        $query = $this->db->update($this->_table_name, array('meta_value' => $meta_value));

        return (isset($query)) ? true : false;
    }

    public function delete($product_id, $meta_key, $meta_value) {
        $this->db->where('product_id', $product_id);
        $this->db->where($meta_key, $meta_value);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

    public function delete_multiple($product_id) {
        $this->db->where('product_id', $product_id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_shops_meta.php */
/* Location: ./application/modules/shops/models/m_shops_meta.php */