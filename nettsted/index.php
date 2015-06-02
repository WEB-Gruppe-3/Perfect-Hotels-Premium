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

            Velg hotell:
            <select id="hotelSelect" onChange='populateRoomTypeList()'>
                <?php // Printing options
                foreach($hotels as $hotel) {
                    $hotelName = $hotel->getName();
                    $hotelId = $hotel->getId();
                    print("<option value='$hotelId'>$hotelName</option>\n");
                }
                ?>
            </select>

            <br>

            Velg rom type:
            <select id="roomTypeSelect">
                <!-- Populates via JS -->
            </select>

            <br>

            Velg dato:
            <input id="startDate" type="text"> - <input id="endDate" type="text">

            <br>

            <button type="button">LoL!</button>
        </form>
    </div>

    <div id="innholdRight">

    </div>
    
</div> <!-- End of content -->

<!-- Javascript -->
<script src="js/jquery-1.11.3.js"></script>
<script src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="js/index.js"></script>

<?php require_once("template/end.html"); ?> <!-- End of page -->


