<?php
session_start();
if (!isset($_SESSION['session_username'])){
    header("Location: ../../../login");
    exit();
}
elseif ($_SESSION['session_level'] != 3){
    header("Location: ../../../home");
    exit();
}
?>

<head>
    <title>Delete category</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Delete category</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="cdelete()" id="deleteform">
            <div class="mb-3 mt-3">
                <label for="id">Select category (id):</label>
                <select class="form-select" id="id" name="id" required form="deleteform">
                    <option value="0"></option>
                    <?php
                        $mysql_link = new mysqli('localhost:42069', 'root', '', 'disaster_volunteer');
                        if ($mysql_link->connect_error) {
                            die('Connect Error (' . $mysql_link->connect_errno . ') ' . $mysql_link->connect_error);
                        }
                        $myquery = "SELECT * FROM category";
                        $result = $mysql_link->query($myquery);
                        $myresponse = "";
                        while($row = $result->fetch_array()){
                            $myresponse .= "<option value ='" . $row['ca_id'] . "'>" . $row['ca_name'] . " (" . $row['ca_id'] . ")</option>";
                        }
                        echo $myresponse;
                        $mysql_link->close();
                    ?>
                </select>
            </div>
            <button type="submit" id="btn-delete" class="btn btn-dark">Submit</button>
            <span class="text-danger">(Warning: contained items will also be deleted!)</span>
        </form>
        <div class="mb-2 mt-3">
            <a href="../../" class="btn btn-dark">Back</a>
        </div>
        <p class="text-success" id="status"></p>
    </div>

    <script src="delete.js"></script>
</body>

</html>