<!DOCTYPE html>
<html>
<!-- Most of the code has been lifted from https://meggsimum.de/webkarte-mit-gps-track-vom-sport/ -->

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
            height: 100%;
            width: 100%;
        }
    </style>
</head>

<body>

    <?php
    $gpx_dir = "gpx";
    $files = scandir($gpx_dir, SCANDIR_SORT_DESCENDING);
    $gpx_file = $gpx_dir . DIRECTORY_SEPARATOR .  $files[0];
    echo "<code>This is <a href='https://github.com/dmpop/ifti'>Ifti</a>. GPX file: " . $files[0] . "</code>";
    ?>

    <body onload="init()">

        <script type="text/javascript">
            var init = function() {

                var map = new L.Map('map', {
                        crs: L.CRS.EPSG900913,
                        continuousWorld: true,
                        worldCopyJump: false,
                        zoom: 13
                    }),
                    osmWms = L.tileLayer.wms("http://ows.terrestris.de/osm/service", {
                        layers: 'OSM-WMS',
                        format: 'image/png',
                        transparent: true,
                        attribution: '&copy; www.meggsimum.de </br>Background-WMS: &copy; terrestris GmbH &amp; Co. KG, Data © OpenStreetMap <a href="http://www.openstreetmap.org/copyright">contributors</a>'
                    });

                // Add the background layer to the map
                osmWms.addTo(map);

                // Define the GPX layer
                var pathGpxTrack = '<?php echo $gpx_file; ?>',
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

                // Register popups on click
                // Set initial zoom
                gpxTrack.on('loaded', function(e) {
                    var gpx = e.target,
                        distM = gpx.get_distance(),
                        distKm = distM / 1000,
                        distKmRnd = distKm.toFixed(1);

                    gpx.getLayers()[0].bindPopup(
                        "Distance " + distKmRnd + " km"
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