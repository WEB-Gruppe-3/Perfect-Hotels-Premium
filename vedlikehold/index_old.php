<?php
    session_start();

    // Check if we're logged in
    if ($_SESSION['loggedin'] != 1) {
        // If not, send user back to login page.
        header("Location: login.php");
        exit;
    }

    require_once("template/start.php");
    require_once("../nettsted/php/classes/DBConnector.php");
    require_once("../nettsted/php/classes/Database.php");

    $dbCon = new DBConnector();
    $dbApi = new Database();

    @$valgt_table=$_POST ["table_name"];
    if (!$valgt_table) {
        $valgt_table=$_SESSION["table_name"]; 
    }

    // store session data
    $_SESSION["table_name"] = "$valgt_table";

    @$id = $_POST['rowID'];
    $column = array();
    $input = array();
    $data = array();?>

<div id="content">
    <div id="innholdLeft">
        <form method="post" action="" id="velgtabellknapp" name="velgtabellknapp">
        <h3>Velg tabell</h3>
        <select name='table_name' id='table_list'>
        <?php
            $result = $dbApi->getTableNames();
            while($row = mysqli_fetch_row($result)) {
                    echo("<option value='" . $row[0] . "'");
                    if ($row[0]==$valgt_table) {
                        echo("selected"); 
                    }
                    echo(">" . $row[0] . "</option>");
                }
        ?>
        </select><input type='submit' value='OK' name='velgtabellknapp' id='velgtabellknapp'></form></div>
    <div id="innholdRight">
        <?php
            @$velgtabellknapp=$_POST ["velgtabellknapp"]; 
            if ($velgtabellknapp || $valgt_table) {
                $result = $dbApi->getColumnNames("$valgt_table");
                print("<table><tr>");
                while($row = mysqli_fetch_row($result)) {
                    echo("<th>" . $row[0] . "</th>");
                    $column[]=$row[0]; 
                }
                print("</tr>");
                print("<tr>");
                $result = $dbApi->getAllRows("$valgt_table");
                while($row = mysqli_fetch_row($result)) {
                    echo ("<tr><form action='' method='post' id='ListForm' name='ListForm'>");
                    $antallRader3=count($row);
                    $nr=0;
                    for ($x=1;$x<=$antallRader3;$x++) {
                        echo ("<td>" . $row["$nr"] . "</td>");
                        $nr++;
                     }
                    echo ("<td><input type='submit' value='Edit' name='editknapp' id='editknapp'>
                            <input type='submit' value='Delete' name='deleteknapp' id='deleteknapp'>
                            
                            
                          </td><input type='hidden' size='2'name='rowID' value='" . $row[0] . "'></form></tr>");
                }

                print ("<tr><form action='");
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                print ("' method='post' id='addForm' name='addForm'><td></td>");
                $nr=1;
                for ($x=2;$x<=$antallRader3;$x++) {
                    if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID") {
                        $foreigntable= substr($column[$nr], 0, -2);
                        print ("<td><select name=$nr type='input'>");
                        $result = $dbApi->getAllRows("$foreigntable");
                        while($row = mysqli_fetch_row($result)) {
                            echo("<option value=$row[0]>$row[0] - $row[1]</option>");
                        }
                        print ("</select></td>");
                    }
                    else if ($column[$nr]=="RoomID") {
                        $foreigntable= substr($column[$nr], 0, -2);
                        print ("<td><select name=$nr type='input'>");
                        echo("<option value='NULL'>-</option>");
                        $result = $dbApi->getAllRows("$foreigntable");
                        while($row = mysqli_fetch_row($result)) {
                            echo("<option value=$row[0]>$row[0] ($row[1]) - ");
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
                        }
                        print ("</select></td>");
                    }
                    else if ($column[$nr]=="HotelRoomTypeID") {
                        $foreigntable= substr($column[$nr], 0, -2);
                        print ("<td><select name=$nr type='input'>");
                        $result = $dbApi->getAllRows("$foreigntable");
                        while($row = mysqli_fetch_row($result)) {
                            echo("<option value=$row[0]>$row[0] - ");

                            $result2 = $dbApi->getRow("RoomType", $row[1]);
                            while($row2 = mysqli_fetch_row($result2)) {
                                echo "$row2[1] ";
                            }
                            $result3 = $dbApi->getRow("Hotel", $row[2]);
                            while($row3 = mysqli_fetch_row($result3)) {
                                echo "($row3[1])";
                            }
                            echo("</option>");
                        }
                        print ("</select></td>");
                    }
                    else if ($column[$nr]=="FromDate") {
                        print ("<td><input size='9' name=$nr id='startDateInput' type='text' readonly required></td>");
                    }
                    else if ($column[$nr]=="ToDate") {
                        print ("<td><input size='9' name=$nr id='endDateInput' type='text' readonly required></td>");
                    }
                    else {
                        print ("<td><input size='1' name=$nr type='input'></td>");
                    }
                    $nr++;
                }
                print ("<td><input type='submit' value='Add' name='addknapp' id='addknapp'></td><input type='hidden' name='table_name' value='$valgt_table'></form><tr></table>");
            }

            @$addknapp=$_POST ["addknapp"];
            if ($addknapp) {
                $rows=count($column);
                $nr="1";
                for ($x=1;$x<=$rows;$x++) {
                    $input[] = $_POST[$nr];
                    $nr++;
                }
                $nr="1";
                $nr2="0";
                for ($x=2;$x<=$rows;$x++) {
                    $data[$column[$nr]] = $input[$nr2];
                    $nr++;
                    $nr2++;
                }
                $result = $dbApi->doesRowExist($valgt_table, $data);
                if($result) {
                    echo("<p style='color:red'><strong>There was already a row containing this.. </strong></p>");
                }
                else {
                    $result2 = $dbApi->insertRow($valgt_table, $data);
                    if($result2) {
                      /*echo ("<br><span style='color:limegreen'>Successfully inserted row in table $valgt_table!</span>");*/
                        echo ("<meta http-equiv='refresh' content='0'>");
                    }
                    else {
                        echo ("<br><span style='color:red'><strong>Inserting of row into table $valgt_table FAILED!</strong></span>");
                    }
                }
            }

            @$updateknapp=$_POST ["updateknapp"];
            if ($updateknapp) {
                $rows=count($column);
                $nr="1";
                for ($x=1;$x<=$rows;$x++) {
                    $input[] = $_POST[$nr];
                    $nr++;
                }
                $nr="1";
                $nr2="0";
                for ($x=2;$x<=$rows;$x++) {
                    $data[$column[$nr]] = $input[$nr2];
                    $nr++;
                    $nr2++;
                }
                $result = $dbApi->doesRowExist($valgt_table, $data);
                if($result && $valgt_table != "Booking") {
                    echo ("<p style='color:red'><strong>There was already a row in $valgt_table containing this.. </strong></p>");
                }
                else {
                    $result2 = $dbApi->updateRow($valgt_table, $id, $data);
                    if($result2) {
                      /*echo ("<br><span style='color:limegreen'>Successfully updated row in table $valgt_table!</span>");*/
                        echo ("<meta http-equiv='refresh' content='0'>");
                    }
                    else {
                        echo ("<br><span style='color:red'><strong>Updating of row into table $valgt_table FAILED!</strong></span>");
                    }
                }

            }
            @$deleteknapp=$_POST ["deleteknapp"];
            if ($deleteknapp) {
                $result = $dbApi->deleteRow($valgt_table, $id);
                if($result) {
                  /*echo("<br><span style='color:limegreen'>Successfully deleted row in table $valgt_table!");*/
                    echo ("<meta http-equiv='refresh' content='0'>");
                }
                else {
                    echo("<br><span style='color:red'><strong>Deleting of row ($id) in table $valgt_table FAILED!</strong></span>");
                }
            }
        ?></div>
    <div id="overlaypopup"></div>
    <div id="popup">
        <?php 
            @$editknapp=$_POST ["editknapp"];
            if ($editknapp) {
                echo ("<script type='text/javascript'>");
                echo ("document.getElementById('popup').style.visibility = 'visible';");
                echo ("document.getElementById('overlaypopup').style.visibility = 'visible';");
                echo ("</script>");
                $result = $dbApi->getColumnNames("$valgt_table");
                print("<h3>Edit View</h3><br><table><tr>");
                while($row = mysqli_fetch_row($result)) {
                    echo("<th>" . $row[0] . "</th>");                
                }
                print("</tr>");
                $result = $dbApi->getRow($valgt_table, $id);
                while($row = mysqli_fetch_row($result)) {
                    echo ("<tr><form action='' method='post' id='EditForm' name='EditForm'>");
                    $rows=count($column);
                    $nr=0;
                    for ($x=1;$x<=$rows;$x++) {
                        if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID" ) {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");
                            $result2 = $dbApi->getAllRows("$foreigntable");
                            while($row2 = mysqli_fetch_row($result2)) {
                                echo("<option value='" . $row2[0] . "'");
                                if ($row2[0]==$row[$nr]) {
                                    echo("selected");
                                }
                                echo(">$row2[0] - $row2[1]</option>");
                            }
                            print ("</select></td>");
                        }
                        else if ($column[$nr]=="RoomID") {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");
                            echo("<option value='NULL'>-</option>");
                            $result2 = $dbApi->getAllRows("$foreigntable");
                            while($row2 = mysqli_fetch_row($result2)) {
                                echo("<option value=$row2[0]");
                                if ($row2[0]==$row[$nr]) {
                                    echo(" selected");
                                }
                                echo(">$row2[0] ($row2[1]) - ");
                                $resultx = $dbApi->getRow("HotelRoomType", $row2[2]);
                                while($rowx = mysqli_fetch_row($resultx)) {
                                    $result4 = $dbApi->getRow("RoomType", $rowx[1]);
                                    while($row4 = mysqli_fetch_row($result4)) {
                                        echo "$row4[1] ";
                                    }
                                    $result3 = $dbApi->getRow("Hotel", $rowx[2]);
                                    while($row3 = mysqli_fetch_row($result3)) {
                                        echo "($row3[1])";
                                    }
                                    echo("</option>");
                                }
                            }
                            print ("</select></td>");
                        }
                        else if ($column[$nr]=="HotelRoomTypeID") {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");
                            $result2 = $dbApi->getAllRows("$foreigntable");
                            while($row2 = mysqli_fetch_row($result2)) {
                                echo("<option value=$row2[0]");
                                if ($row2[0]==$row[$nr]) {
                                    echo(" selected");
                                }
                                echo(">$row2[0] - ");

                                    $result4 = $dbApi->getRow("RoomType", $row2[1]);
                                    while($row4 = mysqli_fetch_row($result4)) {
                                        echo "$row4[1] ";
                                    }
                                    $result3 = $dbApi->getRow("Hotel", $row2[2]);
                                    while($row3 = mysqli_fetch_row($result3)) {
                                        echo "($row3[1])";
                                    }
                                    echo("</option>");

                            }
                            print ("</select></td>");
                        }

                        else if ($column[$nr]=="ID") {
                            echo("<td>" . $row["$nr"] . "</td>");
                        }

                        else if ($column[$nr]=="FromDate") {
                            print ("<td><input size='9' name=$nr id='editstartDateInput' type='text' value='$row[$nr]' readonly required></td>");
                        }
                        else if ($column[$nr]=="ToDate") {
                            print ("<td><input size='9' name=$nr id='editendDateInput' type='text' value='$row[$nr]' readonly required></td>");
                        }

                        else {
                            echo ("<td><input type='text' type='text' name='$nr' value='" . $row["$nr"] . "'></td>");
                        }
                        $nr++;
                    }
                    echo ("<td><input type='submit' value='Update' name='updateknapp' id='updateknapp'>
                            <input type='hidden' name='rowID' value=$row[0]>
                          </td></form></tr></table><br>
                            
                            <script>function closepopup() {
                                document.getElementById('popup').style.visibility = 'hidden';
                                document.getElementById('overlaypopup').style.visibility = 'hidden';
                            }
                            </script>


                          <input type='button' value='Exit' onclick='closepopup()'>");
                }        
            }       
        ?>
    </div>

    <!-- Hidden element with session id -->
    <form id="hiddenFormSessionID" hidden><?php print(session_id()) ?></form>

</div> <!-- Content end -->

    <script src="../nettsted/js/jquery-1.11.3.js"></script>
    <script src="../nettsted/js/jquery-ui-1.11.4/jquery-ui.js"></script>
    <script src="../nettsted/js/jquery-ui-1.11.4/datepicker-no.js"></script>
    <link rel="stylesheet" href="../nettsted/js/jquery-ui-1.11.4/jquery-ui.css">
    <script src="js/index.js"></script>

<?php require_once("template/end.html") ?>