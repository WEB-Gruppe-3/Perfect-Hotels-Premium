<?php
require_once("php/classes/Database.php");
require_once("php/classes/Hotel.php");
require_once("template/start.html");

$db = new Database();

$hotels = $db->getHotels();
?>

<div id="content">

    <!-- Left div -->
    <div id="leftDiv">
        <form id="searchForm">

            <div class="searchDiv" id="hotelSelectDiv">
                <div class="searchTitle">Velg hotell</div>

                <select class="searchInput" id="hotelSelect" onChange='populateRoomTypeList();' required>
                    <?php // Printing options
                    foreach($hotels as $hotel) {
                        $hotelName = $hotel->getName();
                        $hotelId = $hotel->getId();
                        print("<option value='$hotelId'>$hotelName</option>\n");
                    }
                    ?>
                </select>
            </div>

            <div class="searchDiv searchDivExtraMargin" id="roomTypeSelectDiv">
                <div class="searchTitle">Velg type rom</div>

                <select class="searchInput" id="roomTypeSelect" disabled required>
                    <!-- Populates via JS -->
                </select>
            </div>

            <div class="searchDiv searchDivExtraMargin" id="dateSelectDiv">
                <div class="searchTitle">Velg dato</div>

                <table class="searchInput">
                    <tr>
                        <td>
                            <input id="startDateInput" type="text" readonly required> &nbsp; - &nbsp;
                            <input id="endDateInput" type="text" readonly required>
                        </td>
                    </tr>
                </table>
            </div>

            <br>

            <a class="smallButton" id="newSearchButton">Nytt søk</a>
            <a class="smallButton" id="searchButton">Søk</a>
        </form>

        <!-- Error message div -->
        <div id="searchDateErrorMessage"></div>

    </div>

    <!-- Right div -->
    <div id="rightDiv">

        <!-- Show before search -->
        <div class="resultDiv" id="welcome">
            <h2>Velkommen til Perfect Hotels Premium</h2>

            <p>For å finne et hotell, vennligst benytt menyen til venstre.</p>
        </div>

        <!-- Show when search complete -->
        <div class="resultDiv" id="result">

            <div class="hotelPresentation">
                <h2 id="hotelTitle">HotelTitle</h2>
                <img id="hotelImage" width="600" height="300" src="img/modal_window_bg.png">
                <p id="hotelDescription">Hotell beskrivelse...</p>
            </div>

            <div class="roomPresentation">

                <img id="roomTypeImage" width="170" height="170" src="img/modal_window_bg.png">

                <h2 id="roomTypeTitle">RoomTitle</h2>
                <p id="roomTypeDescription">RoomType beskrivelse...</p>

                <div id="freeRoomsBox">
                    <span id="numOfAvailableRooms">-</span>
                    <span id="freeRoomsBoxTitle">Ledig</span>
                    <span id="dateTitle"></span> <!-- Hidden in CSS atm -->
                </div>

            </div>
            <br>
            <br>
            <a class="bigButton" id="goToOrderButton">Gå til bestilling</a>
        </div>

        <!-- Show when ordering -->
        <div class="resultDiv" id="order">
            <h2>Bestilling</h2>
            <p>Du er i ferd med å booke et hotellrom.</p>

            <div id="orderDetails">
                <table>
                    <tr>
                        <td>Hotell:</td><td><span id="orderHotelTitle"></span></td>
                    </tr>
                    <tr>
                        <td>Type rom:</td><td><span id="orderRoomTypeTitle"></span></td>
                    </tr>
                    <tr>
                        <td>Tidsperiode:</td><td><span id="orderDateTitle"></span></td>
                    </tr>
                </table>
            </div>

            <br>
            Epost: <input type="text" id="emailInput">
            <br>
            <div class="bigButton" id="orderButton">Bestill</div>

        </div>

        <!-- Show when order is complete -->
        <div class="resultDiv" id="orderComplete">
            <h2>Takk for din bestilling!</h2>
            Din booking-referanse er: <span id="refNr"></span>
            <br>
            <p>Ta godt vare på referansen, da denne brukes ved innsjekking.</p>
        </div>

    </div>

    <!-- Clear fix -->
    <div class="clearFix"></div>

</div> <!-- End of content -->

<!-- Javascript -->
<script src="js/jquery-1.11.3.js"></script>
<script src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-1.11.4/datepicker-no.js"></script>
<link rel="stylesheet" href="js/jquery-ui-1.11.4/jquery-ui.css">
<script src="js/index.js"></script>

<?php require_once("template/end.html"); ?> <!-- End of page -->


