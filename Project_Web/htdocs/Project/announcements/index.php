<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("Location: ../login");
    exit();
} elseif ($_SESSION['session_level'] != 3) {
    header("Location: ../home");
    exit();
}
?>

<head>
    <title>Create announcement</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Create announcement</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="submitAnnouncement()" id="createform">
            <div class="mb-3 mt-3">
                <label for="en">Add item (id) to announcement:</label>
                <select class="form-select" id="en" name="en" onchange="enableItem()">
                    <option value="0"></option>
                    <?php
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myquery = "SELECT * FROM item";
                    $result = $mysql_link->query($myquery);
                    $myresponse = "";
                    while ($row = $result->fetch_array()) {
                        $myresponse .= "<option id='en" . $row['it_id'] . "' value ='" . $row['it_id'] . "'>" . $row['it_name'] . " (" . $row['it_id'] . ")</option>";
                    }
                    echo $myresponse;
                    $mysql_link->close();
                    ?>
                </select>
            </div>
            <div class="mb-3 mt-3">
                <label for="ds">Remove item (id) from announcement:</label>
                <select class="form-select" id="ds" name="ds" onchange="disableItem()">
                    <option value="0"></option>
                    <?php
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myquery = "SELECT * FROM item";
                    $result = $mysql_link->query($myquery);
                    $myresponse = "";
                    while ($row = $result->fetch_array()) {
                        $myresponse .= "<option id='ds" . $row['it_id'] . "' value ='" . $row['it_id'] . "' hidden>" . $row['it_name'] . " (" . $row['it_id'] . ")</option>";
                    }
                    echo $myresponse;
                    $mysql_link->close();
                    ?>
                </select>
            </div>
            <button type="submit" id="btn-create" class="btn btn-dark">Submit</button>
        </form>

        <div class="mb-2 mt-3">
            <a href="../home" class="btn btn-dark">Back</a>
        </div>
        <p class="text-success" id="status"></p>
    </div>

    <script src="announcements.js"></script>
</body>

</html>