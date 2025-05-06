<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Error404 extends MX_Controller 
{
    function __construct() 
    {
        parent::__construct(); 
    } 

    function index() 
    { 
        $this->output->set_status_header('404'); 
        $this->load->view('view_index');
    } 
}
/* End of file error404.php */
/* Location: ./application/modules/errors/controllers/error404.php */