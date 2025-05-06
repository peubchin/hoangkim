<?php

class M_postmeta extends CI_Model {

    private $_table_name = 'postmeta';

    public function __construst() {
        parent::__construst();
    }
    
    public function get($post_id, $meta_key) {
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->where('post_id', $post_id);
        $this->db->where('meta_key', $meta_key);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function gets($post_id) {
        $this->db->select();
        $this->db->from($this->_table_name);
        $this->db->where('post_id', $post_id);
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

    public function update($post_id, $meta_key, $meta_value) {
        $this->db->where('post_id', $post_id);
        $this->db->where('meta_key', $meta_key);
        $query = $this->db->update($this->_table_name, array('meta_value' => $meta_value));

        return (isset($query)) ? true : false;
    }

    public function delete($post_id, $meta_key, $meta_value) {
        $this->db->where('post_id', $post_id);
        $this->db->where($meta_key, $meta_value);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

    public function delete_multiple($post_id) {
        $this->db->where('post_id', $post_id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_postmeta.php */
/* Location: ./application/modules/pages/models/m_postmeta.php */