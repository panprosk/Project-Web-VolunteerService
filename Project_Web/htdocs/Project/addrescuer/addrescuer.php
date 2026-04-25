<?php
session_start();
if (!isset($_SESSION['session_username']))
    return;
elseif ($_SESSION['session_level'] != 3)
    return;


$mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
if ($mysql_link->connect_error) {
    die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
}

$myquery = "SELECT * FROM users WHERE us_name='" . $_POST['username'] . "'";
$result = $mysql_link->query($myquery);

if ($result->num_rows == 0) {
    $insert = "INSERT INTO users VALUES (null,'" . $_POST['username'] . "','" . $_POST['pswd'] . "',2)";
    $mysql_link->query($insert);

    $rescid = $mysql_link->insert_id;

    $myquery = "SELECT * FROM base";
    $result = $mysql_link->query($myquery);
    if ($result->num_rows == 1) {
        $row = $result->fetch_array();
        $insert = "INSERT INTO vehicle VALUES (" . $rescid . "," . $row['ba_longitude'] . "," . $row['ba_latitude'] . ")";
    } else {
        $insert = "INSERT INTO vehicle VALUES (" . $rescid . ", null, null)";
    }
    $mysql_link->query($insert);

    $myquery = "SELECT it_id FROM item";
    $result = $mysql_link->query($myquery);
    if($result->num_rows > 0){
        $insert = "INSERT INTO inventory VALUES ";
        while($row = $result->fetch_array()){
            $insert .= "(null, " . $rescid . ", " . $row['it_id'] . ", 0),";
        }
        $insert = rtrim($insert,",");
        $mysql_link->query($insert);
    }

    echo "Success";
} else
    echo "Fail";
$mysql_link->close();