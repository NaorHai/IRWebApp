<?php
function getPostingFile($word)
{
    include('connection.php');

    if (isset($_GET['searchId'])) {
        $id = $_GET['searchId'];
        // make query to sql hits table
        $mainTable = "SELECT word
            FROM Hits
            WHERE id='" . $id . "'";

        // send query to sql hits table
        $result = mysqli_query($connection, $mainTable) or die(mysqli_error());

        while ($row = mysqli_fetch_array($result)) {
            echo '<h1 style="color: #ffffff;
                        text-align: center;
                        font-family: Levenim MT , arial;
                        font-weight: normal; ">Appearances of the Word "' . $row["word"] . '"</h1>';
            $word = $row["word"];
        }
    } else return;


    // make query to sql hits table
    $postingFileSql = "select
            fileNo as FileNum ,
            sum(hits) as Hits
            from Hits
            where word='" . $word . "'
            group by fileNo";

    // make query to sql hits table
    $mainTable = "SELECT id,fileNo,word,offset
        FROM Hits
        WHERE word='" . $word . "'";

    // send query to sql hits table
    $result = mysqli_query($connection, $postingFileSql) or die(mysqli_error());


    echo '<table style="margin: 0px auto; border-collapse: collapse; cellspacing="0" cellpadding="0";">';

    echo '<tr><a href="#">';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 90px; background: black; "><span style="font-weight:bold;">FileNum</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 170px; background:black; "><span style="font-weight:bold;">Hits</span></td>';
    echo '</a></tr>';

    // print all sql data rows
    while ($row = mysqli_fetch_array($result)) {
        $fileNo = $row['FileNum'];
        $hits = $row['Hits'];
        echo '<tr><a href="#">';

        echo '<td style="border-bottom:1px solid white; padding-left:15px; font-size: 17px; color: #fff; height: 40px; width: 70px; background: black;">' . $fileNo . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black; ">' . $hits . '</td>';

        echo '</a></tr>';
    }
    echo '</table>';

    // close sql connection
    mysqli_free_result($result);
    mysqli_close($connection);

    searchWord2($word);
}


function searchWord2($word)
{
    // craate sql connection
    include('connection.php');

    $counter = 0;

    // make query to sql hits table
    $mainTable = "SELECT id,fileNo,word,offset
        FROM Hits
        WHERE word='" . $word . "'";

    // send query to sql hits table
    $result = mysqli_query($connection, $mainTable) or die("<h2 style='font-family: Levenim MT , arial; color : aliceblue;'>Error : " . mysqli_error($connection) . "<br>" . $mainTable . "</h2>");

    $num_rows = mysqli_num_rows($result);

    echo "<h2 style='font-family: Levenim MT , arial;
    color : aliceblue; font-size: 25px; margin-left: 380px; font-weight: 100; color: #fff; margin-top: 30px;'>" . $num_rows . " Results Found</h2>";

    // print first table row
    echo '<table style="margin: 0px auto; border-collapse: collapse; cellspacing="0" cellpadding="0";">';

    echo '<tr><a href="#">';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 90px; background: black;"><span style="font-weight:bold;">#</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 170px; background:black; "><span style="font-weight:bold;">Word</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 150px; background:black; "><span style="font-weight:bold;">File No</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 150px; background:black; "><span style="font-weight:bold;">Word No</span></td>';
    echo '</a></tr>';

    // print all sql data rows
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr><a href="#">';

        echo '<td style="border-bottom:1px solid white; padding-left:15px; font-size: 17px; color: #fff; height: 40px; width: 70px; background: black;">' . $row["id"] . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black; ">' . $row["word"] . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black; ">' . $row["fileNo"] . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black; ">' . $row["offset"] . '</td>';

        echo '</a></tr>';
    }
    echo '</table>';
    // close sql connection
    mysqli_free_result($result);
    mysqli_close($connection);
}


$word = "";
getPostingFile($word);

?>
