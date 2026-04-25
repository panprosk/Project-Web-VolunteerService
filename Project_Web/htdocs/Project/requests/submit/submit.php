<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 1)
        return;

    switch($_POST['action']){
        case 1:
            $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
            if ($mysql_link->connect_error) {
                die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
            }
            $myquery = "SELECT * FROM item";
            $items = $mysql_link->query($myquery);
            if($items->num_rows == 0){
                echo "There are no items!";
                exit();
            }
            $first = $items->fetch_array();
            $itemnames = '["' . $first['it_name'] . ' (' . $first['it_id'] . ')"';
            $itemids = '[' . $first['it_id'];
            while($row = $items->fetch_array()){
                $itemnames .= ', "' . $row['it_name'] . ' (' . $row['it_id'] . ')"';
                $itemids .= ', ' . $row['it_id'];
            }
            $myobject = '{"itemnames": ' . $itemnames . '], "itemids": ' . $itemids . ']}';
            echo $myobject;
            break;

        case 2:
            $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
            if ($mysql_link->connect_error) {
                die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
            }
            if($_POST['quantity'] <= 0){
                echo "Enter positive quantity!";
                return;
            }
            $myinsert = "INSERT INTO task VALUES (null, 'Pending', " . $_POST['id'] . ", " . $_SESSION['session_id'] . ", null, null, null, " . $_POST['quantity'] . ", 1)";
            $mysql_link->query($myinsert);
            if($mysql_link->affected_rows == 1){
                echo "Success!";
            }
            else
                echo "Fail";
            $mysql_link->close();
            break;
    }