<p id='Tableheadline'>List View</p>
<table>
    <tr>
        <?php $nr=0; for ($x=1;$x<=$rows;$x++) { echo("<th>$column[$nr]</th>"); $nr++; } ?>
        <th>Verktøy</th>
    </tr>
    <?php
    $result = $dbApi->getAllRows("$valgt_table");
    while($row = mysqli_fetch_row($result)) {
        echo ("<tr><form action='' method='post' id='ListForm' name='ListForm'>");
        $antallRader3=count($row);
        $nr=0;
        for ($x=1;$x<=$antallRader3;$x++) {
            echo ("<td>$row[$nr]</td>");
            $nr++;
        }
        echo ("<td><input type='submit' value='Edit' name='editknapp' id='editknapp' onclick='edit()'>
                            <input type='submit' value='Delete' name='deleteknapp' id='deleteknapp' onclick=\"return confirm('Er du sikker på du vil slette denne raden?')\">
                          </td><input type='hidden' size='2' name='rowID' value='$row[0]'></form></tr>");
    }
    ?>
    <tr>
        <form action="<?PHP echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method='post' id='addForm' name='addForm'>
            <td>
            </td>
            <?php
            $nr=1;
            for ($x=2;$x<=$rows;$x++) {
                if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID" || $column[$nr]=="RoomID" || $column[$nr]=="HotelRoomTypeID") {
                    $foreigntable= substr($column[$nr], 0, -2);
                    print ("<td><select name='$column[$nr]' type='input'>");
                    $result = $dbApi->getAllRows("$foreigntable");
                    if ($column[$nr]=="RoomID") {
                        echo("<option value='0'>-</option>");
                    }
                    while($row = mysqli_fetch_row($result)) {
                        if ($column[$nr]=="RoomID") {
                            echo("<option value=$row[0]");
                            if ($row[0]==@$inputa[$nr]) {
                                echo(" selected");
                            }
                            echo(">$row[0] ($row[1]) - ");
                            $resultx = $dbApi->getRow("HotelRoomType", $row[2]);
                            while($rowx = mysqli_fetch_row($resultx)) {
                                $result2 = $dbApi->getRow("RoomType", $rowx[1]);
                                while($row2 = mysqli_fetch_row($result2)) {
                                    echo "$row2[1] ";
                                }
                                $result3 = $dbApi->getRow("Hotel", $rowx[2]);
                                while($row3 = mysqli_fetch_row($result3)) {
                                    echo "($row3[1])";
                                }
                                echo("</option>");
                            }
                        } else if ($column[$nr]=="HotelRoomTypeID") {
                            echo("<option value=$row[0]");
                            if ($row[0]==@$inputa[$nr]) {
                                echo(" selected");
                            }
                            echo(">$row[0] - ");
                            $result2 = $dbApi->getRow("RoomType", $row[1]);
                            while($row2 = mysqli_fetch_row($result2)) {
                                echo "$row2[1] ";
                            }
                            $result3 = $dbApi->getRow("Hotel", $row[2]);
                            while($row3 = mysqli_fetch_row($result3)) {
                                echo "($row3[1])";
                            }
                            echo("</option>");
                        } else {
                            echo("<option value=$row[0]");
                            if ($row[0]==@$inputa[$nr]) {
                                echo(" selected");
                            }
                            echo(">$row[0] - $row[1]</option>");
                        }
                    }
                    print ("</select></td>");
                } elseif ($column[$nr]=="FromDate") {
                    print ("<td><input size='9' name=$column[$nr] id='startDateInput' type='input' value='" . @$inputa[$nr] . "' readonly required size='6'></td>");
                } elseif ($column[$nr]=="ToDate") {
                    print ("<td><input size='9' name=$column[$nr] id='endDateInput' type='input' value='" . @$inputa[$nr]. "' readonly required></td>");
                } else {
                    print ("<td><input size='1' name='$column[$nr]' type='input' value='" . @$inputa[$nr] . "' ></td>");
                }
                $nr++;
            }
            print ("<td><input type='submit' value='Add' name='addknapp' id='addknapp'></td></form><tr></table>");
            echo "<div id='feedback'>";
            if (isset($errorMsg) && $errorMsg && @$_POST['addknapp']) {
                echo "<p style='color: red;'>";
                echo htmlspecialchars(@$errorMsg);
                echo "</p>";
            }
            if (isset($successMsg) && $successMsg && @$_POST['addknapp'] || @$_POST['deleteknapp']) {
                echo "<p style='color: green;'>";
                echo htmlspecialchars(@$successMsg);
                echo "</p>";
            }
            echo "</div>";
            ?>
