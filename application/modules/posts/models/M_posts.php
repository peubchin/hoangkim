<?php

class M_posts extends CI_Model {

    public $_table_name = 'posts';
    private $_post_type = 'post';
    private $_postmeta = array('homeimgfile', 'homeimgalt', 'post_cat_id');

    public function __construst() {
        parent::__construst();
    }

    private function generate_where($args) {
      if (isset($args['q'])) {
        $this->db->group_start();
        $this->db->like($this->_table_name . ".title", $args['q']);
        $this->db->or_like($this->_table_name . ".hometext", $args['q']);
        $this->db->group_end();
      }

      if (isset($args['in_id'])) {
        $this->db->where_in($this->_table_name . ".id", $args['in_id']);
      }

      if (isset($args['not_in_id'])) {
        $this->db->where_not_in($this->_table_name . ".id", $args['not_in_id']);
      }

      if (isset($args['cat_alias'])) {
        $this->db->where("pc.alias", $args['cat_alias']);
      }

      if (isset($args['in_cat_id'])) {
        $this->db->where_in("pc.id", $args['in_cat_id']);
      }

      if (isset($args['status'])) {
        $this->db->where($this->_table_name . ".status", $args['status']);
      }

      if (isset($args['inhome'])) {
        $this->db->where($this->_table_name . ".inhome", $args['inhome']);
      }

      if (isset($args['is_news'])) {
        $this->db->where($this->_table_name . ".is_news", $args['is_news']);
      }

      if (isset($args['is_new'])) {
        $this->db->where($this->_table_name . ".is_new", $args['is_new']);
      }

      if (isset($args['is_typical_event'])) {
        $this->db->where($this->_table_name . ".is_typical_event", $args['is_typical_event']);
      }

      if (isset($args['is_events_has_organized'])) {
        $this->db->where($this->_table_name . ".is_events_has_organized", $args['is_events_has_organized']);
      }
    }

    private function generate_group_by($args) {
        if (isset($args['group_by']) && is_array($args['group_by']) && !empty($args['group_by'])) {
            foreach ($args['group_by'] as $value) {
                $this->db->group_by($value);
            }
        }
    }

    private function generate_order_by($args) {
        $allow_sort = array("DESC", "ASC");

        if (isset($args['order_by']) && is_array($args['order_by']) && !empty($args['order_by'])) {
            foreach ($args['order_by'] as $key => $value) {
                $sort = in_array($value, $allow_sort) ? $value : "DESC";
                $this->db->order_by($key, $sort);
            }
        }
    }

