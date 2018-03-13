<?php
function getSearchPostingFile()
{
    // Get Search Input & find
    if (isset($_GET['searchInput'])) {
        $word = $_GET['searchInput'];

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


    $word = str_replace("not ", "!", $word);
    $word = str_replace("(", "", $word);
    $word = str_replace(")", "", $word);


    $rows = explode(' ', $word);

    $numOfWords = count($rows);

    $isRedundantOperator = $numOfWords % 2 ==0;

    $query="";


    foreach ($rows as $k => $v) {
        if ($k % 2 == 0) {
            $isNot = $v[0] == "!";
            if ($isNot) {
                $query .= "word NOT LIKE '%" .$v ."%' ";
            }
            else{
                $query .= "word LIKE '%" .$v ."%' ";
            }
        }
        else {
            if ($isRedundantOperator && $k+1 == $numOfWords) {continue;}

            else if ($v == "and" || $v == "^" ) {
                $query .= "AND ";
            }
            else  if ($v == "or" || $v == "||") {
                $query .= "OR ";
            }
        }
    }


    $replace = " REPLACE(word, ' ', '') ";
    $queryToEx = "select
			word as Word,
			offset as Offset,
            fileNo as FileNum ,
            sum(hits) as HitsFromFile
            from Hits
            where " . $query . "
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

    while ($row = mysqli_fetch_array($result)) {
        $i = $row["FileNum"];

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
