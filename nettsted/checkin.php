<?php require_once("template/start.html");
    require_once("php/classes/Database.php");
    $dbApi = new Database();
?>
    <!-- Start of content -->
    <div id="content">
        <div id="innholdLeft">
            <h3>Vennligst fyll inn referanse kode:</h3>
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
                    $foo = array();
                    $bookid = array();
                    $hotelroomtypeid = array();
                    $result = $dbApi->getALLRows("Booking");
                    while($row = mysqli_fetch_row($result)) {
                        if ($row[3]!="0"){
                            $foo[]=$row[3];
                        }
                        if ($row[5] == $refid) {
                            $bookid[] = $row[0];
                            $hotelroomtypeid[] = $row[4];
                        }
                    }
                    $nr=0;
                    $bookings=count($bookid);
                    echo("<form method='post' action='' id='roomform' name='roomform'><input name='Bookings' type='hidden' value='$bookings'>");
                    echo("<table><tr>Vennligst velg ditt/dine rom nummer</tr>");
                    for ($x=1;$x<=$bookings;$x++) {
                        $result = $dbApi->getRow("Booking", $bookid[$nr]);
                        while ($row = mysqli_fetch_row($result)) {
                                if ($row[3] == "0") {
                                    echo("<tr><td><strong>Bestilling $x </strong><input name='ID$nr' type='hidden' value='$bookid[$nr]'></td>");
                                    print ("<td><select name=Room$nr type='input'>");
                                    echo("<option value='' selected>-</option>");
                                    $result = $dbApi->getAllRows("Room");
                                    while ($row = mysqli_fetch_row($result)) {
                                        if ($row[2] == $hotelroomtypeid[$nr]) {
                                            if (in_array($row[0], $foo)) {
                                            } else {
                                                echo("<option value=$row[0]>$row[1]</option>");
                                            }
                                        }
                                    }
                                    print ("</select></td></tr>");

                                } else {
                                    $id = $row[3];
                                    $result = $dbApi->getRow("Room", $id);
                                    while ($row = mysqli_fetch_row($result)) {
                                        print ("<tr><td>Bestilling $x</td>");
                                        echo("<td><strong style='color:red'>Du har allerede booket romid: $row[1]</strong></td>");
                                    }
                                }
                        }
                        $nr++;
                    }
                    echo("</table>");
                    echo("<input name='ID' type='hidden' value='$refid'><input type='submit' value='Fullfør' name='roombutton' id='roombutton'></form>");
                }
                else {
                    echo("<p style='color:Red'><strong>Kan desverre ikke finne referansekode din, er du sikker på du tastet riktig? </strong></p>");
                }
            }
            @$roombutton=$_POST ["roombutton"];
            if ($roombutton) {
                $nr=0;
                $bookings=$_POST ["Bookings"];
                for ($x=1;$x<=$bookings;$x++) {
                    $bookid=$_POST ["ID".$nr];
                    $roomid=$_POST ["Room".$nr];
                    $nr++;
                    $data = array( "RoomID" => $roomid );
                    $result = $dbApi->updateRow("Booking", $bookid, $data);
                    if($result) {
                        $result = $dbApi->getRow("Room", $roomid);
                        while($row = mysqli_fetch_row($result)) {
                            echo("<p style='color:green'><strong>Du har nå sjekket inn på rom nummer: " . $row[1] .  "</strong></p>");
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
    <!-- End of content -->
<?php require_once("template/end.html") ?>