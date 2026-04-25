<?php
    error_reporting(0);
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 3)
        return;

    $json = json_decode($_POST['data']);

//Iterate through the json object

    if (is_null($json)) {
        echo "You didn't select any items.";
        return;
    }


    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }

    $myinsert = "INSERT INTO announcement VALUES (null, null)";
    $mysql_link->query($myinsert);

    $current_id = $mysql_link->insert_id;

    $myinsert = "INSERT INTO requests VALUES ";
    foreach($json->ids as $id){
        $myinsert .= "(" . $current_id . ", " . $id . "),";
    }
    $myinsert = rtrim($myinsert,",");
    $mysql_link->query($myinsert);
    $mysql_link->close();

    echo "Success!";