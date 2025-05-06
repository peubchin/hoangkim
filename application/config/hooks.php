<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['display_override'] = array(
    'class' => 'Minifyhtml',
    'function' => 'output',
    'filename' => 'Minifyhtml.php',
    'filepath' => 'hooks',
    'params' => array()
);
$hook['post_controller_constructor'] = function() {
    $CI = &get_instance();
    $configs_sms = array();
    $appConfigOptions = $CI->M_configs->get_configs();
    if(is_array($appConfigOptions) && !empty($appConfigOptions)){
        foreach($appConfigOptions as $appConfigOption){
        	if($appConfigOption['config_name'] == 'sms_api_key'){
        		$configs_sms['api_key'] = $appConfigOption['config_value'];
        	}
        	if($appConfigOption['config_name'] == 'sms_secret_key'){
        		$configs_sms['secret_key'] = $appConfigOption['config_value'];
        	}
        	if($appConfigOption['config_name'] == 'sms_expired'){
        		$configs_sms['expired'] = $appConfigOption['config_value'];
        	}
        	if($appConfigOption['config_name'] == 'sms_brandname'){
        		$configs_sms['brandname'] = $appConfigOption['config_value'];
        	}
            //$CI->config->set_item($appConfigOption['config_name'], $appConfigOption['config_value']);
        }
    }
    if(!empty($configs_sms)){
	    $CI->config->set_item('sms', $configs_sms);
	}
};