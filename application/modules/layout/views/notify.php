<?php
if ($this->session->userdata('notify_current')) {
    $session_data = $this->session->userdata('notify_current');
    $notify_type = $session_data['notify_type'];
    $notify_content = $session_data['notify_content'];
    ?>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($notify_type == 'success') {
                echo show_alert_success($notify_content);
            } elseif ($notify_type == 'danger') {
                echo show_alert_danger($notify_content);
            } else {
                echo show_alert_warning($notify_content);
            }
            ?>
        </div>
    </div>
    <?php
    $this->session->unset_userdata('notify_current');
}