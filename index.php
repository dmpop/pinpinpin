<?php
$photo_dir = "photos";
$ext = "jpg,JPG";

// Check whether the php-exif library is installed
if (!extension_loaded('exif')) {
    exit("<center><code style='color: red;'>php-exif is not installed</code></center>");
}

// Create $photo_dir if it doesn't exist
if (!file_exists($photo_dir)) {
    mkdir($photo_dir, 0755, true);
}

$photos = glob($photo_dir . DIRECTORY_SEPARATOR . '*.{' . $ext . '}', GLOB_BRACE);
// Count all photos in $photo_dir 
$total_count = count($photos);
// Check if $photo_dir is empty
if ($total_count === 0) {
    exit("<center><code style='color: red;'>No photos found</code></center>");
} else {
    // Find the most recent photo to center the map on
    // $total_count-1 because arrays start with 0
    $first_photo = $photos[$total_count - 1];
};

// Function to read GPS coordinates from geotagged photos
function read_gps_location($file)
{
    if (is_file($file)) {
        $exif = exif_read_data($file);
        if (
            isset($exif['GPSLatitude']) && isset($exif['GPSLongitude']) &&
            isset($exif['GPSLatitudeRef']) && isset($exif['GPSLongitudeRef']) &&
            in_array($exif['GPSLatitudeRef'], array('E', 'W', 'N', 'S')) && in_array($exif['GPSLongitudeRef'], array('E', 'W', 'N', 'S'))
        ) {

            $GPSLatitudeRef     = strtolower(trim($exif['GPSLatitudeRef']));
            $GPSLongitudeRef = strtolower(trim($exif['GPSLongitudeRef']));

            $lat_degrees_a = explode('/', $exif['GPSLatitude'][0]);
            $lat_minutes_a = explode('/', $exif['GPSLatitude'][1]);
            $lat_seconds_a = explode('/', $exif['GPSLatitude'][2]);
            $lon_degrees_a = explode('/', $exif['GPSLongitude'][0]);
            $lon_minutes_a = explode('/', $exif['GPSLongitude'][1]);
            $lon_seconds_a = explode('/', $exif['GPSLongitude'][2]);

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

// Find and remove photos that are not geotagged
foreach ($photos as $file) {
    $exif = exif_read_data($file);
    if (!isset($exif['GPSLatitude']) && !isset($exif['GPSLongitude'])) {
        unlink($file);
    }
}
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
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <link rel="stylesheet" href="leaflet/L.Control.Locate.min.css" />
    <script src="leaflet/L.Control.Locate.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="leaflet/MarkerCluster.css" />
    <link rel="stylesheet" href="leaflet/MarkerCluster.Default.css" />
    <script src="leaflet/leaflet.markercluster.js"></script>
</head>

<body>
    <div id="map"></div>

    <script type="text/javascript">
        var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors,  This is <a href="https://github.com/dmpop/pinpinpin">PinPinPin</a>. Photos: <?php echo $total_count; ?>'
            });

        var map = L.map('map', {
            zoom: 9,
            layers: [tiles]
        });

        var markers = L.markerClusterGroup();
        <?php
        foreach ($photos as $file) {
            $gps = read_gps_location($file);
            echo 'var marker = L.marker(new L.LatLng(' . $gps['lat'] . ', ' . $gps['lon'] . '));';
            echo "marker.bindPopup('<a href=\"" . $file . "\"  target=\"_blank\"><img src=\"tim.php?image=" . $file . "\" width=300px /></a>" . $caption . "');";
            echo 'markers.addLayer(marker);';
        }
        ?>
        map.addLayer(markers);

        <?php
        $init_coordinates = read_gps_location($first_photo);
        ?>
        map.panTo(new L.LatLng(<?php echo $init_coordinates['lat']; ?>, <?php echo $init_coordinates['lon']; ?>));
    </script>
</body>

</html>