<?php
if (isset($_GET['fileid'])) {
    $fileId = $_GET['fileid'];
echo "<script>console.log('deleting file...')</script>";
    // create sql connection
    include('includes/connection.php');

    // make query to sql files table
    $query = "SELECT `fileName` FROM Files WHERE `fileID`='" . $fileId . "';";

    // send query to sql files table
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("DB query failed from delete.php : " . mysqli_connect_error());
    }
    $row = mysqli_fetch_assoc($result);
    $filename = $row['fileName'];

    $chars = explode("../data/", $filename);
    $convert = implode("", $chars);
    $temp = '/data/';
    $final = $temp . $convert;

    if (file_exists("../data/". $filename)){
        $chars = explode("../data/", $filename);
        $convert = implode("", $chars);
        $temp = '/data/';
        $final = $temp . $convert;
    }
    else {
        $chars = explode("./data/", $filename);
        $convert = implode("", $chars);
        $temp = '/data/';
        $final = $temp . $convert;
    }

    $deletedfile = getcwd() . "" . $final . "";

    unlink($deletedfile);

    echo "File was deleted successfully!";



    $query = "DELETE FROM Files where `fileID`='" . $fileId . "';";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("DB query failed from delete.php : " . mysqli_connect_error());
    }

    // close sql connection
//    mysqli_free_result($result);
    mysqli_close($connection);
//    header('Location: files.html');
    include ("includes/createTables.php");


} else {
    echo 'Cant fetch filename';
}
?>
