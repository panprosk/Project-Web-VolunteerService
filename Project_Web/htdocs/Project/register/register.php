<?php
session_start();
$mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
if ($mysql_link->connect_error) {
    die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
}

$myquery = "SELECT * FROM users WHERE us_name='" . $_POST['username'] . "'";
$result = $mysql_link->query($myquery);

if ($result->num_rows == 0) {
    $insert = "INSERT INTO users VALUES (null,'" . $_POST['username'] . "','" . $_POST['pswd'] . "',1)";
    $mysql_link->query($insert);

    $_SESSION['session_username'] = $_POST['username'];
    $_SESSION['session_level'] = 1;
    $_SESSION['session_id'] = $mysql_link->insert_id;

    $insert = "INSERT INTO citizen_info VALUES (" . $_SESSION['session_id'] . ",'" . $_POST['fname'] . "','" . $_POST['lname'] . "','" . $_POST['phone'] . "'," . $_POST['lng'] . "," . $_POST['lat'] . ")";
    $mysql_link->query($insert);
    echo "Success";
} else
    echo "Fail";
$mysql_link->close();