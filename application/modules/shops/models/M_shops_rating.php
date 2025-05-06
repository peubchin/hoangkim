<?php

class M_shops_rating extends CI_Model {

    private $_table_name = 'shops_ratings';

    function __construst() {
        parent::__construst();
    }

    function get_rating_product_id($product_id) {
        $this->db->select($this->_table_name . '.*');
        $this->db->from($this->_table_name);
        $this->db->where($this->_table_name . '.product_id', $product_id);
        $query = $this->db->get();        

        return $query->result_array();
    }

    function add($data = array()) {
        if (empty($data)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $data);
        $insert_id = $this->db->insert_id();
        return (isset($query)) ? $insert_id : 0;
    }

    function update($id, $data) {
        if (empty($data)) {
            return FALSE;
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

/* End of file m_shops_rows.php */
/* Location: ./application/modules/shops/models/m_shops_rows.php */