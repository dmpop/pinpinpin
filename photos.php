<?php
$photoDir = "photos";

// Create $photoDir if it doesn't exist
if (!file_exists($photoDir)) {
    mkdir($photoDir, 0755, true);
}
// Check if the $photoDir is empty
if (count(glob($photoDir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE)) === 0) {
    exit("<center><code style='color: red;'>No photos found.</code></center>");
};
?>

<!DOCTYPE html>

<!--
Author: Dmitri Popov
License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
Source code: https://github.com/dmpop/ifti

Useful resources:
https://github.com/mpetazzoni/leaflet-gpx
https://meggsimum.de/webkarte-mit-gps-track-vom-sport/
https://www.tutorialspoint.com/leafletjs/leafletjs_markers.htm
https://stackoverflow.com/questions/42968243/how-to-add-multiple-markers-in-leaflet-js
-->

<html>

<head>
    <title>Ifti</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>

    <style>
        html,
        body,
        #map {
            margin: 0;
            height: 99%;
            width: 100%;
        }
    </style>
</head>

<body>

    <?php

    function read_gps_location($file)
    {
        if (is_file($file)) {
            $info = exif_read_data($file);
            if (
                isset($info['GPSLatitude']) && isset($info['GPSLongitude']) &&
                isset($info['GPSLatitudeRef']) && isset($info['GPSLongitudeRef']) &&
                in_array($info['GPSLatitudeRef'], array('E', 'W', 'N', 'S')) && in_array($info['GPSLongitudeRef'], array('E', 'W', 'N', 'S'))
            ) {

                $GPSLatitudeRef     = strtolower(trim($info['GPSLatitudeRef']));
                $GPSLongitudeRef = strtolower(trim($info['GPSLongitudeRef']));

                $lat_degrees_a = explode('/', $info['GPSLatitude'][0]);
                $lat_minutes_a = explode('/', $info['GPSLatitude'][1]);
                $lat_seconds_a = explode('/', $info['GPSLatitude'][2]);
                $lon_degrees_a = explode('/', $info['GPSLongitude'][0]);
                $lon_minutes_a = explode('/', $info['GPSLongitude'][1]);
                $lon_seconds_a = explode('/', $info['GPSLongitude'][2]);

                $lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
                $lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
                $lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
                $lon_degrees = $lon_degrees_a[0] / $lon_degrees_a[1];
                $lon_minutes = $lon_minutes_a[0] / $lon_minutes_a[1];
                $lon_seconds = $lon_seconds_a[0] / $lon_seconds_a[1];

                $lat = (float) $lat_degrees + ((($lat_minutes * 60) + ($lat_seconds)) / 3600);
                $lon = (float) $lon_degrees + ((($lon_minutes * 60) + ($lon_seconds)) / 3600);

                // If the latitude is South, make it negative
                // If the longitude is west, make it negative
                $GPSLatitudeRef     == 's' ? $lat *= -1 : '';
                $GPSLongitudeRef == 'w' ? $lon *= -1 : '';

                return array(
                    'lat' => $lat,
                    'lon' => $lon
                );
            }
        }
        return false;
    }
    ?>

    <body onload="init()">
        <div style="text-align: center;">
            <code>
                This is <a href='https://github.com/dmpop/ifti'>Ifti</a><span style='float: right; margin-right: 1em;'><a href='index.php'><img style='vertical-align:middle;' src='svg/pin-location.svg' height=18 /></a></span>
            </code>
        </div>
        <script type="text/javascript">
            var init = function() {

                var map = L.map('map').setView([0, 0], 2);
                L.tileLayer(
                    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(map);

                var posPin = L.icon({
                    iconUrl: 'pin-icon-pos.png'
                });

                // Add markers with popups
                <?php
                $photos = glob($photoDir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE);
                foreach ($photos as $file) {
                    $gps = read_gps_location($file);
                    echo "L.marker([" . $gps['lat'] . ", " . $gps['lon'] . "], {";
                    echo  'icon: posPin';
                    echo "}).addTo(map)";
                    echo ".bindPopup('<a href=\"" . $file . "\"  target=\"_blank\"><img src=\"" . $file . "\" width=200px /></a>');";
                }
                ?>
                L.control.layers(background, overlays).addTo(map);
            }
        </script>
        <div id="map"></div>
    </body>

</html>