<?php

include(dirname(__FILE__) . '/../config.php');
include(dirname(__FILE__) . '/../function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;

    $insert = insert('kriteria_master', [
        'nama' => $data['nama']
    ]);

    header('Content-Type: application/json');
    echo json_encode($insert);
}