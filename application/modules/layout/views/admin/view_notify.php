<?php
if ($this->session->userdata('notify_current_admin')) {
    $session_data = $this->session->userdata('notify_current_admin');
    $notify_type = $session_data['notify_type'];
    $notify_content = $session_data['notify_content'];
    if ($notify_type == 'success') {
        echo show_alert_success($notify_content);
    } elseif ($notify_type == 'danger') {
        echo show_alert_danger($notify_content);
    } else {
        echo show_alert_warning($notify_content);
    }
    $this->session->unset_userdata('notify_current_admin');
}