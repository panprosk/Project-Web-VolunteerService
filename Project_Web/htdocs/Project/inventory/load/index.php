<?php
session_start();
if (!isset($_SESSION['session_username'])){
    header("Location: ../../login");
    exit();
}
elseif ($_SESSION['session_level'] != 3){
    header("Location: ../../home");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Load JSON</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Submit JSON (URL or upload)</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="urldecoder()" id="urlform">
            <div class="mb-3 mt-3">
                <label for="URL">JSON file URL:</label>
                <input type="url" class="form-control" id="url" placeholder="Enter URL" name="url" required>
            </div>
            <button type="submit" id="btn-addrescuer" class="btn btn-dark">Submit URL</button>
        </form>
        <form action="javascript:;" onsubmit="filedecoder()" id="fileform">
            <div class="mb-3 mt-3">
                <label for="file" class="form-label">JSON file upload:</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <button type="submit" id="btn-addrescuer" class="btn btn-dark">Submit file</button>
        </form>
        <div class="mb-2 mt-3">
            <a href="../" class="btn btn-dark">Back</a>
        </div>
        <p id="status"></p>
    </div>

    <script src="load.js"></script>
</body>

</html>