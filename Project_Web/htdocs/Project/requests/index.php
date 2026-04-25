<?php
session_start();
if (!isset($_SESSION['session_username'])){
    header("Location: ../login");
    exit();
}
elseif ($_SESSION['session_level'] != 1){
    header("Location: ../home");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Requests</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">
            Requests
        </h2>
    </div>
    <div class="container mt-3 mb-3 d-grid gap-4">
        <a href="submit" class="btn btn-dark btn-block">Submit a request</a>
        <a href="overview" class="btn btn-dark btn-block">View your requests</a>
        <a href="../home" class="btn btn-dark btn-block">Back</a>
    </div>
</body>

</html>