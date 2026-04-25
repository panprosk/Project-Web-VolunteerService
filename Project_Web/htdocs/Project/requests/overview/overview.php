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
    $myquery = "SELECT ta_id, ta_status, ta_it_id, us_name, ta_issue_date, ta_accept_date, ta_quantity
                FROM task
                LEFT JOIN users ON users.us_id = ta_re_id
                WHERE ta_ci_id = " . $_SESSION['session_id'] . " AND isRequest = 1";
    $records = $mysql_link->query($myquery);
    if ($records->num_rows == 0) {
        echo "There are no requests!";
        exit();
    }
    $first = $records->fetch_array(MYSQLI_NUM);
    $myobject = '{"records": [' . json_encode($first);
    while ($row = $records->fetch_array(MYSQLI_NUM)) {
        $myobject .= ', ' . json_encode($row);
    }
    $myobject .= ']}';
    echo $myobject;