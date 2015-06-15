<?php require_once("template/start.html");
require_once("../nettsted/php/classes/Database.php");
$dbApi = new Database();
?>
    <!-- Start of content -->
    <div id="content">
        <div id="innholdLeft">
            <h3>Vennligst fyll inn referanse kode for å endre din bestilling:</h3>
            <form method="post" action="" id="checkinform" name="checkinform">
                <input name="search" type="search">
                <input type='submit' value='OK' name='checkinbutton' id='checkinbutton'>
            </form>
            <br>
        </div>

        <div id="innholdRight">
            <?php
            @$checkinbutton=$_POST ["checkinbutton"];
            if ($checkinbutton) {
                @$input = $_POST['search'];
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

                    $bookid = array();
                    $hotelroomtypeid = array();
                    $result = $dbApi->getALLRows("Booking");
                    while($row = mysqli_fetch_row($result)) {

                        if ($row[5] == $refid ) {
                            $bookid[] = $row[0];
                            $hotelroomtypeid[] = $row[4];
                        }
                    }
                    $bookings=count($bookid);
                    $nr=0;
                    for ($x=1;$x<=$bookings;$x++) {
                        $result = $dbApi->getRow("Booking", $bookid[$nr]);
                        while ($row = mysqli_fetch_row($result)) {
                            if ($row[3] == "0") {
                                $foo = array("ID" => "$row[0]", "FromDate" => "$row[1]", "ToDate" => "$row[2]", "RoomID" => "$row[3]", "HotelRoomTypeID" => "$row[4]", "CustomerOrderID" => "$row[5]");
                                $result = $dbApi->getRow("HotelRoomType", @$foo[HotelRoomTypeID]);
                                while ($row = mysqli_fetch_row($result)) {
                                    $bar = array("ID" => "$row[0]", "RoomTypeID" => "$row[1]", "HotelID" => "$row[2]");
                                }
                                $result = $dbApi->getRow("Hotel", @$bar[HotelID]);
                                while ($row = mysqli_fetch_row($result)) {
                                    $hotel = $row[1];
                                }
                                $result = $dbApi->getRow("RoomType", @$bar[RoomTypeID]);
                                while ($row = mysqli_fetch_row($result)) {
                                    $roomtype = $row[1];
                                }
                                print ("Bestilling nummer $x<br>");
                                print ("<table><form method='post' action='' id='editform' name='editform'><input type='hidden' name='BookingID' value=$foo[ID]>");
                                print ("<tr><td>Hotel</td>");
                                print ("<td><select name='HotelID' type='input'>");
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
                                print ("<td><select name='RoomTypeID' type='input'>");
                                $result = $dbApi->getAllRows("RoomType");
                                while ($row = mysqli_fetch_row($result)) {
                                    if ($row[0] == $bar[RoomTypeID]) {
                                        echo("<option value=$row[0] selected>$row[0] - $row[1] - $row[2] Senger - $row[5]</option>");
                                    } else {
                                        echo("<option value=$row[0]>$row[0] - $row[1] - $row[2] Senger - $row[5]</option>");
                                    }
                                }
                                print ("</select></td></tr>");
                                print ("<tr><td>From</td><td><input size='9' id='startDateInput$x' type='text' name='FromDate' value=$foo[FromDate] readonly required></td></tr>");
                                print ("<tr><td>To</td><td><input size='9' id='endDateInput$x' type='text' name='ToDate' value=$foo[ToDate] readonly required></td><td><input type='submit' value='Update' name='checkbutton' id='checkbutton'></td></tr>");
                                print ("</form></table><br>");
                            }

                            else {
                                $id = $row[3];
                                $result = $dbApi->getRow("Room", $id);
                                while ($row = mysqli_fetch_row($result)) {
                                    print ("Bestilling nummer $x<br>");
                                    echo("<p style='color:red'><strong>Du har allerede booket rom $row[1], du kan ikke endre din bestilling.</strong></p>");
                                }
                            }
                            $nr++;
                        }
                    }
                    if ($bookings==0) {
                        echo("<p style='color:Red'><strong>Du har ingen bestilling som ikke er sjekket inn allerede. </strong></p>");
                    }
                }
                else {
                    echo("<p style='color:Red'><strong>Could not find your Code! </strong></p>");
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
                $rooms=0;
                $result = $dbApi->getALLRows("Room");
                while($row = mysqli_fetch_row($result)) {
                    if ($row[2]==$hotelroomtype){
                        $rooms++;
                    }
                }
                $fromdate = array();
                $todate = array();
                $newbookingid = array();
                $result = $dbApi->getALLRows("Booking");
                while($row = mysqli_fetch_row($result)) {
                    if ($row[4]==$hotelroomtype){
                        $fromdate[]=$row[1];
                        $todate[]=$row[2];
                        $newbookingid[]=$row[0];
                    }
                }
                $rows=count($fromdate);
                $occupiedrooms=0;
                $nr=0;
                for ($x=1;$x<=$rows;$x++) {
                    if ($newfromdate <= $fromdate[$nr] && $newtodate <= $fromdate[$nr] || $newfromdate >= $todate[$nr]) {
                        if ($newfromdate == $fromdate[$nr] && $newtodate == $todate[$nr] && $bookingid != $newbookingid[$nr] ) {
                            $occupiedrooms++;
                        }
                        else {
                            @$freerooms++;
                        }
                    }
                    elseif ($bookingid != $newbookingid[$nr]) {
                        $occupiedrooms++;
                    }
                    $nr++;
                }
                echo "Rooms: $rooms <br>";
                echo "Occupied rooms; $occupiedrooms <br>";
                $availablerooms = $rooms-$occupiedrooms;
                echo "Available Rooms: $availablerooms <br>";
                echo "Bookingid: $bookingid <br>";
                print_r($newbookingid);
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