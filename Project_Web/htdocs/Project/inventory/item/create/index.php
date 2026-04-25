<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("Location: ../../../login");
    exit();
} elseif ($_SESSION['session_level'] != 3) {
    header("Location: ../../../home");
    exit();
}
?>

<head>
    <title>Create item</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Create item</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="create()" id="createform">
            <div class="mb-3 mt-3">
                <label for="id">Select category (id):</label>
                <select class="form-select" id="id" name="id" required form="createform">
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
                        $myresponse .= "<option value ='" . $row['ca_id'] . "'>" . $row['ca_name'] . " (" . $row['ca_id'] . ")</option>";
                    }
                    echo $myresponse;
                    $mysql_link->close();
                    ?>
                </select>
            </div>
            <div class="mb-3 mt-3">
                <label for="name">Item name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
            </div>
            <label for="detail">Enter details</label>
            <div class="container mb-3 mt-3" id="details">
                <div class="row" id="detail1">
                    <div class="col">
                        <input type="text" class="form-control" id="detailname1" placeholder="Enter detail name" name="detailname1" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="detailvalue1" placeholder="Enter detail value" name="detailvalue1" required>
                    </div>
                </div>
            </div>
            <div class="mb-3 mt-3">
                <button type="button" id="btn-create" class="btn btn-dark" onclick="newDetail()">New detail</button>
            </div>
            <button type="submit" id="btn-create" class="btn btn-dark">Submit</button>
        </form>
        <div class="mb-2 mt-3">
            <a href="../../" class="btn btn-dark">Back</a>
        </div>
        <p class="text-success" id="status"></p>
    </div>

    <script src="create.js"></script>
</body>

</html>