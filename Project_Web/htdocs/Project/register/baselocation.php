<?php
    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }

    $myquery = "SELECT * FROM base";
    $base = $mysql_link->query($myquery)->fetch_array();

    echo json_encode($base);