<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("Location: ../login");
    exit();
} elseif ($_SESSION['session_level'] != 2) {
    header("Location: ../home");
    exit();
}
?>

<head>
    <title>Manage cargo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Manage cargo</h2>
    </div>
    <div class="container mt-3">
        <div class="mb-3 mt-3">
            <label for="en">Add category (id) to filter:</label>
            <select class="form-select" id="en" name="en" onchange="enableCategory()">
                <option value="0"></option>
                <?php
                $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                if ($mysql_link->connect_error) {
                    die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                }
                $myquery = "SELECT * FROM category";
                $result = $mysql_link->query($myquery);
                $myresponse = "";
                while ($row = $result->fetch_array()) {
                    $myresponse .= "<option id='en" . $row['ca_id'] . "' value ='" . $row['ca_id'] . "'>" . $row['ca_name'] . " (" . $row['ca_id'] . ")</option>";
                }
                echo $myresponse;
                $mysql_link->close();
                ?>
            </select>
        </div>
        <div class="mb-3 mt-3">
            <label for="ds">Remove category (id) from filter:</label>
            <select class="form-select" id="ds" name="ds" onchange="disableCategory()">
                <option value="0"></option>
                <?php
                $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                if ($mysql_link->connect_error) {
                    die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                }
                $myquery = "SELECT * FROM category";
                $result = $mysql_link->query($myquery);
                $myresponse = "";
                while ($row = $result->fetch_array()) {
                    $myresponse .= "<option id='ds" . $row['ca_id'] . "' value ='" . $row['ca_id'] . "' hidden>" . $row['ca_name'] . " (" . $row['ca_id'] . ")</option>";
                }
                echo $myresponse;
                $mysql_link->close();
                ?>
            </select>
        </div>
        <div class="mb-3 mt-4">
            <table class="table table-responsive table-bordered" style="text-align:center; vertical-align:middle;">
                <thead class="table-dark">
                    <tr>
                        <th>Category (id)</th>
                        <th>Item (id)</th>
                        <th>Detail Name</th>
                        <th>Detail Value</th>
                        <th>Vehicle id</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                </tbody>
            </table>
        </div>

        <div class="mb-2 mt-3">
            <a href="../home" class="btn btn-dark">Back</a>
        </div>
        <p class="text-success" id="status"></p>
    </div>

    <script src="cargo.js"></script>
</body>

</html>