<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] < 2)
        return;

    switch ($_SESSION['session_level']) {
        case 2:
            switch ($_POST['action']) {
                case 1:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myquery = "SELECT * FROM base";
                    $base = $mysql_link->query($myquery);

                    $myquery = "SELECT citizen_info.ci_fname, citizen_info.ci_lname, citizen_info.ci_phone, citizen_info.ci_latitude, citizen_info.ci_longitude, task.ta_issue_date, item.it_name, item.it_id, task.ta_quantity, task.ta_accept_date, rescuers.us_name, task.ta_re_id, task.ta_id
                            FROM task
                            INNER JOIN citizen_info ON task.ta_ci_id = citizen_info.ci_us_id
                            INNER JOIN item ON task.ta_it_id = item.it_id
                            LEFT JOIN users AS rescuers ON task.ta_re_id = rescuers.us_id
                            WHERE task.isRequest = 1 AND task.ta_status <> 'Completed' AND (task.ta_re_id = " . $_SESSION['session_id'] . " OR task.ta_re_id IS NULL)";
                    $requests = $mysql_link->query($myquery);

                    $myquery = "SELECT citizen_info.ci_fname, citizen_info.ci_lname, citizen_info.ci_phone, citizen_info.ci_latitude, citizen_info.ci_longitude, task.ta_issue_date, item.it_name, item.it_id, task.ta_quantity, task.ta_accept_date, rescuers.us_name, task.ta_re_id, task.ta_id
                            FROM task
                            INNER JOIN citizen_info ON task.ta_ci_id = citizen_info.ci_us_id
                            INNER JOIN item ON task.ta_it_id = item.it_id
                            LEFT JOIN users AS rescuers ON task.ta_re_id = rescuers.us_id
                            WHERE task.isRequest = 0 AND task.ta_status <> 'Completed' AND (task.ta_re_id = " . $_SESSION['session_id'] . " OR task.ta_re_id IS NULL)";
                    $donations = $mysql_link->query($myquery);

                    $myquery = "SELECT users.us_name, users.us_id, vehicle.ve_latitude, vehicle.ve_longitude
                            FROM vehicle
                            INNER JOIN users ON vehicle.ve_us_id = users.us_id
                            WHERE users.us_id = " . $_SESSION['session_id'];
                    $vehicles = $mysql_link->query($myquery);

                    $myquery = "SELECT *
                            FROM inventory
                            INNER JOIN item ON inventory.in_it_id = item.it_id
                            WHERE in_ve_id = " . $_SESSION['session_id'] . " AND in_quantity > 0";
                    $inventory = $mysql_link->query($myquery);


                    $mysql_link->close();

                    $myobject = '{"base": [' . json_encode($base->fetch_array(MYSQLI_ASSOC));
                    $myobject .= '], "requests": [' . json_encode($requests->fetch_array(MYSQLI_ASSOC));
                    while ($row = $requests->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "donations": [' . json_encode($donations->fetch_array(MYSQLI_ASSOC));
                    while ($row = $donations->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "vehicles": [' . json_encode($vehicles->fetch_array(MYSQLI_ASSOC));
                    while ($row = $vehicles->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "inventory": [' . json_encode($inventory->fetch_array(MYSQLI_ASSOC));
                    while ($row = $inventory->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "level":"' . $_SESSION['session_level'] . '"}';
                    echo $myobject;
                    break;

                case 2:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myupdate = "UPDATE vehicle SET ve_latitude = " . $_POST['lat'] . ", ve_longitude = " . $_POST['lng'] . " WHERE ve_us_id = " . $_SESSION['session_id'];
                    $mysql_link->query($myupdate);
                    if ($mysql_link->affected_rows == 1) {
                        echo "Success";
                    } else
                        echo "Fail";
                    $mysql_link->close();
                    break;

                case 3:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myselect = "SELECT * FROM task WHERE ta_status = 'Accepted' AND ta_re_id = " . $_SESSION['session_id'];
                    if($mysql_link->query($myselect)->num_rows == 4){
                        echo "You cannot accept more than 4 tasks at once!";
                        $mysql_link->close();
                        return;
                    }
                    $myupdate = "UPDATE task SET ta_status = 'Accepted', ta_re_id = " . $_SESSION['session_id'] . " WHERE ta_id = " . $_POST['id'];
                    $mysql_link->query($myupdate);
                    if ($mysql_link->affected_rows == 1) {
                        echo "Success!";
                    } else
                        echo "Fail";
                    $mysql_link->close();
                    break;

                case 4:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myselect = "SELECT * FROM task WHERE ta_id = " . $_POST['id'];
                    $targettask = $mysql_link->query($myselect)->fetch_array();
                    if($targettask['isRequest']){
                        $myselect = "SELECT * FROM inventory WHERE in_it_id = " . $targettask['ta_it_id'] . " AND in_ve_id = " . $_SESSION['session_id'];
                        $mycargo = $mysql_link->query($myselect)->fetch_array();
                        if($mycargo['in_quantity'] < $targettask['ta_quantity']){
                            echo "You do not have enough items to complete this task!";
                            $mysql_link->close();
                            return;
                        }
                    }
                    if($targettask['isRequest'])
                        $myupdate = "UPDATE inventory SET in_quantity = in_quantity - " . $targettask['ta_quantity'] . " WHERE in_ve_id = " . $_SESSION['session_id'] . " AND in_it_id = " . $targettask['ta_it_id'];
                    else
                        $myupdate = "UPDATE inventory SET in_quantity = in_quantity + " . $targettask['ta_quantity'] . " WHERE in_ve_id = " . $_SESSION['session_id'] . " AND in_it_id = " . $targettask['ta_it_id'];
                    $mysql_link->query($myupdate);
                    if ($mysql_link->affected_rows != 1){
                        echo "Fail";
                        $mysql_link->close();
                        return;
                    }

                    $myupdate = "UPDATE task SET ta_status = 'Completed' WHERE ta_id = " . $_POST['id'];
                    $mysql_link->query($myupdate);
                    if ($mysql_link->affected_rows == 1) {
                        echo "Success!";
                    } else
                        echo "Fail";
                    $mysql_link->close();
                    break;
                case 5:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }

                    $myupdate = "UPDATE task SET ta_status = 'Pending', ta_re_id = NULL WHERE ta_id = " . $_POST['id'];
                    $mysql_link->query($myupdate);
                    if ($mysql_link->affected_rows == 1) {
                        echo "Success!";
                    } else
                        echo "Fail";
                    $mysql_link->close();
                    break;
                case 6:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myselect = "SELECT * FROM inventory WHERE in_ve_id = " . $_SESSION['session_id'];
                    $items = $mysql_link->query($myselect);
                    while($cargo = $items->fetch_array()){
                        $myupdate = "UPDATE inventory SET in_quantity = in_quantity + " . $cargo['in_quantity'] . " WHERE in_it_id = " . $cargo['in_it_id'] . " AND in_ve_id IS NULL";
                        $mysql_link->query($myupdate);
                    }
                    $myupdate = "UPDATE inventory SET in_quantity = 0 WHERE in_ve_id = " . $_SESSION['session_id'];
                    $mysql_link->query($myupdate);
                    echo "Success!";
                    $mysql_link->close();
                    break;
            }
            break;
        case 3:
            switch ($_POST['action']) {
                case 1:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myquery = "SELECT * FROM base";
                    $base = $mysql_link->query($myquery);

                    $myquery = "SELECT citizen_info.ci_fname, citizen_info.ci_lname, citizen_info.ci_phone, citizen_info.ci_latitude, citizen_info.ci_longitude, task.ta_issue_date, item.it_name, item.it_id, task.ta_quantity, task.ta_accept_date, rescuers.us_name, task.ta_re_id
                            FROM task
                            INNER JOIN citizen_info ON task.ta_ci_id = citizen_info.ci_us_id
                            INNER JOIN item ON task.ta_it_id = item.it_id
                            LEFT JOIN users AS rescuers ON task.ta_re_id = rescuers.us_id
                            WHERE task.isRequest = 1 AND task.ta_status <> 'Completed'";
                    $requests = $mysql_link->query($myquery);

                    $myquery = "SELECT citizen_info.ci_fname, citizen_info.ci_lname, citizen_info.ci_phone, citizen_info.ci_latitude, citizen_info.ci_longitude, task.ta_issue_date, item.it_name, item.it_id, task.ta_quantity, task.ta_accept_date, rescuers.us_name, task.ta_re_id
                            FROM task
                            INNER JOIN citizen_info ON task.ta_ci_id = citizen_info.ci_us_id
                            INNER JOIN item ON task.ta_it_id = item.it_id
                            LEFT JOIN users AS rescuers ON task.ta_re_id = rescuers.us_id
                            WHERE task.isRequest = 0 AND task.ta_status <> 'Completed'";
                    $donations = $mysql_link->query($myquery);

                    $myquery = "SELECT users.us_name, users.us_id, vehicle.ve_latitude, vehicle.ve_longitude
                            FROM vehicle
                            INNER JOIN users ON vehicle.ve_us_id = users.us_id";
                    $vehicles = $mysql_link->query($myquery);

                    $myquery = "SELECT *
                            FROM inventory
                            INNER JOIN item ON inventory.in_it_id = item.it_id
                            WHERE in_ve_id IS NOT NULL AND in_quantity > 0";
                    $inventory = $mysql_link->query($myquery);


                    $mysql_link->close();

                    $myobject = '{"base": [' . json_encode($base->fetch_array(MYSQLI_ASSOC));
                    $myobject .= '], "requests": [' . json_encode($requests->fetch_array(MYSQLI_ASSOC));
                    while ($row = $requests->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "donations": [' . json_encode($donations->fetch_array(MYSQLI_ASSOC));
                    while ($row = $donations->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "vehicles": [' . json_encode($vehicles->fetch_array(MYSQLI_ASSOC));
                    while ($row = $vehicles->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "inventory": [' . json_encode($inventory->fetch_array(MYSQLI_ASSOC));
                    while ($row = $inventory->fetch_array(MYSQLI_ASSOC)) {
                        $myobject .= ', ' . json_encode($row);
                    }
                    $myobject .= '], "level":"' . $_SESSION['session_level'] . '"}';
                    echo $myobject;
                    break;

                case 2:
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myupdate = "UPDATE base SET ba_latitude = " . $_POST['lat'] . ", ba_longitude = " . $_POST['lng'];
                    $mysql_link->query($myupdate);
                    if ($mysql_link->affected_rows == 1) {
                        echo "Success";
                    } else
                        echo "Fail";
                    $mysql_link->close();
                    break;
            }
            break;
        
        default:
            return;
    }