<?php require_once("template/start.html");
require_once("../nettsted/php/classes/Database.php");
$dbApi = new Database();





function checkrooms($data) {
    $dbApi = new Database();
    //checking how many rooms there are of that type
    $rooms=0;
    $result = $dbApi->getALLRows("Room");
    while($row = mysqli_fetch_row($result)) {
        if ($row[2]==$data["HotelRoomTypeID"]){
            $rooms++;
        }
    }
    //getting data from all bookings with same HotelRoomTypeID, except for the one we are trying to edit.
    $fromdate = array();
    $todate = array();
    $bookingid = array();
    $result = $dbApi->getALLRows("Booking");
    while($row = mysqli_fetch_row($result)) {
        if ($row[0] != $data["ID"] && $row[4] == $data["HotelRoomTypeID"]) {
            $bookingid[]=$row[0];
            $fromdate[]=$row[1];
            $todate[]=$row[2];
        }
    }
    // Tar bort bookinger som er før eller etter gitt tidsramme
    $validbookings = array();
    $validfromdate = array();
    $validtodate = array();
    $rows=count($fromdate);
    $nr=0;
    for ($x=1;$x<=$rows;$x++) {
        if ($data["FromDate"] >= $todate[$nr] || $data["ToDate"] <= $fromdate[$nr]) {
//            echo "$bookingid[$nr]<br>";
        } else {
            $validbookings[]=$bookingid[$nr];
            $validfromdate[]=$fromdate[$nr];
            $validtodate[]=$todate[$nr];
        }
        $nr++;
    }
    //Fikser slik at hvis en rad slutter før en annen begynner blir den sett på som en oppføring.

    $rows=count($validbookings);
    $doublerooms=array();
    $nr=0;
    for ($x=1;$x<=$rows;$x++) {
        $nr2=0;
        for ($y = 1; $y <= $rows; $y++) {
            if ($validbookings[$nr2] == $validbookings[$nr2] ) {
//                echo "$nr : $nr2 Booking id:$validbookings[$nr2] $validfromdate[$nr] == $validtodate[$nr2]<br>";
                if ($validfromdate[$nr] == $validtodate[$nr2]){
                    $doublerooms[]=$bookingid[$nr2];
                    //echo "" . $fromdate[$nr] . " From<br>";
                    //echo "" . $todate[$nr2] . " To<br>";
                    //echo "" . $bookingid[$nr2] . " To<br>";
                }
            }
            $nr2++;
        }
        $nr++;
//        echo "<br>";
    }

    $clean=array();
    $rows=count($doublerooms);
    $nr=0;
    for ($x=1;$x<=$rows;$x++) {
        if (!in_array($doublerooms[$nr], $clean)) {
            $clean[] = $doublerooms[$nr];
        }
        $nr++;
    }


    $cleaned = count($clean);
    $validbooking = count($validbookings);

/*
    echo "Total rooms: $rooms <br>";
    echo "Valid booking: $validbooking <br>";
    echo "cleaned: $cleaned <br>";

    echo "<br> validbookings";
    print_r($validbookings);
    echo "<br>";
    print_r($doublerooms);
    echo "<br>";
    print_r($clean);
    echo "<br>";
    print_r($bookingid);
    echo "<br>";
    print_r($fromdate);
    echo "<br>";
    print_r($todate);
    echo "<br>";
    print_r($data);
*/
    $availablerooms = $rooms - ($validbooking-$cleaned);
//    echo "Available rooms: $availablerooms <br>";

    return $availablerooms;
}

