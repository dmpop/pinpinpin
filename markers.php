<?php
$csv_file = "markers.csv";
if (!file_exists($csv_file)) {
    exit("<center><code style='color: red;'>$csv_file not found.</code></center>");
    }
?>

<!DOCTYPE html>
<html>

<!-- Author: Dmitri Popov
License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
Source code: https://github.com/dmpop/pinpinpin -->

<head>
    <title>PinPinPin</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="favicon.png" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <!-- Numbered markers https://gist.github.com/comp615/2288108 -->
    <link rel="stylesheet" href="leaflet/leaflet_numbered_markers.css" />
    <script src="leaflet/leaflet_numbered_markers.js"></script>
</head>

<body>
    <div id="map"></div>
    <script>
        // Creating map options
        var mapOptions = {
            <?php
            if (($file = fopen($csv_file, "r")) !== FALSE) {
                $line = fgetcsv($file);
                echo "center: [" . $line[0] . "," . $line[1] . "],";
            }
            ?>
            zoom: 5
        }
        // Creating a map object
        var map = new L.map('map', mapOptions);

        // Creating a Layer object
        var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors. This is <a href="https://github.com/dmpop/pinpinpin">PinPinPin</a>'
        });

        // Adding layer to the map
        map.addLayer(layer);

        // Creating a marker
        <?php
        if (($file = fopen($csv_file, "r")) !== FALSE) {
            for ($i = 0; $i < count(file($csv_file)); $i++) {
                $line = fgetcsv($file);
                echo "
                var marker" . $i . " = new L.Marker(new L.LatLng(" . $line[0] . ", " . $line[1] . "), {
                    icon: new L.NumberedDivIcon({
                        number: '" . $i + 1 . "'
                    })
                }).addTo(map).bindPopup('" . $line[2] . "');
                ";
            }
            fclose($file);
        }
        ?>
    </script>
</body>

</html>