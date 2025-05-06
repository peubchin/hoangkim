<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Postmeta extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('posts/m_postmeta', 'M_postmeta');
    }

    function get($post_id, $meta_key) {
        $this->load->model('posts/m_postmeta', 'M_postmeta');
        return $this->M_postmeta->get($post_id, $meta_key);
    }

    function gets($post_id = 0) {
        $this->load->model('posts/m_postmeta', 'M_postmeta');
        return $this->M_postmeta->gets($post_id);
    }
    
    function gets_in_key_and_value($meta_key, $meta_value) {
        $this->load->model('posts/m_postmeta', 'M_postmeta');
        return $this->M_postmeta->gets_in_key_and_value($meta_key, $meta_value);
    }

    function add($args) {
        $this->load->model('posts/m_postmeta', 'M_postmeta');
        return $this->M_postmeta->add($args);
    }

    function update($post_id, $meta_key, $meta_value) {
        return $this->M_postmeta->update($post_id, $meta_key, $meta_value);
    }

    function delete($post_id, $meta_key, $meta_value) {
        return $this->M_postmeta->delete($post_id, $meta_key, $meta_value);
    }

    function delete_multiple($post_id) {
        return $this->M_postmeta->delete_multiple($post_id);
    }

}

/* End of file Postmeta.php */
/* Location: ./application/modules/pages/controllers/Postmeta.php */    