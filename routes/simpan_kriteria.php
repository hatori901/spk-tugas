<?php

include(dirname(__FILE__) . '/../config.php');
include(dirname(__FILE__) . '/../function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;
    $id = $data['kriteria_master_id'];
    $data = $data['kriteria'];
    

    foreach ($data as $value) {
        $value['kriteria_master_id'] = $id;
        $insert = insert('kriteria', $value);
    }

    header('Content-Type: application/json');
    echo json_encode($insert);
}