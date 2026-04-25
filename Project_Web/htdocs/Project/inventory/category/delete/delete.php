<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 3)
        return;

    if($_POST['id'] == 0){
        echo 'Select a category!';
    }
    else{
        $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
        if ($mysql_link->connect_error) {
            die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
        }
        $mydelete = "DELETE FROM category WHERE ca_id = '" . $_POST['id'] . "'";
        $mysql_link->query($mydelete);
        if($mysql_link->affected_rows == 0){
            echo "Category not found!";
        } elseif($mysql_link->affected_rows == 1){
            echo "Success!";
        } else
            echo "Something went terribly wrong :(";
        $mysql_link->close();
    }