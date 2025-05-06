<?php
class M_users extends CI_Model {

    public $_table_name = 'users';
    public $_primary_key = 'userid';

    function __construst() {
        parent::__construst();
    }

    private function generate_where($args) {
        if (isset($args['search'])) {
            $this->db->group_start();
            $this->db->like($this->_table_name . ".full_name", $args['search']);
            $this->db->or_like($this->_table_name . ".phone", $args['search']);
            $this->db->group_end();
        }

        if (isset($args['q'])) {
            $this->db->group_start();
            $this->db->like($this->_table_name . ".full_name", $args['q']);
            $this->db->or_like($this->_table_name . ".username", $args['q']);
            $this->db->or_like($this->_table_name . ".phone", $args['q']);
            $this->db->or_like($this->_table_name . ".email", $args['q']);
            $this->db->group_end();
        }
        if (isset($args['in_id'])) {
            $this->db->where_in($this->_table_name . ".userid", $args['in_id']);
        }
        if (isset($args['not_in_id'])) {
            $this->db->where_not_in($this->_table_name . ".userid", $args['not_in_id']);
        }
        if (isset($args['active'])) {
            $this->db->where($this->_table_name . ".active", $args['active']);
        }
        if (isset($args['role'])) {
            $this->db->where($this->_table_name . ".role", $args['role']);
        }
        if (isset($args['in_role'])) {
            $this->db->where_in($this->_table_name . ".role", $args['in_role']);
        }
        if (isset($args['username'])) {
            $this->db->where($this->_table_name . '.username', $args['username']);
        }
        if (isset($args['refer_key'])) {
            $this->db->where($this->_table_name . '.refer_key', $args['refer_key']);
        }
        if (isset($args['referred_by'])) {
            $this->db->where($this->_table_name . '.referred_by', $args['referred_by']);
        }
        if (isset($args['referred_status'])) {
            $this->db->where($this->_table_name . '.referred_status', $args['referred_status']);
        }
        if (isset($args['group_id'])) {
            $this->db->where('groups_users.group_id', $args['group_id']);
        }
        if (isset($args['admin_group_id'])) {
            $this->db->where('groups_users.group_id <=', $args['admin_group_id']);
        }
        if (isset($args['admin_userid'])) {
            $this->db->where($this->_table_name . '.userid !=', $args['admin_userid']);
        }
		if (isset($args['in_group_id'])) {
            $this->db->where_in('groups_users.group_id', $args['in_group_id']);
        }

        if (isset($args['last_order_date'])) {
            $this->db->group_start();
            $this->db->where($this->_table_name . ".last_order_date <", $args['last_order_date']);
            $this->db->group_end();
        }

        // if (isset($args['last_login_start'])) {
        //     $this->db->group_start();
        //     $this->db->where($this->_table_name . ".last_login <", $args['last_login_start']);
        //     $this->db->group_end();
        // }
    }

    private function generate_order_by($args) {
        $allow_sort = array("DESC", "ASC", "RANDOM");

        if (isset($args['order_by']) && is_array($args['order_by']) && !empty($args['order_by'])) {
            foreach ($args['order_by'] as $key => $value) {
                $sort = in_array($value, $allow_sort) ? $value : "DESC";
                $this->db->order_by($key, $sort);
            }
        }
    }

    public function gets($args, $perpage = 5, $offset = -1) {
        if (isset($args['select_fields'])) {
            $this->db->select($this->_table_name . '.*,' . 'users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key, users_parent.phone as parent_phone');
        }else{
            $this->db->select($this->_table_name . '.*,' . 'groups_users.group_id, users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key, users_parent.phone as parent_phone');
        }
        $this->db->from($this->_table_name);
        if (!isset($args['select_fields'])) {
            $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
            $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        }
        $this->db->join('users as users_parent', 'users_parent.userid = ' .  $this->_table_name . '.referred_by', 'left');

        $this->generate_where($args);
        $this->generate_order_by($args);
        if ($offset >= 0) {
            $this->db->limit($perpage, $offset);
        }
        $query = $this->db->get();
        // echo $this->db->last_query(); die;

        return $query->result_array();
    }

    public function counts($args) {
        $this->db->select($this->_table_name . '.userid');
        $this->db->from($this->_table_name);
        if (!isset($args['select_fields'])) {
            $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
            $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        }
        $this->db->join('users as users_parent', 'users_parent.userid = ' .  $this->_table_name . '.referred_by', 'left');

        $this->generate_where($args);

        $query = $this->db->get();
        // echo $this->db->last_query(); die;

        return $query->num_rows();
    }

