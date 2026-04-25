<?php
    session_start();
    if(!isset($_SESSION['session_username'])){
        header("Location: ../login");
        exit();
    }
    elseif($_SESSION['session_level'] != 3){
        header("Location: ../home");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Rescuer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Register a Rescuer</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="addrescuerSubmit()">
            <div class="mb-3 mt-3">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required>
            </div>
            <button type="submit" id="btn-addrescuer" class="btn btn-dark">Submit</button>
        </form>
        <div class="mb-2 mt-3">
            <a href="../home" class="btn btn-dark">Back</a>
        </div>
        <p class="text-danger" id="failedaddrescuer"></p>
    </div>

    <script src="addrescuer.js"></script>
</body>

</html>