<?php
@$editknapp=$_POST ["editknapp"];
if ($editknapp) {
    echo("<script type='text/javascript'>");
    echo("document.getElementById('popup').style.visibility = 'visible';");
    echo("document.getElementById('overlaypopup').style.visibility = 'visible';");
    echo("</script>");
    print("<h3>Edit View</h3><br><table><tr>");
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
                    if ($row2[0] == $row[$nr]) {
                        echo("selected");
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
                    if ($row2[0] == $row[$nr]) {
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
                    if ($row2[0] == $row[$nr]) {
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
                print ("<td><input size='9' name=$column[$nr] id='editstartDateInput' type='text' value='$row[$nr]' readonly required></td>");
            } else if ($column[$nr] == "ToDate") {
                print ("<td><input size='9' name=$column[$nr] id='editendDateInput' type='text' value='$row[$nr]' readonly required></td>");
            } else {
                echo("<td><input type='text' type='text' name=$column[$nr] value='$row[$nr]' required></td>");
            }
            $nr++;
        }
        echo("<td><input type='submit' value='Update' name='updateknapp' id='updateknapp'>
                            <input type='hidden' name='rowID' value=$row[0]>
                          </td></form></tr></table><br><script>function closepopup() {
                                document.getElementById('popup').style.visibility = 'hidden';
                                document.getElementById('overlaypopup').style.visibility = 'hidden';
                            }
                            </script>


                          <input type='button' value='Exit' onclick='closepopup()'>");
    }
    if (isset($errorMsg) && $errorMsg) {
        echo "<p style=\"color: red;\">*", htmlspecialchars($errorMsg), "</p>\n\n";
    }
}

