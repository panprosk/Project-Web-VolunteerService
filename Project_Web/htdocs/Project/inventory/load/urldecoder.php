<?php
    //error_reporting(0);
    session_start();
    if (!isset($_SESSION['session_username']))
        return;
    elseif ($_SESSION['session_level'] != 3)
        return;



    $target_file = "upload/url.json";
    if (file_exists($target_file)) {
        if (!unlink($target_file)) {
            //echo "Unable to delete preexisting file.";
        }
    }

    $json = json_decode(file_get_contents($_POST['url']));

    //Iterate through the json object

    if (is_null($json)) {
        echo "File is not written in proper JSON format.";
        return;
    }


    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
    if ($mysql_link->connect_error) {
        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
    }
    $myinsert = "";
    $result = "";

    foreach ($json->categories as $category) {
        $myinsert = "INSERT INTO category VALUES (null, '" . $category->category_name . "')";
        $mysql_link->query($myinsert);
        $new_id = $mysql_link->insert_id;
        foreach ($json->items as $item){
            if($item->category == $category->id){
                $myinsert = "INSERT INTO item VALUES (null, " . $new_id . ", '" . $item->name . "')";
                $mysql_link->query($myinsert);
                $current_id = $mysql_link->insert_id;

                $myinsert = "INSERT INTO detail VALUES ";
                $count = 0;
                foreach($item->details as $detail){
                    $newvalue = "(" . $current_id . ", '" . $detail->detail_name . "', '" . $detail->detail_value . "'),";
                    if(!str_contains($myinsert, $newvalue))
                        $myinsert .= $newvalue;
                    $count += 1;
                }
                $myinsert = rtrim($myinsert,",");
                $mysql_link->query($myinsert);

                $myquery = "SELECT ve_us_id FROM vehicle";
                $result = $mysql_link->query($myquery);
                $myinsert = "INSERT INTO inventory VALUES (null, null, $current_id, 0)";
                while($row = $result->fetch_array()){
                    $myinsert .= ", (null, " . $row['ve_us_id'] . ", " . $current_id . ", 0)";
                }
                $mysql_link->query($myinsert);
            }
        }
    }

    echo "Success!";

    $mysql_link->close();
    if (file_exists($target_file)) {
        if (!unlink($target_file)) {
            echo "Unable to delete created file.";
        }
    }