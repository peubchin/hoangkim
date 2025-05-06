<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
     function run($module = '', $group = '') {
           // Kiểm tra tham số truyền thêm có phải là 1 object
           (is_object($module)) AND $this->CI = & $module;
           return parent::run($group);
     }
}