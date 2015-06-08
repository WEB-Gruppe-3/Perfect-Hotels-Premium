<?php require_once("template/start.html");
    require_once("../nettsted/php/classes/DBConnector.php");
    require_once("../nettsted/php/classes/Database.php");
    $dbCon = new DBConnector();
    $dbApi = new Database();
?>
    <!-- Start of content -->
    <div id="content">
        <div id="rightDiv">
            <h3>Enter your Reference Code:</h3>
            <form method="post" action="" id="checkinform" name="checkinform">
                <input name="search" type="search">
                <input type='submit' value='OK' name='checkinbutton' id='checkinbutton'>
            </form>
            <br>
        </div>

        <div id="leftDiv">
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
                    $result = $dbApi->getALLRows("Booking");
                    while($row = mysqli_fetch_row($result)) {
                        if ($row[3]!="0"){
                            $foo[]=$row[3];
                        }
                    }
                    $result = $dbApi->getAllRows("Booking");
                    while($row = mysqli_fetch_row($result)) {
                        if ($row[5]==$refid) {
                            $bookid = $row[0];
                            $hotelroomtypeid=$row[4];
                            if ($row[3]=="0") {
                                echo("<p style='color:green'><strong>Velg ditt rom nr:</strong></p>");
                                echo("<form method='post' action='' id='roomform' name='roomform'>");
                                $result = $dbApi->getAllRows("Room");
                                while($row = mysqli_fetch_row($result)) {
                                    if ($row[2]==$hotelroomtypeid) {
                                        if (in_array($row[0], $foo)) {

                                        }
                                        else {
                                            echo("<input type='checkbox' name='Room' value='$row[0]' style='color:green'><strong>" . $row[1] .  "</strong></br>");
                                        }
                                    }
                                }
                                echo("<input name='ID' type='hidden' value='$refid'><input type='submit' value='OK' name='roombutton' id='roombutton'></form>");
                            }
                            else {
                                $id=$row[3];
                                $result = $dbApi->getRow("Room", $id);
                                while($row = mysqli_fetch_row($result)) {
                                    echo("<p style='color:red'><strong>Du har allerede booket romid: " . $row[1] .  "</strong></p>");
                                }
                            }
                        }
                    }
                }
                else {
                    echo("<p style='color:Red'><strong>Could not find your Code! </strong></p>");
                }
            }
            @$roombutton=$_POST ["roombutton"];
            if ($roombutton) {
                $roomid=$_POST ["Room"];
                $data = array( "RoomID" => $roomid );
                @$id = $_POST['ID'];
                $result = $dbApi->updateRow("Booking", $id, $data);
                if($result) {
                    $result = $dbApi->getRow("Room", $id);
                    while($row = mysqli_fetch_row($result)) {
                        echo("<p style='color:green'><strong>Successfully checked in to Room: " . $row[1] .  "</strong></p>");
                    }
                }
                else {
                    echo ("<br><span style='color:red'><strong>Checkin FAILED!</strong></span>");
                }
            }
            ?>
        </div>
    </div>
    <!-- End of content -->
<?php require_once("template/end.html") ?>