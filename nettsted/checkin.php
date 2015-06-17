<?php require_once("template/start.html");
    require_once("php/classes/Database.php");
    $dbApi = new Database();
?>
    <!-- Start of content -->
    <div id="content">
        <div id="checkinDivFtw">

            <h3>Vennligst fyll inn referanse kode:</h3>
            <form method="post" action="" id="checkinform" name="checkinform">
                <input name="search" type="search" required="">
                <input type='submit' value='OK' name='checkinbutton' id='checkinbutton'>
            </form>
            <br>

            <?php
            @$checkinbutton=$_POST ["checkinbutton"];
            if ($checkinbutton) {
                @$input = $_POST['search'];
                // Trimming spaces
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
                                    echo("<tr><td>Fra-Til: $fromdate[$nr] - $todate[$nr]<input name='ID' type='hidden' value='$bookid[$nr]'></td><td><input type='submit' value='Sjekk inn' name='roombutton' id='roombutton'></td></tr>");
                                    echo "</form>";

                                } else {
                                    echo("<table>");
                                    $id = $row[3];
                                    $result = $dbApi->getRow("Room", $id);
                                    while ($row = mysqli_fetch_row($result)) {
                                        print ("<tr><td><strong>Bestilling $x: </strong></td></tr>");
                                        echo("<tr><td><strong style='color:red'>Du har allerede sjekket inn på rom: $row[1]</strong></td></tr>");
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
            @$roombutton=$_POST ["roombutton"];
            if ($roombutton) {
                $bestilling=$_POST ["Bestilling"];
                $result = $dbApi->getALLRows("Booking");
                while($row = mysqli_fetch_row($result)) {
                    if ($row[3]!="0"){
                        $foo[]=$row[3];
                    }
                }
                $nr=0;
                $bookid=$_POST ["ID"];
                $result = $dbApi->getRow("Booking", $bookid);
                while ($row = mysqli_fetch_row($result)) {
                    if ($row[3] == "0") {
                        echo("<table>");
                        echo("<form method='post' action='' id='roomform' name='roomform'>");
                        echo("<tr><td><strong>Bestilling $bestilling: </strong></td></tr>");
                        $result2 = $dbApi->getRow("hotelroomtype", $row[4]);
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


                        echo("<tr><td>Fra-Til: $row[1] - $row[2]<input name='ID' type='hidden' value='$bookid'></td><td></td></tr>");
                        echo ("<tr><td>Velg ditt rom nummer: </td><td><select name=Room type='input' required=''>");
                        echo("<option value='' selected>-</option>");
                        $result5 = $dbApi->getAllRows("Room");
                        while ($row5 = mysqli_fetch_row($result5)) {
                            if ($row5[2] == $row[4]) {
                                if (in_array($row5[0], $foo)) {
                                } else {
                                    echo("<option value=$row5[0]>$row5[1]</option>");
                                }
                            }
                        }
                        echo ("</td><td><input type='submit' value='Fullfør' name='donebutton' id='donebutton'></td></tr>");
                        echo "</form>";

                    } else {
                        $id = $row[3];
                        $result = $dbApi->getRow("Room", $id);
                        while ($row = mysqli_fetch_row($result)) {
                            print ("<tr><td>Bestilling $bestilling</td></tr>");
                            echo("<tr><td><strong style='color:red'>Du har allerede sjekket inn på rom: $row[1]</strong></td></tr>");
                        }
                    }
                }
                $nr++;
                echo("</table>");
            }
            @$donebutton=$_POST ["donebutton"];
            if ($donebutton) {

                $bookid = $_POST ["ID"];
                $roomid = $_POST ["Room"];
                $data = array("RoomID" => $roomid);
                $result = $dbApi->updateRow("Booking", $bookid, $data);
                if ($result) {
                    $result = $dbApi->getRow("Room", $roomid);
                    while ($row = mysqli_fetch_row($result)) {
                        echo("<p style='color:green'><strong>Du har nå sjekket inn på rom nummer: " . $row[1] . "</strong></p>");
                    }
                }

            }

            ?>
        </div>
    </div><!-- End of content -->

    <script src="js/jquery-1.11.3.js"></script>
    <script src="js/checkin.js"></script>

<?php require_once("template/end.html") ?>