    public function get($id) {
        $this->db->select($this->_table_name . '.*,' . 'groups_users.group_id, users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key');
		$this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('users as users_parent', 'users_parent.userid = ' .  $this->_table_name . '.referred_by', 'left');
        $this->db->where($this->_table_name . '.userid', $id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_by($args) {
        $this->db->select($this->_table_name . '.*,' . 'groups_users.group_id, users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key');
        $this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('users as users_parent', 'users_parent.userid = ' .  $this->_table_name . '.referred_by', 'left');

        $this->generate_where($args);
        $query = $this->db->get();
        // if (isset($args['refer_key'])) {
        //     echo $this->db->last_query(); die;
        // }

        return $query->row_array();
    }

    public function counts_inactive($args) {
        /*$this->db->select($this->_table_name . '.userid, count(o.order_id) as quantity_order');
        // $this->db->select($this->_table_name . '.userid');
        // $this->db->select($this->_table_name . '.userid, ' . $this->_table_name . ".created");
        $this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('shops_order as o', 'o.order_customer_id = ' . $this->_table_name . '.userid', 'left');

        $this->generate_where($args);
        if (isset($args['interval_seconds'])) {
            $this->db->where($this->_table_name . ".created <", $args['interval_seconds']);
            // $this->db->where("o.created >=", $args['interval_seconds']);
        }
        $this->db->group_by($this->_table_name . '.userid');
        $this->db->having('count(o.order_id) = 0');*/

        $interval_seconds = 67 * 30 * 24 * 60 * 60;
        $current_timestamp = time();

        // Xây dựng truy vấn
        // $this->db->select('u.userid, u.full_name, u.created');
        $this->db->select('users.userid, users.full_name, users.regdate');
        // $this->db->select('users.userid');
        $this->db->from('users');
        $this->db->join('groups_users', 'groups_users.userid = users.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('users as users_parent', 'users_parent.userid = users.referred_by', 'left');
        $this->db->join('shops_order o', 'users.userid = o.order_customer_id', 'left');
        $this->generate_where($args);
        $this->db->group_by('users.userid')
                 ->having('MAX(o.order_date) <', $current_timestamp - $interval_seconds)
                 ->or_having('MAX(o.order_date) IS NULL')
                 ->having('users.regdate <=', $current_timestamp - $interval_seconds);

        /*$interval_seconds = 6 * 30 * 24 * 3600;
        $this->db->having("MAX(o.created) < UNIX_TIMESTAMP() - $interval_seconds");
        $this->db->or_having('MAX(o.order_date) IS NULL');
        $this->db->or_having($this->_table_name . ".created < UNIX_TIMESTAMP() - $interval_seconds");*/

        /*$this->db->select($this->_table_name . '.userid');
        $this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('shops_order as o', 'o.order_customer_id = ' . $this->_table_name . '.userid', 'left');

        $this->db->where("o.order_id IS NULL");
        $this->generate_where($args);
        if (isset($args['interval_seconds'])) {
            // $this->db->group_start();
            // $this->db->where("o.order_id IS NULL");
            $this->db->where("o.created >=", $args['interval_seconds']);
            // $this->db->where($this->_table_name . ".created <", $args['interval_seconds']);
            // $this->db->group_end();
        }*/

        $query = $this->db->get();
        // echo $this->db->last_query(); die;

        return $query->num_rows();
    }

    public function gets_inactive($args, $perpage = 5, $offset = -1) {        
        /*$this->db->select($this->_table_name . '.*,' . 'groups_users.group_id, users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key, users_parent.phone as parent_phone, count(o.order_id) as quantity_order');
        $this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('users as users_parent', 'users_parent.userid = ' .  $this->_table_name . '.referred_by', 'left');
        $this->db->join('shops_order as o', 'o.order_customer_id = ' . $this->_table_name . '.userid', 'left');

        $this->generate_where($args);
        if (isset($args['interval_seconds'])) {
            $this->db->where($this->_table_name . ".created <", $args['interval_seconds']);
            // $this->db->where("o.created >=", $args['interval_seconds']);
        }
        $this->db->group_by($this->_table_name . '.userid');
        $this->db->having('count(o.order_id) = 0 ');
        $this->generate_order_by($args);*/

        $interval_seconds = 6 * 30 * 24 * 60 * 60;
        $current_timestamp = time();

        // Xây dựng truy vấn
        // $this->db->select('users.*, ' . 'groups_users.group_id, users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key, users_parent.phone as parent_phone, count(o.order_id) as quantity_order');
        $this->db->select('users.userid, users.role, users.referred_by, users.full_name, users.username, users.phone, users.regdate, users.created, users.last_login, users.active, groups_users.group_id, users_parent.full_name as parent_full_name, users_parent.username as parent_username, users_parent.refer_key as parent_refer_key, users_parent.phone as parent_phone, count(o.order_id) as quantity_order');
        $this->db->from('users');
        $this->db->join('groups_users', 'groups_users.userid = users.userid', 'left');
        $this->db->join('groups', 'groups_users.group_id = ' . 'groups.group_id', 'left');
        $this->db->join('users as users_parent', 'users_parent.userid = users.referred_by', 'left');
        $this->db->join('shops_order o', 'users.userid = o.order_customer_id', 'left');
        $this->generate_where($args);
        $this->db->group_by('users.userid')
                 ->having('MAX(o.order_date) <', $current_timestamp - $interval_seconds)
                 ->or_having('MAX(o.order_date) IS NULL')
                 ->having('users.regdate <=', $current_timestamp - $interval_seconds);


        // $this->db->having('count(o.order_id) = 0 ');

        if ($offset >= 0) {
            $this->db->limit($perpage, $offset);
        }
        $query = $this->db->get();
        // echo $this->db->last_query(); die;

        return $query->result_array();
    }

    function gets_short() {
        $this->db->select("userid");
        $this->db->from($this->_table_name);
        $query = $this->db->get();

        return $query->result_array();
    }

    function gets_F1($user_id = 0) {
        $this->db->select("userid");
        $this->db->from($this->_table_name);
        $this->db->where('referred_by', $user_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    function gets_ref($args) {
        $this->db->select("userid, CONCAT((full_name), (' - '), (userid)) AS full_name", FALSE);
        $this->db->from($this->_table_name);

        $this->generate_where($args);
        $this->generate_order_by($args);
        $query = $this->db->get();

        return $query->result_array();
    }

    function gets_customers($args = array()) {
        $this->db->select("userid, CONCAT((full_name), (' - '), (phone)) AS full_name", FALSE);
        $this->db->from($this->_table_name);

        $this->generate_where($args);
        $this->generate_order_by($args);
        $query = $this->db->get();

        return $query->result_array();
    }

    function gets_short_multiple() {
        $this->db->select("userid, referred_by");
        $this->db->from($this->_table_name);
        $query = $this->db->get();

        return $query->result_array();
    }

    function search($args) {
        // $this->db->select('userid as id, CONCAT(full_name," - ", phone) as text');
        $this->db->select('userid as id, full_name as name');
        $this->db->from($this->_table_name);

        $this->generate_where($args);
        $this->generate_order_by($args);
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_in_groups_data($group_id = 0) {
        $records = $this->db->select($this->_table_name . '.*,' . 'group_id')
                ->where('group_id', $group_id)
                ->from($this->_table_name)
                ->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left')
                ->get()
                ->result_array();
        return $records;
    }

    public function get_info($id) {
        $this->db->select($this->_table_name . '.*,' . 'banker.name as bank_name');
        $this->db->from($this->_table_name);
        $this->db->join('banker', 'banker.id = ' . $this->_table_name . '.banker_id', 'left');
        $this->db->where($this->_table_name . '.userid', $id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function checkUser($data = array()) {
        if (!is_array($data) || empty($data)) {
            return NULL;
        }
        $this->db->select($this->_table_name . '.*,' . 'group_id');
        $this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');
        $this->db->where('oauth_provider', $data['oauth_provider']);
        $this->db->where('oauth_uid', $data['oauth_uid']);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return NULL;
        }
    }

    function validate_current_password($username, $current_password) {
        $this->db->where('username', $username);
        $this->db->where('password', $current_password);

        $query = $this->db->get($this->_table_name);

        return ($query->num_rows() == 1 ? TRUE : FALSE);
    }

    function validate_login($username, $password, $is_admin = FALSE) {
        $this->db->select($this->_table_name . '.*,' . 'group_id');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $this->db->where('active', 1);
        if ($is_admin) {
            // $this->db->where('group_id >', 3);
            $this->db->where_in('role', $this->config->item('role_access_admin'));
        }
        $this->db->from($this->_table_name);
        $this->db->join('groups_users', 'groups_users.userid = ' . $this->_table_name . '.userid', 'left');

        $query = $this->db->get();
        if ($is_admin) {
            // echo $this->db->last_query(); die;
        }

        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    function get_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get($this->_table_name);
        return $query->row_array();
    }

    function get_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get($this->_table_name);
        return $query->row_array();
    }

    function get_by_activation_key($username, $code) {
		$this->db->where('username', $username);
        $this->db->where('activation_key', $code);
        $query = $this->db->get($this->_table_name);
        return $query->row_array();
    }

    function add($data = array()) {
        if (empty($data)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $data);

        return isset($query) ? $this->db->insert_id() : 0;
    }

    function update($id, $data) {
        if (empty($data)) {
            return FALSE;
        }
        $this->db->where('userid', $id);
        $query = $this->db->update($this->_table_name, $data);

        return isset($query) ? true : false;
    }

    function update_inactive($in_id = array()) {
        if(!(is_array($in_id) && !empty($in_id))){
            return false;
        }
        $data = array(
            'active' => 0
        );
        $this->db->where_in($this->_table_name . ".userid", $in_id);
        $query = $this->db->update($this->_table_name, $data);

        return isset($query) ? true : false;
    }

    function delete($userid = 0) {
        $this->db->where('userid', $userid);
        $query = $this->db->delete($this->_table_name);

        return isset($query) ? true : false;
    }

    function check_current_password_availablity($username, $current_password) {
        if ((trim($current_password) == '') || (trim($username) == '')) {
            return false;
        }

        $this->db->select();
        $this->db->where('username', $username);
        $this->db->where('password', $current_password);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($query->num_rows() != 1) {
            return false;
        } else {
            return true;
        }
    }

    function check_current_username_availablity($username, $userid = 0) {
        if (trim($username) == '') {
            return false;
        }

        $this->db->select();
        if ($userid != 0) {
            $this->db->where('userid', $userid);
            $this->db->or_where('username', $username);
        } else {
            $this->db->where('username', $username);
        }

        $this->db->from($this->_table_name);

        $query = $this->db->get();
        if ($userid != 0) {
            if ($query->num_rows() == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($query->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    function check_current_email_availablity($email, $userid = 0) {
        if (trim($email) == '') {
            return false;
        }

        $this->db->select();
        if ($userid != 0) {
            $this->db->where('userid', $userid);
            $this->db->or_where('email', $email);
        } else {
            $this->db->where('email', $email);
        }

        $this->db->from($this->_table_name);

        $query = $this->db->get();

        /*
         * SELECT * FROM `users` WHERE userid=1 OR email='lenhan10th@gmail.com'
         * 1 là đúng ngược lại là sai
         */

        if ($userid != 0) {
            if ($query->num_rows() == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($query->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    function check_current_identity_number_card_availablity($identity_number_card, $userid = 0) {
        if (trim($identity_number_card) == '') {
            return false;
        }

        $this->db->select();
        if ($userid != 0) {
            $this->db->where('userid', $userid);
            $this->db->or_where('identity_number_card', $identity_number_card);
        } else {
            $this->db->where('identity_number_card', $identity_number_card);
        }

        $this->db->from($this->_table_name);

        $query = $this->db->get();
        if ($userid != 0) {
            if ($query->num_rows() == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($query->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    function check_username_availablity($username) {
        if (trim($username) == '') {
            return false;
        }

        $this->db->select();
        $this->db->where('username', $username);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function check_email_availablity($email, $username = '') {
        if (trim($email) == '') {
            return false;
        }

        $this->db->select();
        if (trim($username) != '') {
            $this->db->where('username', $username);
            $this->db->or_where('email', $email);
        } else {
            $this->db->where('email', $email);
        }

        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if (trim($username) != '') {
            if ($query->num_rows() == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($query->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    function check_refer_key_availablity($refer_key = '') {
        if (trim($refer_key) == '') {
            return false;
        }

        $this->db->select();
        $this->db->where('refer_key', $refer_key);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

	function check_phone_availablity($phone) {
        if (trim($phone) == '') {
            return false;
        }

        $this->db->select();
        $this->db->where('phone', $phone);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function check_identity_number_card_availablity($identity_number_card) {
        if (trim($identity_number_card) == '') {
            return false;
        }

        $this->db->select();
        $this->db->where('identity_number_card', $identity_number_card);
        $this->db->from($this->_table_name);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file M_users.php */
/* Location: ./application/modules/users/models/M_users.php */