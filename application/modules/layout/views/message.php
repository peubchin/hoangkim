<?php

if ($box['status'] === 'success') {
    echo show_alert_success($box['message']);
} elseif ($box['status'] === 'warning') {
    echo show_alert_warning($box['message']);
} else {
    echo show_alert_danger($box['message']);
}