<?php
    session_start();
    if(!isset($_SESSION['session_username'])){
        header("Location: ../login");
        exit();
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">
            Signed in as <?php echo $_SESSION['session_username'] ?>
            <?php
                switch ($_SESSION['session_level']) {
                    case 1:
                        echo "(Citizen)";
                        break;
                    case 2:
                        echo "(Rescuer)";
                        break;
                    case 3:
                        echo "(Admin)";
                        break;
                }
            ?>
        </h2>
    </div>
    <div class="container mt-3 mb-3 d-grid gap-4">
        <?php
            if($_SESSION['session_level'] == 3)
            echo '
            <a href="../inventory" class="btn btn-dark btn-block">Inventory</a>
            <a href="../map" class="btn btn-dark btn-block">Map</a>
            <a href="../statistics" class="btn btn-dark btn-block">Statistics</a>
            <a href="../addrescuer" class="btn btn-dark btn-block">Add Rescuer</a>
            <a href="../announcements" class="btn btn-dark btn-block">Announcements</a>';
            elseif($_SESSION['session_level'] == 2)
            echo '
            <a href="../cargo" class="btn btn-dark btn-block">Cargo</a>
            <a href="../map" class="btn btn-dark btn-block">Map</a>';
            else
            echo '
            <a href="../requests" class="btn btn-dark btn-block">Requests</a>
            <a href="../donations" class="btn btn-dark btn-block">Donations</a>';
        ?>
        <a href="../logout" class="btn btn-dark btn-block">Log out</a>
    </div>
</body>

</html>

