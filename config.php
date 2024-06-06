<?php

$db_config = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'spk'
);

$conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);

?>