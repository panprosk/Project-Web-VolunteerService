<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 2)
        return;


    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }

    $myquery = "SELECT ba_latitude, ba_longitude, ve_latitude, ve_longitude FROM base, vehicle WHERE ve_us_id =" . $_SESSION['session_id'];
    $base = $mysql_link->query($myquery)->fetch_array();

    echo json_encode($base);