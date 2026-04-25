<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("Location: ../../login");
    exit();
} elseif ($_SESSION['session_level'] != 1) {
    header("Location: ../../home");
    exit();
}
?>

<head>
    <title>Submit request</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Submit request</h2>
    </div>
    <div class="container mt-3">
        <form autocomplete="off" action="javascript:;" onsubmit="submitSelect()" id="requestform">
            <div class="row mb-3 mt-3">
                <div class="col">
                <label for="id">Select announcement date (id):</label>
                <select class="form-select" id="selcatid" name="id" form="requestform" onchange="selectCategory()">
                    <option value="0"></option>
                    <?php
                    $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                    if ($mysql_link->connect_error) {
                        die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                    }
                    $myquery = "SELECT * FROM announcement";
                    $result = $mysql_link->query($myquery);
                    $myresponse = "";
                    while ($row = $result->fetch_array()) {
                        $myresponse .= "<option value ='" . $row['an_id'] . "'>" . $row['an_date'] . " (" . $row['an_id'] . ")</option>";
                    }
                    echo $myresponse;
                    $mysql_link->close();
                    ?>
                </select>
                </div>
                <div class="col">
                    <label for="id">Select item (id):</label>
                    <select class="form-select" id="selitid" name="id" required form="deleteform" required>
                        <option value="0"></option>
                        <?php
                        $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                        if ($mysql_link->connect_error) {
                            die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                        }
                        $myquery = "SELECT it_id, it_name, it_ca_id, re_an_id
                                    FROM item
                                    INNER JOIN requests ON it_id = re_it_id";
                        $result = $mysql_link->query($myquery);
                        $myresponse = "";
                        while ($row = $result->fetch_array()) {
                            $myresponse .= "<option class='selection category" . $row['re_an_id'] . "' value ='" . $row['it_id'] . "' hidden>" . $row['it_name'] . " (" . $row['it_id'] . ")</option>";
                        }
                        echo $myresponse;
                        $mysql_link->close();
                        ?>
                    </select>
                </div>
                <div class="col">
                    <label for="selquantity">Enter quantity:</label>
                    <input type="number" class="form-control" id="selquantity" placeholder="Enter quantity" name="quantity" required>
                </div>
            </div>
            <button type="submit" id="btn-request" class="btn btn-dark">Submit</button>
        </form>
        <div class="mb-2 mt-3">
            <a href="../" class="btn btn-dark">Back</a>
        </div>
        <p class="text-success" id="status"></p>
    </div>

    <script src="submit.js"></script>
</body>

</html>