<?php
require_once("php/classes/Database.php");
require_once("php/classes/Hotel.php");
require_once("template/start.html");

$db = new Database();

$hotels = $db->getHotels();
?>

<div id="content">

    <!-- Left div -->
    <div id="rightDiv">
        <form id="searchForm">

            <div class="searchDiv" id="hotelSelectDiv">
                <div class="searchTitle">Velg hotell</div>

                <select class="searchInput" id="hotelSelect" onChange='populateRoomTypeList()'>
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

                <select class="searchInput" id="roomTypeSelect" disabled>
                    <!-- Populates via JS -->
                </select>
            </div>

            <div class="searchDiv searchDivExtraMargin" id="dateSelectDiv">
                <div class="searchTitle">Velg dato</div>

                <table class="searchInput">
                    <tr>
                        <td>
                            <input id="startDateInput" type="text" size="9" readonly> -
                            <input id="endDateInput" type="text" size="9" readonly>
                        </td>
                    </tr>
                </table>
            </div>

            <br>
            <br>

            <button type="button" onClick="search()">Søk</button>
            <button type="reset">Nullstill</button>

        </form>

    </div>

    <!-- Right div -->
    <div id="leftDiv">

        <p>Antall ledige rom: <span id="numOfAvailableRooms"></span></p>

        <h2 id="hotelTitle"></h2>
        <img id="hotelImage" width="200" height="200">
        <p id="hotelDescription"></p>

        <h3 id="roomTypeTitle"></h3>

        <h4 id="dateTitle"></h4>

        <img id="roomTypeImage" width="200" height="200">

        <p id="roomTypeDescription"></p>

        <br>

        <button type="button" onClick="showOrderOverlay()">Bestill!</button>

    </div>

    <!-- Clear fix -->
    <div class="clearFix"></div>

    <!-- Modal window -->
    <div class="modalWindow">
        <div class="modalWindowContent">

            <div id="modalPreOrderContent">
                <h2>Bestilling</h2>
                <span id="modalClose" onClick="closeOrderOverlay()">Lukk!</span> <br><br>
                Hotell: <span id="modalHotelTitle"></span> <br>
                Romtype: <span id="modalRoomTypeTitle"></span> <br>
                Dato: <span id="modalDateTitle"></span> <br><br>

                <form>
                    Epost: <input type="text" id="emailInput">
                    <button type="button" onClick="doOrder()">Bestill!</button>
                </form>
            </div>

            <div id="modalPostOrderContent">
                <span id="modalClose" onClick="closeOrderOverlay()">Lukk!</span> <br><br>
                Takk for din bestilling!
                <br>
                Ditt referansenummer er: <span id="modalRefNrTitle"></span>
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


