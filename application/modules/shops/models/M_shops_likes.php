<?php

class M_shops_likes extends CI_Model {

    public $_table_name = 'shops_likes';

    function __construst() {
        parent::__construst();
    }

    function get_all_data() {
        $this->db->select('*');
        $query = $this->db->get($this->_table_name);

        return $query->result_array();
    }

    function get_rows_likes_in_users($user_id) {
        if ($user_id == 0) {
            return false;
        }
        $this->db->select('product_id');
        $this->db->where('user_id', $user_id);
        $this->db->from($this->_table_name);
        $query = $this->db->get();
        return $query->result_array();
    }

    function check_like_availablity($user_id, $product_id) {
        if ($user_id == 0 || $product_id == 0) {
            return false;
        }

        $this->db->select();
        $this->db->where('user_id', $user_id);
        $this->db->where('product_id', $product_id);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function num_like_userid($user_id = 0) {
        if ($user_id == 0) {
            return 0;
        }

        $this->db->select();
        $this->db->where('user_id', $user_id);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        return $query->num_rows();
    }
    
    function get_num_likes_all() {
        $this->db->select('product_id');
        $this->db->from($this->_table_name);
        $this->db->group_by('product_id');

        $query = $this->db->get();

        return $query->num_rows();
    }

    function add($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return (isset($query)) ? TRUE : FALSE;
    }
    
    function delete_in_users($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }
    
    function delete_in_rows($product_id) {
        $this->db->where('product_id', $product_id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_shops_likes.php */
/* Location: ./application/modules/shops/models/m_shops_likes.php */