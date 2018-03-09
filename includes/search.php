<?php

function searchWord()
{
    // create sql connection
    include('connection.php');

    $queryToEx = "SELECT id, fileNo, word, offset FROM Hits ";

    // send query to sql hits table
    $result = mysqli_query($connection, $queryToEx) or die("<h2 style='font-family: Levenim MT , arial; color : aliceblue;'>Error : " . mysqli_error($connection) . "<br>" . $queryToEx . "</h2>");
    $num_rows = mysqli_num_rows($result);

    echo "<h2 style='font-family: Levenim MT , arial;
    color : aliceblue; font-size: 25px; margin-left: 400px; font-weight: 300; margin-top: 30px; margin-bottom: 20px;'>" . $num_rows . " Documents Found</h2>";

    // print first table row
    echo '<table style="margin: 0px auto; border-collapse: collapse; cellspacing="0" cellpadding="0";">';

    echo '<tr><a href="#">';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 90px; background: black"><span style="font-weight:bold;">#</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 170px; background:black "><span style="font-weight:bold;">Word</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 150px; background:black "><span style="font-weight:bold;">File No</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 150px; background:black "><span style="font-weight:bold;">Word No</span></td>';
    echo '</a></tr>';

    // print all sql data rows
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr><a href="#">';

        echo '<td style="border-bottom:1px solid white; padding-left:15px; font-size: 17px; color: #fff; height: 40px; width: 70px; background: black">' . $row["id"] . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black ">' . $row["word"] . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black ">' . $row["fileNo"] . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black ">' . $row["offset"] . '</td>';

        echo '</a></tr>';
    }
    echo '</table>';
    // close sql connection
    mysqli_free_result($result);
    mysqli_close($connection);
}

// start
searchWord();

?>
