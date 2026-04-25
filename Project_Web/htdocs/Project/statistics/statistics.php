<?php
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 3)
        return;

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];

    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }

    $myquery = "SELECT
                COUNT(CASE WHEN isRequest THEN 1 END) as req_new,
                COUNT(CASE WHEN NOT isRequest THEN 1 END) as don_new,
                COUNT(CASE WHEN ta_status = 'Completed' AND isRequest THEN 1 END) as req_com,
                COUNT(CASE WHEN ta_status = 'Completed' AND NOT isRequest THEN 1 END) as don_com
                FROM task 
                WHERE ta_issue_date >= '" . $sdate . "' AND ta_issue_date <= '" . $edate . "'";

    $result = $mysql_link->query($myquery);
    $mysql_link->close();

    echo json_encode($result->fetch_array());