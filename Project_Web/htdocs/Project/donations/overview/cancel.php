<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 1)
        return;

    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }
    $mydelete = "DELETE FROM task WHERE ta_id = " . $_POST['id'] . " AND ta_ci_id = " . $_SESSION['session_id'];
    $mysql_link->query($mydelete);
    if ($mysql_link->affected_rows == 0) {
        echo "Donation not found!";
    } elseif ($mysql_link->affected_rows == 1) {
        echo "Success!";
    } else
        echo "Something went terribly wrong :(";
    $mysql_link->close();