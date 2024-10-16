<?php
function is_logged_in() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/login');
        exit();
    }
}

function format_date($date) {
    return date('d-m-Y', strtotime($date));
}
