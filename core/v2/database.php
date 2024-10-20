<?php
function db_connect() {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db_name = 'stockopname';

    $mysqli = new mysqli($host, $user, $pass, $db_name);

    if ($mysqli->connect_error) {
        die('Koneksi gagal: ' . $mysqli->connect_error);
    }

    return $mysqli;
}
