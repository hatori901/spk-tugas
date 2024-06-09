<?php

include(dirname(__FILE__) . '/../config.php');
include(dirname(__FILE__) . '/../function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;

    $json['status'] = false;

    switch ($data['action']) {
        case 'add_hitung':
            $insert = simpan_hitung($data);
            if ($insert) {
                $json['status'] = true;
                $json['id'] = $conn->insert_id;
                $json['message'] = 'Data berhasil disimpan';
            }else{
                $json['message'] = 'Data gagal disimpan';
            }
            break;
        case 'edit_hitung':
            $update = update('kriteria_master', [
                'name' => $data['name'],
            ], 'id = ' . $data['id']);
            if ($update) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil disimpan';
            }else{
                $json['message'] = 'Data gagal disimpan';
            }
            break;
        case 'delete_hitung':
            $delete = delete_hitung($data);
            if ($delete) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil dihapus';
            }else{
                $json['message'] = 'Data gagal dihapus';
            }
            break;
        case 'simpan_kriteria':
            $id = $data['kriteria_master_id'];
            $data = $data['kriteria'];
            if($id == 0 || !$id){
                $create_master = insert('kriteria_master', [
                    'name' => 'Perhitungan Baru',
                ]);
                $id = $conn->insert_id;
            }
            $error = false;

            $kriteria = getKriteriaId($id);
            if($kriteria){
                // check is kode exist
                foreach ($data as $value) {
                    if (in_array($value['kode'], $kriteria)) {
                        $json['message'] = 'Terdapat kode yang sama!';
                        $error = true;
                        break;
                    }
                }

                // check is nama exist
                foreach ($data as $value) {
                    if (in_array($value['nama'], $kriteria)) {
                        $json['message'] = 'Terdapat nama yang sama!';
                        $error = true;
                        break;
                    }
                }
            }

            if(!$error){
                foreach ($data as $value) {
                    $value['kriteria_master_id'] = $id;
                    $insert = insert('kriteria', $value);
                }
                if ($insert) {
                    $json['status'] = true;
                    $json['message'] = 'Data berhasil disimpan';
                }else{
                    $json['message'] = 'Data gagal disimpan';
                }
            }
            break;
        case 'edit_kriteria':
            $update = update('kriteria', [
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'bobot' => $data['bobot'],
                'tipe' => $data['tipe'],
            ], 'id = ' . $data['id']);
            if ($update) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil disimpan';
            }else{
                $json['message'] = 'Data gagal disimpan';
            }
            break;
        case 'simpan_nilai_kriteria':
            $json['status'] = false;
            $json['data'] = $data;
            $insert = insert('alternative_nilai', [
                    'alternative_id' => $data['alternative_id'],
                    'kriteria_id' => $data['kriteria_id'],
                    'nilai' => $data['nilai']
                ]);
            if ($insert) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil disimpan';
            }else{
                $json['message'] = 'Data gagal disimpan';
            }
            break;
        case 'delete_kriteria':
            $delete = delete('kriteria', 'id = ' . $data['id']);
            if ($delete) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil dihapus';
            }else{
                $json['message'] = 'Data gagal dihapus';
            }
            break;
        case 'simpan_alternatif':
            $id = $data['kriteria_master_id'];
            $data = $data['alternative'];
            $kriteria_id = getKriteriaId($id);

            $alternative_id = null;

            
            foreach ($data as $value) {
                if(isset($value['name'])){
                    $insert_alternative = insert('alternative', [
                        'kriteria_id' => $kriteria_id['id'],
                        'nama' => $value['nama']
                    ]);
                }

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
            if ($insert_alternative) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil disimpan';
            }else{
                $json['message'] = 'Data gagal disimpan';
            }
            break;
        case 'edit_alternatif':
            $update = update('alternative', [
                'nama' => $data['nama'],
            ], 'id = ' . $data['id']);
            if ($update) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil disimpan';
            }else{
                $json['message'] = 'Data gagal disimpan';
            }
            break;
        case 'delete_alternatif':
            $delete = delete('alternative', 'id = ' . $data['id']);
            if ($delete) {
                $json['status'] = true;
                $json['message'] = 'Data berhasil dihapus';
            }else{
                $json['message'] = 'Data gagal dihapus';
            }
            break;
        default:
            $json['message'] = 'Action not found';
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($json);
}


function simpan_hitung($data)
{
    $insert = insert('kriteria_master', [
        'name' => $data['name'],
    ]);

    return $insert;
}

function delete_hitung($data)
{
    $insert = delete('kriteria_master', 'id = ' . $data['id']);

    return $insert;
}