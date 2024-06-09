<?php


function select($table, $where = null, $join = null, $select = "*")
{
    global $conn;
    $query = "SELECT $select FROM $table";
    if ($join) {
        $query .= " $join";
    }
    if ($where) {
        $query .= " WHERE $where";
    }
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function insert($table, $data)
{
    global $conn;
    $fields = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $query = "INSERT INTO $table ($fields) VALUES ($values)";
    return $conn->query($query);
}

function update($table, $data, $where)
{
    global $conn;
    $set = "";
    foreach ($data as $key => $value) {
        $set .= "$key = '$value', ";
    }
    $set = rtrim($set, ", ");
    $query = "UPDATE $table SET $set WHERE $where";
    return $conn->query($query);
}

function delete($table, $where)
{
    global $conn;
    $query = "DELETE FROM $table WHERE $where";
    return $conn->query($query);
}

function getTotalWeightedProduct($data)
{
    $total_weighted_product = 0;
    foreach ($data as $key => $value) {
        $total_weighted_product += $value['bobot'];
    }
    return $total_weighted_product;
}

function getKriteriaId($id)
{
    global $conn;
    $query = "SELECT * FROM kriteria WHERE kriteria_master_id = $id LIMIT 1";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}


function getKriteriaIdByKode($id, $kode)
{
    global $conn;
    $query = "SELECT * FROM kriteria WHERE kriteria_master_id = $id AND kode = '$kode' LIMIT 1";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

function logout()
{
    session_destroy();
    header('Location: ../index.php');
}