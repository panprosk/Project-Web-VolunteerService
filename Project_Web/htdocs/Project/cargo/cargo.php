<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 2)
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

            $myquery = "SELECT * FROM detail";
            $details = $mysql_link->query($myquery);

            $myquery = "SELECT * FROM category";
            $categories = $mysql_link->query($myquery);

            $myquery = "SELECT * FROM inventory WHERE in_ve_id IS NULL OR in_ve_id = " . $_SESSION['session_id'];
            $inventory = $mysql_link->query($myquery);
            $mysql_link->close();

            $myobject = '{"items": [' . json_encode($items->fetch_array());
            while($row = $items->fetch_array()){
                $myobject .= ', ' . json_encode($row);
            }
            $myobject .= '], "details": [' . json_encode($details->fetch_array());
            while($row = $details->fetch_array()){
                $myobject .= ', ' . json_encode($row);
            }
            $myobject .= '], "categories": [' . json_encode($categories->fetch_array());
            while($row = $categories->fetch_array()){
                $myobject .= ', ' . json_encode($row);
            }
            $myobject .= '], "inventory": [' . json_encode($inventory->fetch_array());
            while($row = $inventory->fetch_array()){
                $myobject .= ', ' . json_encode($row);
            }
            $myobject .= ']}';
            echo $myobject;
            break;

        case 2:
            $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
            if ($mysql_link->connect_error) {
                die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
            }
            $myupdate = "UPDATE inventory SET in_quantity = in_quantity + 1 WHERE in_it_id = " . $_POST['id'] . " AND in_ve_id = " . $_SESSION['session_id'];
            $mysql_link->query($myupdate);
            if($mysql_link->affected_rows == 1){
                echo "Success";
            }
            else
                echo "Fail";
            $mysql_link->close();
            break;

        case 3:
            $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
            if ($mysql_link->connect_error) {
                die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
            }
            $myupdate = "UPDATE inventory SET in_quantity = in_quantity - 1 WHERE in_it_id = " . $_POST['id'] . " AND in_ve_id = " . $_SESSION['session_id'] . " AND in_quantity > 0";
            $mysql_link->query($myupdate);
            if($mysql_link->affected_rows == 1){
                echo "Success";
            }
            else
                echo "Fail";
            $mysql_link->close();
            break;
    }