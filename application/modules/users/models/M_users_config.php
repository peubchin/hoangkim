<?php

class M_users_config extends CI_Model {

    public $_table_name = 'users_config';

    function __construst() {
        parent::__construst();
    }

    function get_configs($config = '') {
        $this->db->select('*');
        if ($config != '') {
            $this->db->where('config', $config);
        }
        $query = $this->db->get($this->_table_name);

        return $query->result_array();
    }

    function get_configs_single($config = '') {
        $this->db->select('*');
        $this->db->where('config', $config);
        $query = $this->db->get($this->_table_name);

        return $query->row_array();
    }

    function update($config, $data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('config', $config);
        $query = $this->db->update($this->_table_name, $data);
        
        return (isset($query)) ? true : false;
    }

}

/* End of file m_users_config.php */
/* Location: ./application/modules/users/models/m_users_config.php */