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
    <style>
        .autocomplete {
            position: relative;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Submit request</h2>
    </div>
    <div class="container mt-3">
        <form autocomplete="off" action="javascript:;" onsubmit="submitSelect()" id="requestform">
            <div class="row mb-3 mt-3">
                <div class="col">
                <label for="id">Select category (id):</label>
                <select class="form-select" id="selcatid" name="id" form="requestform" onchange="selectCategory()">
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
                <div class="col">
                    <label for="id">Select item (id):</label>
                    <select class="form-select" id="selitid" name="id" required form="deleteform" required>
                        <?php
                        $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                        if ($mysql_link->connect_error) {
                            die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                        }
                        $myquery = "SELECT it_id, it_name, it_ca_id FROM item";
                        $result = $mysql_link->query($myquery);
                        $myresponse = "";
                        while ($row = $result->fetch_array()) {
                            $myresponse .= "<option class='selection category" . $row['it_ca_id'] . "' value ='" . $row['it_id'] . "'>" . $row['it_name'] . " (" . $row['it_id'] . ")</option>";
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
        <form autocomplete="off" action="javascript:;" onsubmit="submitSearch()" id="requestform">
            <div class="mb-3 mt-3">
                <div class="row">
                    <div class="col autocomplete">
                    <label for="myInput">Search item (id):</label>
                    <input class="form-control" id="myInput" type="text" name="myNames" placeholder="Search item (id)" required>
                    </div>
                    <div class="col">
                    <label for="schquantity">Enter quantity:</label>
                    <input type="number" class="form-control" id="schquantity" placeholder="Enter quantity" name="quantity" required>
                </div>
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