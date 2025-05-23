<?php

class M_emails_config extends CI_Model {

    public $_table_name = 'emails_config';

    public function __construst() {
        parent::__construst();
    }

    public function get_configs($config = '') {
        $this->db->select('*');
        if ($config != '') {
            $this->db->where('config', $config);
        }
        $query = $this->db->get($this->_table_name);

        return $query->result_array();
    }

    public function get_configs_single($config = '') {
        $this->db->select('*');
        $this->db->where('config', $config);
        $query = $this->db->get($this->_table_name);

        return $query->row_array();
    }

    public function update($config, $data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('config', $config);
        $query = $this->db->update($this->_table_name, $data);
        
        return (isset($query)) ? true : false;
    }

}

/* End of file m_emails_config.php */
/* Location: ./application/modules/emails/models/m_emails_config.php */