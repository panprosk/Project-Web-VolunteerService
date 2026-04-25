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
    <title>Create category</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Create category</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="create()">
            <div class="mb-3 mt-3">
                <label for="name">Category name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
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