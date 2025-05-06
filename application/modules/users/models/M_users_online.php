<?php

class M_users_online extends CI_Model {

    public $_table_name = 'online_users';

    function __construst() {
        parent::__construst();
    }

    function get_user_online($session) {
        $this->db->select();
        $this->db->from($this->_table_name);
        if ($session != null) {
            $this->db->where('session', $session);
        }
        $query = $this->db->get();
        
        echo $this->db->last_query();
        
        return $query->result_array();

        return $query->num_rows();
    }

    function add($data = array()) {
        if (empty($data)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return (isset($query)) ? $this->db->insert_id() : 0;
    }

    function update($session_id, $data) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('session', $id);
        $query = $this->db->update($this->_table_name, $data);

        return (isset($query)) ? true : false;
    }

    function delete($time) {
        $this->db->where('time <', $time);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }
}

/* End of file m_users_online.php */
/* Location: ./application/modules/users/models/m_users_online.php */