?>
    <!-- Start of content -->
    <div id="content">
        <div id="innholdLeft">
            <h3>Vennligst fyll inn referanse kode for å endre din bestilling:</h3>
            <form method="post" action="" id="checkinform" name="checkinform">
                <input name="search" type="search" required>
                <input type='submit' value='OK' name='checkinbutton' id='checkinbutton'>
            </form>
            <br>
        </div>

        <div id="innholdRight">
            <?php
            @$checkinbutton=$_POST ["checkinbutton"];
            if ($checkinbutton) {
                @$input = $_POST['search'];
                // Trimming spaces from input
                $input = str_replace(" ", "", $input);

                $column='Reference';
                $tablename='CustomerOrder';
                $data[$column] = $input;
                $result = $dbApi->doesRowExist("CustomerOrder", $data);
                if($result) {
                    $result = $dbApi->getAllRows("CustomerOrder");
                    while($row = mysqli_fetch_row($result)) {
                        if ($row[1]==$input) {
                            $refid=$row[0];
                        }
                    }
                    $foo = array();
                    $bookid = array();
                    $startdate = array();
                    $todate = array();
                    $hotelroomtypeid = array();
                    $result = $dbApi->getALLRows("Booking");
                    while($row = mysqli_fetch_row($result)) {
                        if ($row[3]!="0"){
                            $foo[]=$row[3];
                        }
                        if ($row[5] == $refid) {
                            $bookid[] = $row[0];
                            $fromdate[] = $row[1];
                            $todate[] = $row[2];
                            $hotelroomtypeid[] = $row[4];
                        }
                    }
                    $nr=0;
                    $bookings=count($bookid);

                    for ($x=1;$x<=$bookings;$x++) {
                        $result = $dbApi->getRow("Booking", $bookid[$nr]);
                        while ($row = mysqli_fetch_row($result)) {
                            if ($row[3] == "0") {
                                echo("<table>");
                                echo("<form method='post' action='' id='roomform' name='roomform'><input name='Bookings' type='hidden' value='$bookings'>");
                                echo("<tr><td><strong>Bestilling $x: </strong><input name='Bestilling' type='hidden' value='$x'></td></tr>");
                                $result2 = $dbApi->getRow("hotelroomtype", $hotelroomtypeid[$nr]);
                                while ($row2 = mysqli_fetch_row($result2)) {
                                    $result3 = $dbApi->getRow("hotel", $row2[2]);
                                    while ($row3 = mysqli_fetch_row($result3)) {
                                        echo ("<tr><td>Hotellnavn: $row3[1]</td></tr>");
                                    }
                                    $result4 = $dbApi->getRow("roomtype", $row2[1]);
                                    while ($row4 = mysqli_fetch_row($result4)) {
                                        echo ("<tr><td>Romtype: $row4[1]</td></tr>");
                                    }
                                }
                                echo("<tr><td>Fra-Til: $fromdate[$nr] - $todate[$nr]<input name='ID' type='hidden' value='$bookid[$nr]'></td><td><input type='submit' value='Endre' name='endrebutton' id='endrebutton'><input type='submit' value='Slett' name='deleteknapp' id='deleteknapp' onclick=\"return confirm('Er du sikker på du vil slette bestillingen?')\"></td></tr>");
                                echo "</form>";

                            } else {
                                echo("<table>");
                                $id = $row[3];
                                $result = $dbApi->getRow("Room", $id);
                                while ($row = mysqli_fetch_row($result)) {
                                    print ("<tr><td><strong>Bestilling $x: </strong></td></tr>");
                                    echo("<tr><td><strong style='color:red'>Du har allerede booket romid: $row[1]</strong></td></tr>");
                                }
                            }
                        }
                        $nr++;
                    }
                    echo("</table>");
                    echo("<input name='ID' type='hidden' value='$refid'>");
                }
                else {
                    echo("<p style='color:Red'><strong>Kan desverre ikke finne referansekode din, er du sikker på du tastet riktig? </strong></p>");
                }
            }
            @$endrebutton=$_POST ["endrebutton"];
            if ($endrebutton) {
                $bookid=$_POST ["ID"];
                $bestilling=$_POST ["Bestilling"];
                $result = $dbApi->getRow("Booking", $bookid);
                while ($row = mysqli_fetch_row($result)) {
                    if ($row[3] == "0") {
                        $foo = array("ID" => "$row[0]", "FromDate" => "$row[1]", "ToDate" => "$row[2]", "RoomID" => "$row[3]", "HotelRoomTypeID" => "$row[4]", "CustomerOrderID" => "$row[5]");
                        $data = array( "ID" => "$row[0]", "FromDate" => "$row[1]", "ToDate" => "$row[2]", "HotelRoomTypeID" => "$row[4]" );
                        $result = $dbApi->getRow("HotelRoomType", $foo['HotelRoomTypeID']);
                        while ($row = mysqli_fetch_row($result)) {
                            $bar = array("ID" => "$row[0]", "RoomTypeID" => "$row[1]", "HotelID" => "$row[2]");
                        }
                        $result = $dbApi->getRow("Hotel", $bar['HotelID']);
                        while ($row = mysqli_fetch_row($result)) {
                            $hotel = $row[1];
                        }
                        $result = $dbApi->getRow("RoomType", $bar['RoomTypeID']);
                        while ($row = mysqli_fetch_row($result)) {
                            $roomtype = $row[1];
                        }
                        print ("Bestilling nummer $bestilling<br>");
                        print ("<table><form method='post' action='' id='editform' name='editform'><input type='hidden' name='BookingID' value=$foo[ID]>");
                        print ("<tr><td>Hotel</td>");
                        print ("<td><select name=HotelID type='input'>");
                        $result = $dbApi->getAllRows("Hotel");
                        while ($row = mysqli_fetch_row($result)) {
                            if ($row[0] == $bar[HotelID]) {
                                echo("<option value=$row[0] selected>$row[0] - $row[1] - $row[3]</option>");
                            } else {
                                echo("<option value=$row[0]>$row[0] - $row[1] - $row[3]</option>");
                            }
                        }
                        print ("</select></td></tr>");
                        print ("<tr><td>Romtype</td>");
                        print ("<td><select name=RoomTypeID type='input'>");
                        $result = $dbApi->getAllRows("RoomType");
                        while ($row = mysqli_fetch_row($result)) {
                            if ($row[0] == $bar[RoomTypeID]) {
                                echo("<option value=$row[0] selected>$row[0] - $row[1] - $row[2] Senger - $row[5]</option>");
                            } else {
                                echo("<option value=$row[0]>$row[0] - $row[1] - $row[2] Senger - $row[5]</option>");
                            }
                        }
                        print ("</select></td></tr>");
                        print ("<tr><td>From</td><td><input size='9' id='startDateInput' type='text' name='FromDate' value=$foo[FromDate] readonly required></td></tr>");
                        print ("<tr><td>To</td><td><input size='9' id='endDateInput' type='text' name='ToDate' value=$foo[ToDate] readonly required></td>");
                        print ("<td><input type='button' value='Sjekk' name='sjekkButton' id='sjekkButton'></td></tr>");
                        print ("<tr><td></td><td id='sjekkAvailRoomsMsg'></td><td><input type='submit' value='Oppdater' name='checkbutton' id='checkbutton' onclick=\"return confirm('Er du sikker på du vil endre bestillingen?')\"></td></tr></form></table><br>");
                    } else {
                        $id = $row[3];
                        $result = $dbApi->getRow("Room", $id);
                        while ($row = mysqli_fetch_row($result)) {
                            print ("Bestilling nummer $x<br>");
                            echo("<p style='color:red'><strong>Du har allerede booket rom $row[1], du kan ikke endre din bestilling.</strong></p>");
                        }
                    }
                }
            }


            @$checkbutton=$_POST ["checkbutton"];
            if ($checkbutton) {
                $hotel=$_POST ["HotelID"];
                $roomtype=$_POST ["RoomTypeID"];
                $newfromdate=$_POST ["FromDate"];
                $newtodate=$_POST ["ToDate"];
                $bookingid=$_POST ["BookingID"];
                $result = $dbApi->getAllRows("HotelRoomType");
                while($row = mysqli_fetch_row($result)) {
                    if ($row[1]==$roomtype AND $row[2]==$hotel) {
                        $hotelroomtype=$row[0];
                    }
                }
                $data = array( "ID" => "$bookingid", "FromDate" => "$newfromdate", "ToDate" => "$newtodate", "HotelRoomTypeID" => "$hotelroomtype" );
                $availablerooms = checkrooms($data);
                if ($availablerooms >= 1 ) {
                    $result = $dbApi->updateRow("Booking", $bookingid, $data);
                    if($result) {
                        echo("<p style='color:green'><strong>Your booking has changed succesfully</strong></p>");
                    }
                    else {
                        echo ("<br><span style='color:red'><strong>Oh noes! it FAILED!</strong></span>");
                    }
                }
                else {
                    echo ("<br><span style='color:red'><strong>Oh noes! There's no more available rooms left in this time period: $newfromdate - $newtodate </strong></span>");
                }
            }

            if (@$_POST['deleteknapp']) {
                $id=$_POST ["ID"];
                $result = $dbApi->deleteRow("booking", $id);
                if($result) {
                    echo ("<p style='color:green'><strong>Successfully deleted!</strong></p>");
                }
                else {
                    echo ("<br><span style='color:red'><strong>Deleting of row ($id) in table FAILED!</strong></span>");
                }
            }
            ?>
        </div>
    </div>
    <script src="js/jquery-1.11.3.js"></script>
    <script src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
    <script src="js/jquery-ui-1.11.4/datepicker-no.js"></script>
    <link rel="stylesheet" href="js/jquery-ui-1.11.4/jquery-ui.css">
    <script src="js/editorder.js"></script>

    <link rel="stylesheet" href="css/editorder.css">
    <!-- End of content -->
<?php require_once("template/end.html") ?>