<?php
session_start();
if (isset($_SESSION['session_username']))
    echo "Success";
else {
    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }

    $myquery = "SELECT * FROM users WHERE us_name=BINARY '" . $_POST['username'] . "' AND us_password=BINARY '" . $_POST['pswd'] . "'";
    $result = $mysql_link->query($myquery);

    if ($result->num_rows == 1) {
        $_SESSION['session_username'] = $_POST['username'];
        $row = $result->fetch_array();
        $_SESSION['session_level'] = $row['us_level'];
        $_SESSION['session_id'] = $row['us_id'];
        echo "Success";
    } else
        echo "Fail";
    $mysql_link->close();
}