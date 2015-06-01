<?php
require_once("php/classes/Database.php");
require_once("php/classes/Hotel.php");
require_once("template/start.html");

$db = new Database();

$hotels = $db->getHotels();
?>

<div id="content">

    <div id="innholdLeft">
        <form id="searchForm">

            <select id="hotelSelect" onChange='getAndSetRoomTypes()'>
                <?php // Printing options
                foreach($hotels as $hotel) {
                    $hotelName = $hotel->getName();
                    $hotelId = $hotel->getId();
                    print("<option value='$hotelId'>$hotelName</option>\n");
                }
                ?>
            </select> <br>

            <select id="roomTypeSelect">
                // Print options based on selected hotel
            </select>
            <br>

        </form>

    </div>

    <div id="innholdRight">

    </div>


</div> <!-- End of content -->

<script src="js/jquery-1.11.3.js"></script>
<script src="js/index.js"></script>

<?php require_once("template/end.html"); ?> <!-- End of page -->


