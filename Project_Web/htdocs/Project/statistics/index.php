<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("Location: ../login");
    exit();
} elseif ($_SESSION['session_level'] != 3) {
    header("Location: ../home");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Task statistics</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Task statistics</h2>
    </div>
    <div class="container mt-3">
        <form action="javascript:;" onsubmit="submitRange()">
            <div class="mb-3 mt-3">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="sdate">Start date:</label>
                        <input type="date" class="form-control" id="sdate" placeholder="Select start date:" name="sdate"
                            required>
                    </div>
                    <div class="col-sm-6">
                        <label for="edate">End date:</label>
                        <input type="date" class="form-control" id="edate" placeholder="Select start date:" name="edate"
                            required>
                    </div>
                </div>
            </div>
            <button type="submit" id="btn-addrescuer" class="btn btn-dark">Submit</button>
        </form>
        <div>
            <canvas id="myChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="mb-2 mt-3">
            <a href="../home" class="btn btn-dark">Back</a>
        </div>
        <p class="text-danger" id="status"></p>
    </div>

    <script src="statistics.js"></script>
</body>

</html>