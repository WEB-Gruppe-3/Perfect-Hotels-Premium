<?php
require_once("php/classes/Database.php");
require_once("php/classes/Hotel.php");
require_once("template/start.html");

$db = new Database();

$hotels = $db->getHotels();
?>

<div id="content">

    <!-- Search -->
    <div id="innholdLeft">
        <form id="searchForm">
            <table>
                <tr>
                    <td>Velg hotell:</td>
                    <td>
                        <select id="hotelSelect" onChange='populateRoomTypeList()'>
                            <?php // Printing options
                            foreach($hotels as $hotel) {
                                $hotelName = $hotel->getName();
                                $hotelId = $hotel->getId();
                                print("<option value='$hotelId'>$hotelName</option>\n");
                            }
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td>Velg rom type:</td>
                    <td>
                        <select id="roomTypeSelect" disabled>
                            <!-- Populates via JS -->
                            <option>----------</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Velg dato:</td>
                    <td>
                        <input id="startDateInput" type="text" size="9" readonly> -
                        <input id="endDateInput" type="text" size="9" readonly>
                    </td>
                </tr>

                <tr>
                    <td>
                        <button type="button" onClick="getAvailableRooms()">Søk</button>
                        <button type="reset">Nullstill</button>
                    </td>
                </tr>
            </table>
        </form>

    </div>

    <!-- Result -->
    <div id="innholdRight">

        <p>Antall ledige rom: <span id="numOfAvailableRooms"></span></p>

        <br>
        <br>
        <br>
        <br>
        <br>

        <!--
        <h2 id="hotelTitle">selected hotel</h2>
        <img id="hotelImage" src="img/top.jpg" width="200" height="200">
        <p id="hotelDescription">placeholder hotel description</p>

        <h3 id="roomTypeTitle">selected roomType</h3>

        <h4 id="dateTitle">selected date</h4>

        <img id="roomTypeImage" src="img/top.jpg" width="200" height="200">

        <p id="roomTypeDescription">placeholder roomtype description</p>
    -->
    </div>

</div> <!-- End of content -->

<!-- Javascript -->
<script src="js/jquery-1.11.3.js"></script>
<script src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-1.11.4/datepicker-no.js"></script>
<link rel="stylesheet" href="js/jquery-ui-1.11.4/jquery-ui.css">
<script src="js/index.js"></script>

<?php require_once("template/end.html"); ?> <!-- End of page -->


