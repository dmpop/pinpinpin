<?php
$gpxDir = "gpx";

// Create $gpxDir if it doesn't exist
if (!file_exists($gpxDir)) {
    mkdir($gpxDir, 0755, true);
}

// Check if the $gpxDir is empty
if (count(glob($gpxDir . DIRECTORY_SEPARATOR . '*')) === 0) {
    exit("<center><code style='color: red;'>No GPX files found.</code></center>");
};
?>

<!DOCTYPE html>

<!--
Author: Dmitri Popov
License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
Source code: https://github.com/dmpop/pinpinpin

Useful resources:
https://github.com/mpetazzoni/leaflet-gpx
https://meggsimum.de/webkarte-mit-gps-track-vom-sport/
https://www.tutorialspoint.com/leafletjs/leafletjs_markers.htm
https://stackoverflow.com/questions/42968243/how-to-add-multiple-markers-in-leaflet-js
-->

<html>

<head>
    <title>PinPinPin</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <script src="leaflet/gpx.js"></script>
    <link rel="stylesheet" href="leaflet/L.Control.Locate.min.css" />
    <script src="leaflet/L.Control.Locate.min.js" charset="utf-8"></script>
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
$files = scandir($gpxDir, SCANDIR_SORT_DESCENDING);
$gpxFile = $gpxDir . DIRECTORY_SEPARATOR .  $files[0];
?>

<script type="text/javascript">
    var init = function() {

        var map = L.map('map').setView([11.206051, 122.447886], 8);
        L.tileLayer(
            'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors. This is <a href="https://github.com/dmpop/pinpinpin">PinPinPin</a>. GPX file: <?php echo $files[0] ?>',
                maxZoom: 18,
            }).addTo(map);

        // Define the GPX layer
        var pathGpxTrack = '<?php echo $gpxFile; ?>'
        markerOptions = {
            startIconUrl: 'pin-icon-start.png',
            endIconUrl: 'pin-icon-end.png',
            shadowUrl: 'pin-shadow.png',
        };

        // Add track to the map
        var gpxTrack = new L.GPX(
            '<?php echo $gpxFile; ?>', {
                async: true,
                max_point_interval: 120000,
                polyline_options: {
                    color: '#005ce6'
                },
            }
        );
        // Add the GPX layer to the map
        gpxTrack.addTo(map);

        var wptPin = L.icon({
            iconUrl: 'pin-icon-wpt.png'
        });

        // Register popups on click
        // Set initial zoom
        gpxTrack.on('loaded', function(e) {
            var gpx = e.target,
                distM = gpx.get_distance(),
                distKm = distM / 1000,
                distKmRnd = distKm.toFixed(1),
                speedKmh = gpx.get_moving_speed(),
                speedKmhRnd = speedKmh.toFixed(1);

            gpx.getLayers()[0].bindPopup(
                "Total distance: " + distKmRnd + " km</br>" +
                "Average speed: " + speedKmhRnd + " km/h"
            );
            // Zoom to the GPX track
            map.fitBounds(gpx.getBounds());
        });
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
    <!-- <div style="text-align: center;">
        <code>
            <form method='POST' action='' style="display: inline-block;">
            Name: <input type='text' name='name'> Lat: <input type='text' name='lat'> Lon: <input type='text' name='lon'> <input type="submit" name="submit" value="Save">
            </form>
        </code>
    </div> -->
</body>

</html>