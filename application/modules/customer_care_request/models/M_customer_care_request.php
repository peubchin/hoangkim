<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_customer_care_request extends CI_Model {

	public $_table_name = 'customer_care_request';

	public function __construst() {
		parent::__construst();
	}

	private function generate_select() {
        $this->db->select($this->_table_name . '.*');
    }

    private function generate_from() {
        $this->db->from($this->_table_name);
    }

	private function generate_where($args) {
        if (isset($args['q'])) {
			$this->db->group_start();
			$this->db->like($this->_table_name . ".name", $args['q']);
			$this->db->group_end();
		}

		if (isset($args['id'])) {
            $this->db->where($this->_table_name . ".id", $args['id']);
        }

        if (isset($args['in_id'])) {
            $this->db->where_in($this->_table_name . ".id", $args['in_id']);
        }

        if (isset($args['not_in_id'])) {
            $this->db->where_not_in($this->_table_name . ".id", $args['not_in_id']);
        }
    }

    private function generate_order_by($args) {
        $allow_sort = array("DESC", "ASC", "RANDOM");

        if (isset($args['order_by']) && is_array($args['order_by']) && !empty($args['order_by'])) {
            foreach ($args['order_by'] as $key => $value) {
                $sort = in_array($value, $allow_sort) ? $value : "DESC";
                $this->db->order_by($this->_table_name . ".$key", $sort);
            }
        }
    }

    public function gets($args = array(), $limit = 0, $offset = 0) {
        $this->generate_select();
        $this->generate_from();
        $this->generate_where($args);
        $this->generate_order_by($args);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        // echo $this->db->last_query(); die;

        return $query->result_array();
    }

	public function counts($args = array()) {
		$this->db->select($this->_table_name . ".id");
		$this->generate_from();
        $this->generate_where($args);

		$query = $this->db->get();

		return $query->num_rows();
	}

	public function get($id = 0) {
		$this->generate_select();
        $this->generate_from();
		$this->db->where($this->_table_name . '.id', $id);

		$query = $this->db->get();

		return $query->row_array();
	}

	public function get_by($args = array()) {
        $this->generate_select();
        $this->generate_from();
        $this->generate_where($args);
        $this->generate_order_by($args);
        $query = $this->db->get();

        return $query->row_array();
    }

	public function insert($args = array()) {
		if (empty($args)) {
			return 0;
		}
		$query = $this->db->insert($this->_table_name, $args);

		$insert_id = $this->db->insert_id();

		return isset($query) ? $insert_id : 0;
	}

	public function insert_batch($data = array()) {
        if(!(is_array($data) && !empty($data))){
            return false;
        }
        
        $query = $this->db->insert_batch($this->_table_name, $data);

        return isset($query) ? true : false;
    }

	public function update($id, $args) {
		if (empty($args)) {
			return false;
		}
		$this->db->where('id', $id);
		$query = $this->db->update($this->_table_name, $args);

		return isset($query) ? true : false;
	}

	public function update_by($args = array(), $data = array()) {
        if (empty($args) || empty($data)) {
            return false;
        }
        $this->generate_where($args);
        $query = $this->db->update($this->_table_name, $data);

        return isset($query) ? true : false;
    }

    public function update_batch($data = array()) {
        if(!(is_array($data) && !empty($data))){
            return false;
        }
        
        $query = $this->db->update_batch($this->_table_name, $data, 'id');

        return isset($query) ? true : false;
    }

	public function delete($id) {
		$this->db->where('id', $id);
		$query = $this->db->delete($this->_table_name);

		return isset($query) ? true : false;
	}

	public function delete_by($args = array()) {
        if (empty($args)) {
            return false;
        }
        $this->generate_where($args);
        $query = $this->db->delete($this->_table_name);

        return isset($query) ? true : false;
    }

}

/* End of file M_customer_care_request.php */
/* Location: ./application/modules/customer_care_request/models/M_customer_care_request.php */