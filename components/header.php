<?php
session_start();
include(dirname(__FILE__) . '/../config.php');
include(dirname(__FILE__) . '/../function.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    body {
        font-family: 'Poppins', 'Arial', sans-serif;
        background-color: #f1f1f1;
    }

    .box-table {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 10px;
    }

    #table_ranking table tbody tr:nth-child() {
        background-color: green;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 10px;
    }

    table thead tr {
        background-color: #353434;
        color: white;
    }

    table thead tr th {
        padding: 10px;
    }

    table thead tr th:first-child {
        border-radius: 10px 0 0 10px;
    }

    table thead tr th:last-child {
        border-radius: 0 10px 10px 0;
    }

    table tbody tr td {
        padding: 10px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    #table_ranking tbody tr:nth-child(1) {
        background-color: #42f581;
    }

    #table_ranking tbody tr:nth-child(2) {
        background-color: #426ff5;
    }

    #table_ranking tbody tr:nth-child(3) {
        background-color: #e3f542;
    }
    </style>
</head>

<body>