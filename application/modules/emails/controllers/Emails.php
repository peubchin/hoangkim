<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Emails extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->library('encrypt');
        $this->_data['breadcrumbs_module_name'] = 'Email';
    }

    function send_mail($data_sendmail = null) {
        if ($data_sendmail == NULL) {
            return FALSE;
        } else {
            $this->load->library('encrypt');
            $this->load->module('emails/emails_config');
            $configs_emails = $this->emails_config->get_configs_emails();

            // Configure email library
            $config['protocol'] = $configs_emails['protocol'];
            $config['smtp_host'] = $configs_emails['smtp_host'];
            $config['smtp_port'] = $configs_emails['smtp_port'];
            $config['smtp_user'] = $configs_emails['smtp_user'];
            $config['smtp_pass'] = $this->encrypt->decode($configs_emails['smtp_pass']);

            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = '\r\n';
            $config['wordwrap'] = TRUE;

            // Load email library and passing configured values to email library
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");

            // Sender email address
            $this->email->from($configs_emails['smtp_user'], $data_sendmail['sender_name']);
            // Receiver email address
            $this->email->to($data_sendmail["receiver_email"]);
            $this->email->reply_to($data_sendmail["sender_email"], $data_sendmail['sender_name']);
            // Subject of email
            $this->email->subject($data_sendmail["subject"]);
            // Message in email
            $this->email->message($data_sendmail["message"]);

            if ($this->email->send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}

/* End of file emails.php */
/* Location: ./application/modules/emails/controllers/emails.php */