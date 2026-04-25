<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("Location: ../login");
    exit();
} elseif ($_SESSION['session_level'] < 2) {
    header("Location: ../home");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Map</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        #map {
            height: 600px;
        }
    </style>
</head>

<body>

    <div class="container p-5 my-5 bg-dark text-white rounded">
        <h2 class="text-center">Map</h2>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <div id="map"></div>
            </div>
            <div id="paneldiv" class="col-sm-4" hidden>
                <div class="container mt-3 mb-3" id="panel">
                    <label for="taskselect">Select accepted task:</label>
                    <select class="form-select" id="taskselect" name="taskselect" onchange="taskSelect()">
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="d-grid col">
                        <a href="javascript: taskComplete()" class="btn btn-dark btn-block disabled bcontrol">Complete</a>
                    </div>
                    <div class="d-grid col">
                        <a href="javascript: taskRemove()" class="btn btn-dark btn-block disabled bcontrol">Cancel</a>
                    </div>
                </div>
                <div class="d-grid col mb-3">
                    <a href="javascript: unloadCargo()" class="btn btn-dark btn-block">Unload Cargo</a>
                </div>
                <p class="text-center" id="status"></p>
            </div>
            <div class="mb-2 mt-3">
                <a href="../login" class="btn btn-dark">Back</a>
            </div>
        </div>
        <p id="rescdistance" class="text-center" hidden>Distance to base: <span id="distance"></span> (m)</p>
        <script src="map.js"></script>
</body>

</html>