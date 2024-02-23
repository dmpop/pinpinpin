<?php

$password = "secret";

if (isset($_POST['save'])) {
    if ($_POST['password'] != $password) {
        exit('Wrong password');
    }
    Write();
    header('Location:markers.php');
}
?>

<!DOCTYPE html>
<html>

<!-- Author: Dmitri Popov, dmpop@linux.com
	 License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
    <title>PinPinPin</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" />
    <style>
        textarea {
            font-size: 15px;
            width: 50em;
            height: 25em;
            line-height: 1.9;
            margin-top: 2em;
        }
    </style>
    <!-- Suppress form re-submit prompt on refresh -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body>
    <a href="markers.php">Back</a>
    <?php
    function Read()
    {
        $f = "markers.csv";
        echo file_get_contents($f);
    }
    function Write()
    {
        $f = "markers.csv";
        $fp = fopen($f, "w");
        $data = $_POST["text"];
        fwrite($fp, $data);
        fclose($fp);
    }
    ?>
    <form action=" " method="POST">
        <textarea name="text"><?php Read(); ?></textarea><br /><br />
        <label>Password:
            <input type="password" name="password">
        </label>
        <input title="Save changes" type="submit" name="save" value="Save"/>
    </form>
</body>

</html>