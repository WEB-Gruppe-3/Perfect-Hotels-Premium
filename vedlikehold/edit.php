<div id="jsValidateFeedback">Placeholder JS validerings feedback text</div>

<?php
@$editknapp=$_POST ["editknapp"];
@$updateknapp=$_POST ["updateknapp"];
if ($editknapp || $updateknapp) {
    /*echo("<script type='text/javascript'>");
    echo("document.getElementById('popup').style.visibility = 'visible';");
    echo("document.getElementById('overlaypopup').style.visibility = 'visible';");
    echo("</script>");*/
    echo "<p id='editheadline'>Edit View</p>";
    print("<table><tr>");
    $nr = 0;
    for ($x = 1; $x <= $rows; $x++) {
        echo("<th>$column[$nr]</th>");
        $nr++;
    }
    print("</tr>");
    $result = $dbApi->getRow($valgt_table, $id);
    while ($row = mysqli_fetch_row($result)) {
        echo("<tr><form action='");
        echo htmlspecialchars($_SERVER['PHP_SELF']);
        echo("' method='post' id='EditForm' name='EditForm'>");
        $rows = count($column);
        $nr = 0;
        for ($x = 1; $x <= $rows; $x++) {
            if ($column[$nr] == "RoomTypeID" || $column[$nr] == "ImageID" || $column[$nr] == "HotelID" || $column[$nr] == "CustomerOrderID") {
                $foreigntable = substr($column[$nr], 0, -2);
                print ("<td><select name=$column[$nr] type='input'>");
                $result2 = $dbApi->getAllRows("$foreigntable");
                while ($row2 = mysqli_fetch_row($result2)) {
                    echo("<option value='$row2[0]'");
                    if ($row2[0] == @$inputu[$nr]) {
                        echo("selected");
                    }
                    if ($row2[0] == $row[$nr] && !@$inputu[$nr]) {
                        echo(" selected");
                    }
                    echo(">$row2[0] - $row2[1]</option>");
                }
                print ("</select></td>");
            } else if ($column[$nr] == "RoomID") {
                $foreigntable = substr($column[$nr], 0, -2);
                print ("<td><select name=$column[$nr] type='input'>");
                echo("<option value='0'>-</option>");
                $result2 = $dbApi->getAllRows("$foreigntable");
                while ($row2 = mysqli_fetch_row($result2)) {
                    echo("<option value=$row2[0]");
                    if ($row2[0] == @$inputu[$nr]) {
                        echo(" selected");
                    }
                    if ($row2[0] == $row[$nr] && !@$inputu[$nr]) {
                        echo(" selected");
                    }
                    echo(">$row2[0] ($row2[1]) - ");
                    $resultx = $dbApi->getRow("HotelRoomType", $row2[2]);
                    while ($rowx = mysqli_fetch_row($resultx)) {
                        $result4 = $dbApi->getRow("RoomType", $rowx[1]);
                        while ($row4 = mysqli_fetch_row($result4)) {
                            echo "$row4[1] ";
                        }
                        $result3 = $dbApi->getRow("Hotel", $rowx[2]);
                        while ($row3 = mysqli_fetch_row($result3)) {
                            echo "($row3[1])";
                        }
                        echo("</option>");
                    }
                }
                print ("</select></td>");
            } else if ($column[$nr] == "HotelRoomTypeID") {
                $foreigntable = substr($column[$nr], 0, -2);
                print ("<td><select name=$column[$nr] type='input'>");
                $result2 = $dbApi->getAllRows("$foreigntable");
                while ($row2 = mysqli_fetch_row($result2)) {
                    echo("<option value=$row2[0]");
                    if ($row2[0] == @$inputu[$nr]) {
                        echo(" selected");
                    }
                    if ($row2[0] == $row[$nr] && !@$inputu[$nr]) {
                        echo(" selected");
                    }
                    echo(">$row2[0] - ");

                    $result4 = $dbApi->getRow("RoomType", $row2[1]);
                    while ($row4 = mysqli_fetch_row($result4)) {
                        echo "$row4[1] ";
                    }
                    $result3 = $dbApi->getRow("Hotel", $row2[2]);
                    while ($row3 = mysqli_fetch_row($result3)) {
                        echo "($row3[1])";
                    }
                    echo("</option>");

                }
                print ("</select></td>");
            } else if ($column[$nr] == "ID") {
                echo("<td>$row[$nr]</td>");
            } else if ($column[$nr] == "FromDate") {
                if (!@$inputu[$nr]){
                    $fromdate=$row[$nr];
                } else {
                    $fromdate=$inputu[$nr];
                }
                print ("<td><input size='9' name=$column[$nr] id='editstartDateInput' type='text' value='$fromdate' readonly required></td>");
            } else if ($column[$nr] == "ToDate") {
                if (!@$inputu[$nr]){
                    $todate=$row[$nr];
                } else {
                    $todate=$inputu[$nr];
                }
                print ("<td><input size='9' name=$column[$nr] id='editendDateInput' type='text' value='$todate' readonly required></td>");
            } else {
                if (!@$inputu[$nr]){
                    $foo=$row[$nr];
                } else {
                    $foo=$inputu[$nr];
                }
                echo("<td><input type='text' type='text' name=$column[$nr] value='$foo' required></td>");
            }
            $nr++;
        }
        echo("<td><input type='submit' value='Update' name='updateknapp' id='updateknapp'><input type='submit' value='Cancel' name='cancelknapp' id='cancelknapp'><input type='hidden' name='rowID' value=$row[0]></td></form></tr></table>");
    }
    echo "<div id='feedback'>";
    if (isset($errorMsg) && $errorMsg && $updateknapp) {
        echo "<p style='color: red;'>";
        echo htmlspecialchars($errorMsg);
        echo "</p>";
    }
    if (isset($successMsg) && $successMsg && $updateknapp) {
        echo "<p style='color: green;'>";
        echo htmlspecialchars($successMsg);
        echo "</p>";
    }
    echo "</div>";
}

