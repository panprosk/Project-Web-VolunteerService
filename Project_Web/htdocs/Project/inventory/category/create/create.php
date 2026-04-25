<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 3)
        return;

    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }
    $myinsert = "INSERT INTO category VALUES (null, '" . $_POST['name'] . "')";
    $mysql_link->query($myinsert);

    echo "Success!";

    $mysql_link->close();