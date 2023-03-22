<?php
$kmzDir = "kmz";

// Create $kmzDir if it doesn't exist
if (!file_exists($kmzDir)) {
    mkdir($kmzDir, 0755, true);
}

// Check if the $kmzDir is empty
if (count(glob($kmzDir . DIRECTORY_SEPARATOR . '*')) === 0) {
    exit("<center><code style='color: red;'>No KMZ files found.</code></center>");
};
?>

<!DOCTYPE html>

<!--
Author: Dmitri Popov
License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
Source code: https://github.com/dmpop/pinpinpin

Useful resources:
https://github.com/Raruto/leaflet-kmz
-->

<html>

<head>
    <title>PinPinPin</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <link rel="stylesheet" href="leaflet/L.Control.Locate.min.css" />
    <script src="leaflet/L.Control.Locate.min.js" charset="utf-8"></script>
    <script src="leaflet/leaflet-kmz.js"></script>
    <style>
        html,
        body,
        #map {
            margin: 0;
            height: 100%;
            width: 100%;
        }
    </style>
</head>

<?php
$kmzFiles = scandir($kmzDir, SCANDIR_SORT_DESCENDING);
//$kmzFile = $kmzDir . DIRECTORY_SEPARATOR .  $files[0];
?>

<script type="text/javascript">
    var init = function() {

        var map = L.map('map').setView([43.5978, 12.7059], 5);
        L.tileLayer(
            'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors. This is <a href="https://github.com/dmpop/pinpinpin">PinPinPin</a>.',
                maxZoom: 18,
            }).addTo(map);
        var kmz = L.kmzLayer().addTo(map);

        kmz.on('load', function(e) {
            control.addOverlay(e.layer, e.name);
            // e.layer.addTo(map);
        });

        <?php
        $i = 0;
        $arrayLength = count($kmzFiles);
        while ($i < $arrayLength) {
            echo "kmz.load('" . $kmzDir . DIRECTORY_SEPARATOR . $kmzFiles[$i] . "');";
            $i++;
        }
        ?>
        L.control.locate({
            strings: {
                title: "My current position"
            }
        }).addTo(map);
        L.control.layers(background, overlays).addTo(map);
    }
</script>

<body onload="init()">
    <div id="map"></div>
</body>

</html>