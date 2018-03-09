<?php

function getInvertedTable()
{
    // Init Sorting
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == "hits")
            $sort = "sum(hits) desc";
        if ($_GET['sort'] == "word")
            $sort = "word";
        if ($_GET['sort'] == "id")
            $sort = "ID asc";
    } // Default Sorting
    else $sort = "ID asc";


    // craate sql connection
    include('connection.php');

    // make query to sql hits table
    $mainTable = "select
           DISTINCT (word) as Keyword, 
            (SELECT MAX(id)) as ID,
            group_concat(DISTINCT fileNo separator ',') as FromFiles,
            sum(hits) as TotalHits
            from Hits where isStopList = 0
            group BY Keyword
            order by ' . $sort . '";
    //order by ".$sort." desc";

    // send query to sql hits table
    $result = mysqli_query($connection, $mainTable) or die(mysqli_error($connection));

    // print first table row
    echo '<table style="font-family: Levenim MT , arial; margin: 0px auto; border-collapse: collapse; cellspacing="0" cellpadding="0";">';

    echo '<tr>';
//    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 90px; background: black "><span style="font-weight:bold;">#</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 170px; background: black "><span style="font-weight:bold;">KeyWord</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 150px; background: black "><span style="font-weight:bold;">From X Files</span></td>';
    echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 20px; color: #fff; height: 40px; width: 150px; background: black "><span style="font-weight:bold;">Total Hits</span></td>';
    echo '</a></tr>';

    echo '<script type="text/javascript">
                function doSomething(value) {
                    var iframe = document.createElement("iframe");
                    iframe.id = "iframeSearch";
                    iframe.frameBorder = "0";
                    iframe.height = "100%";
                    iframe.width = "100%";
                    var html = "postingFile.php?searchId="+value+"";
                    iframe.src = encodeURI(html);
                    console.log("iframe: "+iframe.src);
                    document.body.appendChild(iframe); window.open("postingFile.php?searchId="+value+"", "_self", "");
                }
            </script>';

    // print all sql data rows
    while ($row = mysqli_fetch_array($result)) {
        $ID = $row['ID'];
        $KeyWord = (string)$row['Keyword'];
        $FromFiles = $row['FromFiles'];
        $TotalHits = $row['TotalHits'];
        $temp = &$ID;
        echo '<tr onclick="Javascript:doSomething(' . $temp . ')">';

//        echo '<td style="border-bottom:1px solid white; padding-left:15px; font-size: 17px; color: #fff; height: 40px; width: 70px; background: black">' . $ID . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black ">' . $KeyWord . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black ">' . $FromFiles . '</td>';
        echo '<td style="border-bottom:1px solid white; padding-left:20px; font-size: 17px; color: #fff; height: 40px; width: 100px; background:black ">' . $TotalHits . '</td>';

        echo '</tr>';
    }
    echo '</table>';

    // close sql connection
    mysqli_free_result($result);
    mysqli_close($connection);


}


// start
getInvertedTable();

?>
