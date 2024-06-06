<?php

include(dirname(__FILE__) . '/../config.php');
include(dirname(__FILE__) . '/../function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;
    $id = $data['kriteria_master_id'];
    $data = $data['alternative'];
    $kriteria_id = getKriteriaId($id);

    $alternative_id = null;
    
    foreach ($data as $value) {
        $insert_alternative = insert('alternative', [
            'kriteria_id' => $kriteria_id['id'],
            'nama' => $value['nama']
        ]);

        $alternative_id = $conn->insert_id;
        
        foreach ($value as $key => $nilai) {
            $isKode = getKriteriaIdByKode($id,$key);
            if($isKode){
                $insert_nilai = insert('alternative_nilai', [
                    'alternative_id' => $alternative_id,
                    'kriteria_id' => $isKode['id'],
                    'nilai' => $nilai
                ]);
            }
            
        }
    }
    

    header('Content-Type: application/json');
    echo json_encode($data);
}