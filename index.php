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
</head>

<body>
    <?php
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
    echo "<div id='map' class='gpxview:$track:OSM' style='width:800px;height:600px'></div>";
    echo "<noscript><p>Enable JavaScript to view the map.</p></noscript>";
    ?>
    <button type="button" class="gpxview:map:skaliere">Reset position and zoom</button>
    </noscript>
    <script>
        var Bestaetigung = false;
        var Shwpname = false;
        var Legende_fnm = false;
        var Fullscreenbutton = true;
    </script>
</body>

</html>