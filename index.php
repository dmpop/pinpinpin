<?php
$gpx_dir = "gpx";
$photo_dir = "photos";

// Create $gpx_dir if it doesn't exist
if (!file_exists($gpx_dir)) {
    mkdir($gpx_dir, 0755, true);
}
// Create $photo_dir if it doesn't exist
if (!empty($photo_dir) && !file_exists($photo_dir)) {
    mkdir($photo_dir, 0755, true);
}

// Check if the $gpx_dir is empty
if (count(glob($gpx_dir . DIRECTORY_SEPARATOR . '*')) === 0) {
    exit("<center><code style='color: red;'>No GPX files found.</code></center>");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>
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
    $files = scandir($gpx_dir, SCANDIR_SORT_DESCENDING);
    $gpx_file = $gpx_dir . DIRECTORY_SEPARATOR .  $files[0];
    echo "<center><code>This is <a href='https://github.com/dmpop/ifti'>Ifti</a>. GPX file: " . $files[0] . "</code></center>";

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

        <script type="text/javascript">
            var init = function() {

                var map = L.map('map').setView([11.206051, 122.447886], 8);
                L.tileLayer(
                    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(map);

                // Define the GPX layer
                var pathGpxTrack = '<?php echo $gpx_file; ?>'
                markerOptions = {
                    startIconUrl: 'pin-icon-start.png',
                    endIconUrl: 'pin-icon-end.png',
                    shadowUrl: 'pin-shadow.png',
                };

                // Add track to the map
                var gpxTrack = new L.GPX(
                    '<?php echo $gpx_file; ?>', {
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

                // Add markers with popups
                <?php
                if ($photo_dir) {
                    $photos = glob($photo_dir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE);
                    // Check if the $photo_dir is not empty
                    if (count(glob($photo_dir . DIRECTORY_SEPARATOR . '*')) > 0) {
                        foreach ($photos as $file) {
                            $gps = read_gps_location($file);
                            echo "L.marker([" . $gps['lat'] . ", " . $gps['lon'] . "], {";
                            echo  'icon: wptPin';
                            echo "}).addTo(map)";
                            echo ".bindPopup('<img src=\"" . $file . "\" width=100px />');";
                        }
                    }
                }
                ?>

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

                L.control.layers(background, overlays).addTo(map);
            }
        </script>
        <div id="map"></div>
    </body>
</body>

</html>