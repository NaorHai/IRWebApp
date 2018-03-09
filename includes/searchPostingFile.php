<?php
function getSearchPostingFile()
{
    // Get Search Input & find
    if (isset($_GET['searchInput'])) {
        $word = $_GET['searchInput'];
        //echo $word;
        $chars = str_split($word);

        if ($chars[0] == '"') {
            $isStopList = 1;
            for ($i = 1; $i < count($chars) - 1; $i++)
                $array[$i - 1] = $chars[$i];
            $word = implode($array);
        } else
            $isStopList = 0;
    } else {
        echo "<h2 style='color:white;'>Can't fetch word</h2>";
        return;
    }
    $bla = "bla";
    $rows = explode(" ", $word);


    $numOfWords = count($rows);
    //$isStopList = 0;
    $replace = " REPLACE(word, ' ', '') ";
    $queryToEx = "select
			word as Word,
			offset as Offset,
            fileNo as FileNum ,
            sum(hits) as HitsFromFile
            from Hits
            where " . $replace . " = '" . $word . "'
            AND isStopList=" . $isStopList . "
            group by FileNum, id
            order by FileNum";

    // connect
    include('connection.php');

    $getWordId = "select id from Hits where REPLACE(word, ' ', '') = '" . $word . "' LIMIT 1 ";
    $result = mysqli_query($connection, $getWordId);
    while ($ID = mysqli_fetch_array($result)) {
        $wordId = $ID["id"];
    }

    if ($numOfWords == 1) {
        // if the word is different from OR, AND, NOT
        if (strcmp($rows[0], "OR") != 0
            && strcmp($rows[0], "AND") != 0
            && strcmp($rows[0], "NOT") != 0) {
            //$queryToEx .= " WHERE ".$replace.' ="'.$word.'" ;
            // debbuging
            //echo "<Br><H2 style='color:white; font-size: 20px;'>".$queryToEx."</h2>";
        }
    } else if ($numOfWords == 2) {
        // if $rows[0] == "NOT"
        if (strcmp($rows[0], "NOT") == 0) {
            //  "NOT" statement was found
            //$queryToEx .= " WHERE NOT ".$replace.' ="'.$rows[1].'"  '.$andStopList.' ';
            // ****************************************************
            // gal: need to fix this :
            //notStatement($rows[1] , 0);
            //return;
        } else {
            //echo "<Br><H2 style='color:white; font-size: 20px;'>Please provide another values</h2>";
        }
    } else if ($numOfWords == 3) {
        if (strcmp($rows[1], "AND") == 0 || strcmp($rows[1], "&&") == 0) {
            // ****************************************************
            // gal: need to fix this :
            // andStatement($rows[0] , $isStopListA , $rows[2] , $isStopListB);
            // return;
        } else if (strcmp($rows[1], "OR") == 0 || strcmp($rows[1], "||") == 0 || strcmp($rows[1], "|") == 0) {
            // ****************************************************
            // gal: need to fix this :
            //orStatement($rows[0] , $isStopListA , $rows[2] , $isStopListB);
            // return;
        } else {
            //echo "<Br><H2 style='color:white; font-size: 20px;'>Please provide another values</h2>";
        }
    }


    $tdOpen = '<td style="padding-left:20px; border-bottom:1px solid white; font-size: 20px; color: #fff; height: 40px; width: 130px; background: black; "><span style="font-family: "Levenim MT" , arial;">';

    $tdOpenID = '<td style="padding-left:20px; border-bottom:1px solid white; font-size: 20px; color: #fff; height: 40px; width: 130px; background: black; "><span id="ffn" style="font-family: "Levenim MT" , arial;">';


    $tdClose = '</span></td>';

    // connect
    include('connection.php');


    // send query to sql hits table
    $result = mysqli_query($connection, $queryToEx)
    or die("<h2 style='font-family: Levenim MT , arial; color : aliceblue;'>Error : " . mysqli_error($connection) . "<br>" . $queryToEx . "</h2>");

    // count the rows in the results
    $num_rows = mysqli_num_rows($result);


    // headline
    if ($num_rows == 0) {
        echo "<h2 style='font-family: Levenim MT , arial;
            color : black;  font-size: 25px; text-align: center; font-weight: 900; margin-top: 30px; margin-bottom: 20px;'>No Results</h2>";
        return;
    } else {
        echo "<h2 style='font-family: Levenim MT , arial;
            color : aliceblue; font-size: 25px; text-align:center; font-weight: 300;'>" . $num_rows . " Documents Found, Click on the row in order to open the Player</h2>";
    }


    // table definition
    echo '<table style="margin: 0px auto; border-collapse: collapse; cellspacing="0" cellpadding="0";">';

    // first table row print
    echo '<tr ><a href="#">';
    {
        echo $tdOpen . ' Word ' . $tdClose;
        echo $tdOpen . ' From File No. ' . $tdClose;
        echo $tdOpen . ' Hits ' . $tdClose;
        echo $tdOpen . ' Offset ' . $tdClose;
    }
    echo '</a></tr>';

    $w = "";
    $i = "";
    $o = "";
    // table
    while ($row = mysqli_fetch_array($result)) {
        $w = $row["Word"];
        $i = $row["FileNum"];
        $o = $row["Offset"];

        echo '<tr style="cursor:pointer;" onclick="javascript:toFile(' . $i . ',' . $wordId . ');"><a href="#">';

        echo '<script type="text/javascript">
                function toFile(value, id){
                    path =  "../data/file"+value+".txt";
                    window.open("getLyrics.php?file="+value+"&word="+id+"", "", "width=700,height=800");
                }
             </script>';
        {
            echo $tdOpen . $row["Word"] . $tdClose;
            echo $tdOpenID . $row["FileNum"] . $tdClose;
            echo $tdOpen . $row["HitsFromFile"] . $tdClose;
            echo $tdOpen . $row["Offset"] . $tdClose;
        }
        echo '</a></tr>';
    }
    echo '</table>';

    //free memory
    mysqli_free_result($result);

    // close sql connection
    mysqli_close($connection);

}
// start
getSearchPostingFile();
?>
