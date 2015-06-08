<?php
require_once("php/classes/Database.php");
require_once("php/classes/Hotel.php");
require_once("template/start.html");

$db = new Database();

$hotels = $db->getHotels();
?>

<div id="content">

    <!-- Left div -->
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

    <!-- Right div -->
    <div id="innholdRight">

        <p>Antall ledige rom: <span id="numOfAvailableRooms"></span></p>

        <h2 id="hotelTitle"></h2>
        <img id="hotelImage" width="200" height="200">
        <p id="hotelDescription"></p>

        <h3 id="roomTypeTitle"></h3>

        <h4 id="dateTitle"></h4>

        <img id="roomTypeImage" width="200" height="200">

        <p id="roomTypeDescription"></p>

        <br>

        <button type="button" onClick="openModalWindow()">Book rom!</button>

    </div>

    <!-- Clear fix -->
    <div class="clearFix"></div>

    <!-- Modal window -->
    <div class="modalWindow">
        <div class="modalWindowContent">

            <div id="preOrderContent">
                <h2>Bestilling</h2>
                <span id="modalClose" onClick="closeModalWindow()">Lukk!</span> <br><br>
                Hotell: <span id="modalHotelTitle"></span> <br>
                Romtype: <span id="modalRoomTypeTitle"></span> <br>
                Dato: <span id="modalDateTitle"></span> <br><br>

                <form>
                    Epost: <input type="text" id="emailInput">
                    <button type="button" onClick="bookRoom()">Bestill!</button>
                </form>
            </div>

            <div id="postOrderContent">
                Takk for din bestilling!
                <br>
                Ditt referansenummer er: <span id="refNr"></span>
                <br>
                <a href="../vedlikehold/checkin.php">Sjekk inn her!</a>
            </div>

        </div>
    </div>

</div> <!-- End of content -->

<!-- Javascript -->
<script src="js/jquery-1.11.3.js"></script>
<script src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-1.11.4/datepicker-no.js"></script>
<link rel="stylesheet" href="js/jquery-ui-1.11.4/jquery-ui.css">
<script src="js/index.js"></script>

<?php require_once("template/end.html"); ?> <!-- End of page -->


