<?php
$PASSWORD = 'secret';
$photoDir = "photos";

if (isset($_POST['submit']) && isset($_POST['password'])) {
    // Check whether the password is correct
    if ($_POST['password'] != $PASSWORD) {
        exit("<center><code style='color: red;'>Wrong password</code></center>");
    }
    // Count total files
    $countfiles = count($_FILES['file']['name']);

    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES['file']['name'][$i];
        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'][$i], $photoDir . DIRECTORY_SEPARATOR . $filename);
    }
}
?>
<form method='post' action='' enctype='multipart/form-data'>
    Password: <input type="password" name="password">
    <input type="file" name="file[]" id="file" multiple>

    <input type='submit' name='submit' value='Upload'>
</form>

<a href="index.php">Home</a>