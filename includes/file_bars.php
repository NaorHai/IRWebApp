<?php

if (isset($_GET['delete'])) {
    $deleteFlag = true;
    return;
} else $deleteFlag = false;

// create sql connection
include('connection.php');

// make query to sql files table
$query = "SELECT * FROM Files";

// send query to sql files table
$result = mysqli_query($connection, $query);
if (!$result) {
    die("DB query failed from file_bars.php : " . mysqli_connect_error());
}

// print all sql data rows
while ($row = mysqli_fetch_assoc($result)) {

    echo '<div class="col-xs-6 col-md-6" >';
    echo '<a target="_blank" title="Delete this file" href="deleteFile.php?fileid=' . $row["fileID"] . '">';
    echo '<button class="deleteBtn" title="Delete this file" href="deleteFile.php?fileid=' . $row["fileID"] . '">';
    echo '<img style="width: 40px" src="./images/x.png" data-pin-nopin="true"/>';
    echo '</button>';
    echo '</a>';
    echo '<a href="javascript:showLyrics(' . $row["fileID"] . ')" style="text-decoration:none;">';
    echo '<div class="panel panel-primary">';
    echo '<div class="panel-heading"><h4 style="display: inline-block;">' . $row["fileName"] . '</h4>';
    echo '<span style="    float: right; font-size: 30px;">'. $row["fileID"] . '</span> </div>';
    echo '<div class="panel-body">';
    echo '<div style="float: left"><p>' . $row["songAuthor"] . ' - ' . $row["songName"] . '</p>';
    echo '<p>' . $row["songDate"] . '</p>';
    echo '<p>' . $row["songSummary"] . '</p></div>';
    echo '<img style="width:100px; float:right;" src="' . $row["songPic"] . '"/>';

    echo '</div>';
    echo '</div>';
    echo '</a>';
    echo '</div>';

}

echo '<script type="text/javascript">
        function showLyrics(value) {
              window.open("includes/getLyrics.php?file="+value+"", "", "width=700,height=800");
        }

        function deleteFile(value){
                $.post("http://localhost:8080/Retrieval/Retrieval/deleteFile.php?filename="+value+"");

                $.post("http://localhost:8080/Retrieval/Retrieval/includes/createTables.php");
                window.open(files.html,"_self");
                window.location.reload(true);

        }
    </script>';

// close sql connection
mysqli_free_result($result);
mysqli_close($connection);
//header('Location: ../intro.html');
?>
