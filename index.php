<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="css/classless.css" />
    <link rel="stylesheet" href="css/themes.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <script src="GM_Utils/GPX2GM.js"></script>
    <style>
        #map {
            width: 100%;
            height: 35em;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <?php
    if (!file_exists("gpx")) {
        mkdir("gpx", 0755, true);
    }
    $title = "GPX on map";
    $blurb = "This is a simple example of using the GPX Viewer JavaScript library to generate maps with GPX tracks.";
    echo "<h1 style='margin-top: 0em;'>$title</h1>";
    echo "<p>$blurb</p>";
    echo 'Track: <select class="gpxview">';
    $files = glob("gpx/*.gpx");
    foreach ($files as $track) {
        echo "<option value='map:$track'>$track</option>";
    }
    echo '</select>';
    echo "<div id='map' class='gpxview:$track:OSM'></div>";
    echo "<noscript><p>Enable JavaScript to view the map.</p></noscript>";
    ?>
    <button type="button" class="gpxview:map:skaliere">Reset position and zoom</button>
    </noscript>
    <script>
        var Bestaetigung = false;
        var Shwpname = false;
        var Legende_fnm = false;
        var Fullscreenbutton = true;
        var Arrowtrack = true;
    </script>
</body>

</html>