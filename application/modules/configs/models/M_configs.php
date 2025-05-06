<?php
class M_configs extends CI_Model {

    public $_table_name = 'configs';

    function __construst() {
        parent::__construst();
    }

    function _parse($data = array()) {
        $configs = array();
        if (is_array($data) && !empty($data)) {
            foreach ($data as $value) {
                $configs[$value['config_name']] = $value['config_value'];
            }
        }
        return $configs;
    }

    function gets($names = null) {
        $this->db->select();
        $this->db->from($this->_table_name);
        if (is_array($names) && !empty($names)) {
            $this->db->where_in('config_name', $names);
        }

        $query = $this->db->get();

        $rows = $query->result_array();
        return $this->_parse($rows);
    }

    function get_configs() {
        $this->db->select('*');
        $query = $this->db->get($this->_table_name);

        return $query->result_array();
    }
    
    function get($config_name = '') {
        $this->db->select('*');
        $this->db->where('config_name', $config_name);
        $query = $this->db->get($this->_table_name);

        return $query->row_array();
    }

    function update($config_name, $data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('config_name', $config_name);
        $query = $this->db->update($this->_table_name, $data);

        return isset($query) ? true : false;
    }

    function update_batch($data = array()) {
        if(!(is_array($data) && !empty($data))){
            return false;
        }

        $items = array();
        foreach($data as $config_name => $config_value){
            $items[] = array(
                'config_name' => $config_name,
                'config_value' => $config_value
            );
        }
        $query = $this->db->update_batch($this->_table_name, $items, 'config_name');

        return isset($query) ? true : false;
    }

    function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->_table_name);

        return isset($query) ? true : false;
    }

}

/* End of file M_configs.php */
/* Location: ./application/modules/configs/models/M_configs.php */