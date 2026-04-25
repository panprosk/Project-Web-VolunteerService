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
    <title>Manage inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Manage inventory</h2>
    </div>
    <div class="container mt-3">
        <div class="mb-3 mt-3">
            <table class="table table-responsive table-bordered" style="text-align:center; vertical-align:middle;">
                <thead class="table-dark">
                    <tr>
                        <th>Request id</th>
                        <th>Status</th>
                        <th>Item (id)</th>
                        <th>Rescuer Username</th>
                        <th>Issue Date</th>
                        <th>Accept Date</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                </tbody>
            </table>
        </div>

        <div class="mb-2 mt-3">
            <a href="../" class="btn btn-dark">Back</a>
        </div>
        <p class="text-success" id="status"></p>
    </div>

    <script src="overview.js"></script>
</body>

</html>