    public function gets($args, $perpage = 5, $offset = -1) {
        $post_status = isset($args['post_status']) ? $args['post_status'] : 'publish';
        $post_type = isset($args['post_type']) ? $args['post_type'] : $this->_post_type;
        $select = "";
        $select_arr = array();
        $where = "";
        $where_arr = array();
        $i = 1;
        foreach ($args['fields'] as $value) {
            $select_arr[] = "mt$i.meta_value as $value";
            $where_arr[] = "(mt$i.meta_key = '$value')";
            $i++;
        }

        $select .= implode(", ", $select_arr);
        $where .= implode(" OR ", $where_arr);
        $this->db->select($this->_table_name . '.*, us.full_name, pc.alias AS cat_alias,' . $select);
        $this->db->from($this->_table_name);
        $this->db->join('users AS us', $this->_table_name . '.admin_id = us.userid', 'left');

        $i = 1;
        foreach ($args['fields'] as $value) {
            $this->db->join('postmeta AS mt' . $i, $this->_table_name . '.id = mt' . $i . ".post_id AND mt$i.meta_key='$value'", 'left');
            $i++;
        }
        $this->db->join('postmeta AS pma', $this->_table_name . ".id = pma.post_id AND pma.meta_key='post_cat_id'", 'left');
        $this->db->join('postcat AS pc', 'pma.meta_value = pc.id', 'left');

        if ($post_type != 'all') {
            $this->db->where($this->_table_name . '.post_type', $post_type);
        }
        $this->db->where($this->_table_name . '.status', $post_status);
        $this->db->where("($where)");

        $this->generate_where($args);
        $this->generate_order_by($args);
        $this->generate_group_by($args);
        if ($offset >= 0) {
            $this->db->limit($perpage, $offset);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();

        return $query->result_array();
    }

    public function counts($args) {
        $post_status = isset($args['post_status']) ? $args['post_status'] : 'publish';
        $post_type = isset($args['post_type']) ? $args['post_type'] : $this->_post_type;
        $select = "";
        $select_arr = array();
        $where = "";
        $where_arr = array();
        $i = 1;
        foreach ($args['fields'] as $value) {
            $select_arr[] = "mt$i.meta_value as $value";
            $where_arr[] = "(mt$i.meta_key = '$value')";
            $i++;
        }

        $select .= implode(", ", $select_arr);
        $where .= implode(" OR ", $where_arr);
        $this->db->select($this->_table_name . '.*,' . $select);
        $this->db->from($this->_table_name);
        $i = 1;
        foreach ($args['fields'] as $value) {
            $this->db->join('postmeta AS mt' . $i, $this->_table_name . '.id = mt' . $i . ".post_id AND mt$i.meta_key='$value'", 'left');
            $i++;
        }
        $this->db->join('postmeta AS pma', $this->_table_name . ".id = pma.post_id AND pma.meta_key='post_cat_id'", 'left');
        $this->db->join('postcat AS pc', 'pma.meta_value = pc.id', 'left');

        if ($post_type != 'all') {
            $this->db->where($this->_table_name . '.post_type', $post_type);
        }
        $this->db->where($this->_table_name . '.status', $post_status);
        $this->db->where("($where)");

        $this->generate_where($args);
        $this->generate_group_by($args);

        $query = $this->db->get();
        //echo $this->db->last_query();

        return $query->num_rows();
    }

    /*
    public function get_taxonomy($post_id, $taxonomy = 'category', $alias = '') {
        $sql = "SELECT wt.*
            FROM posts p
            LEFT JOIN term_relationships r ON r.object_id=p.id
            LEFT JOIN term_taxonomy t ON t.term_taxonomy_id = r.term_taxonomy_id
            LEFT JOIN terms wt on wt.term_id = t.term_id
            WHERE p.id=$post_id AND t.taxonomy=\"$taxonomy\"";

        if (trim($alias) != '') {
            $sql .= " AND p.alias='" . $alias . "'";
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }
    */

    public function get($id, $alias = '') {
        $args['fields'] = $this->_postmeta;
        $select = "";
        $select_arr = array();
        $where = "";
        $where_arr = array();
        $i = 1;
        foreach ($args['fields'] as $value) {
            $select_arr[] = "mt$i.meta_value as $value";
            $where_arr[] = "(mt$i.meta_key = '$value')";
            $i++;
        }

        $select .= implode(", ", $select_arr);
        $where .= implode(" OR ", $where_arr);
        $this->db->select($this->_table_name . '.*, us.full_name,' . $select);
        $this->db->from($this->_table_name);
        $this->db->join('users AS us', $this->_table_name . '.admin_id = us.userid', 'left');
        $i = 1;
        foreach ($args['fields'] as $value) {
            $this->db->join('postmeta AS mt' . $i, $this->_table_name . '.id = mt' . $i . ".post_id AND mt$i.meta_key='$value'", 'left');
            $i++;
        }

        $this->db->where($this->_table_name . '.post_type', $this->_post_type);
        $this->db->where($this->_table_name . '.id', $id);
        $this->db->where("($where)");

        $this->generate_where($args);
        $this->generate_group_by($args);
        if (trim($alias) != '') {
            $this->db->where($this->_table_name . '.alias', $alias);
        }

        $query = $this->db->get();

        return $query->row_array();
    }

    public function add($args = array()) {
        if (empty($args)) {
            return 0;
        }
        $query = $this->db->insert($this->_table_name, $args);

        $insert_id = $this->db->insert_id();

        return (isset($query)) ? $insert_id : 0;
    }

    public function update($id, $args) {
        if (empty($args)) {
            return false;
        }
        $this->db->where('id', $id);
        $query = $this->db->update($this->_table_name, $args);

        return (isset($query)) ? true : false;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->_table_name);

        return (isset($query)) ? true : false;
    }

}

/* End of file m_posts.php */
/* Location: ./application/modules/posts/models/m_posts.php */
