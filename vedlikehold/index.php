<?php
    session_start();

    // Check if we're logged in
    if ($_SESSION['loggedin'] != 1) {
        // If not, send user back to login page.
        header("Location: login.php");
        exit;
    }

    require_once("template/start.php");
    require_once("../nettsted/php/classes/DBConnector.php");
    require_once("../nettsted/php/classes/Database.php");

    $dbCon = new DBConnector();
    $dbApi = new Database();

    @$valgt_table=$_POST ["table_name"];
    if (!$valgt_table) {
        @$valgt_table=$_SESSION["table_name"];
    }
    // store session data
    $_SESSION["table_name"] = "$valgt_table";

    @$id = $_POST['rowID'];
    $column = array();
    $input = array();
    $data = array();


if ($valgt_table) {
    $result = $dbApi->getColumnNames("$valgt_table");
    while ($row = mysqli_fetch_row($result)) {
        $column[] = $row[0];
    }
    $rows = count($column);
    $nr=0;
    for ($x=1;$x<=$rows;$x++) {
        $input[] = @$_POST[$column[$nr]];
        $nr++;
    }
}

function validate($column, $input) {
    if(@$column[1]=='FromDate') {
        if (!$input[1]) {
            $errorMsg = "You have to pick a FromDate";
            return $errorMsg;
        }
        if (isset($input[1])){
            $now=date("Y-m-d");
            if($input[1] < $now) {
                $errorMsg = 'date is in the past';
                return $errorMsg;
            }
        }
    }
    if(@$column[2]=='ToDate') {
        if (!$input[2]) {
            $errorMsg = "You have to pick a ToDate";
            return $errorMsg;
        }
        if (isset($input[2])){
            if($input[2] <= $input[1]) {
                $errorMsg = 'date is earlier than startdate';
                return $errorMsg;
            }
        }
    }
    if(@$column[1]=='Reference' && !$input[1]) {
        $errorMsg = "Insert a valid Reference code";
        return $errorMsg;
    }
    if(@$column[1]=='Name' && !$input[1]) {
        $errorMsg = "Insert a valid Name";
        return $errorMsg;
    }
    if(@$column[3]=='Description' && !$input[3] || @$column[2]=='Description' && !$input[2] || @$column[5]=='Description' && !$input[5]) {
        $errorMsg = "Insert a valid Description";
        return $errorMsg;
    }
    if(@$column[1]=='URL' && !$input[1]) {
        $errorMsg = "Insert a valid URL";
        return $errorMsg;
    }
    if(@$column[1]=='UserName' && !$input[1]) {
        $errorMsg = "Insert a valid UserName";
        return $errorMsg;
    }
    if(@$column[2]=='Password' && !$input[2]) {
        $errorMsg = "Insert a valid Password";
        return $errorMsg;
    }
    if(@$column[1]=='RoomNumber' && !$input[1]) {
        $errorMsg = "Insert a valid RoomNumber";
        return $errorMsg;
    }
    if(@$column[2]=='NumOfBeds' && !$input[2]) {
        $errorMsg = "Insert a valid number of beds";
        return $errorMsg;
    }
    if(@$column[3]=='Price' && !$input[3]) {
        $errorMsg = "Insert a valid Price";
        return $errorMsg;
    }
}


if (@$_POST['addknapp']) {
    $errorMsg = validate($column, $input);
    if (!$errorMsg) {
        unset($input);
        $nr="1";
        for ($x=2;$x<=$rows;$x++) {
            $data[$column[$nr]] = $_POST[$column[$nr]];
            $nr++;
        }
        $result = $dbApi->doesRowExist($valgt_table, $data);
        if($result && $valgt_table != "Booking") {
            $errorMsg = ("There was already a row containing this..");
        }
        else {
            $result2 = $dbApi->insertRow($valgt_table, $data);
            if($result2) {
                $successMsg = ("Successfully inserted row in table $valgt_table!");
            }
            else {
                $errorMsg =  ("Inserting of row into table $valgt_table FAILED!");
                print_r($data);
            }
        }
    }
}

if (@$_POST['updateknapp']) {
    $errorMsg = validate($column, $input);
    if (!$errorMsg) {
        echo ("<script type='text/javascript'>");
        echo ("document.getElementById('popup').style.visibility = 'hidden';");
        echo ("document.getElementById('overlaypopup').style.visibility = 'hidden';");
        echo ("</script>");
        unset($input);
    $nr="1";
    $nr2="0";
    for ($x=2;$x<=$rows;$x++) {
        $data[$column[$nr]] = $_POST[$column[$nr]];
        $nr++;
        $nr2++;
    }
    //print_r($data);
    $result = $dbApi->doesRowExist($valgt_table, $data);
    if($result && $valgt_table != "Booking") {
        $errorMsg =  ("There was already a row in $valgt_table containing this..");
    }
    else {
        $result2 = $dbApi->updateRow($valgt_table, $id, $data);
        if($result2) {
            $successMsg = ("Successfully updated row in table $valgt_table!");
        }
        else {
            $errorMsg =  ("Updating of row into table $valgt_table FAILED!");
        }
    }

    }
}


if (@$_POST['deleteknapp']) {
    $result = $dbApi->deleteRow($valgt_table, $id);
    if($result) {
        $successMsg = ("Successfully deleted row in table $valgt_table!");
    }
    else {
        $errorMsg =  ("Deleting of row ($id) in table $valgt_table FAILED!");
    }
}

?>
    <div id="content">
        <div id="innholdLeft">
            <form method="post" action="" id="velgtabellknapp" name="velgtabellknapp">
                <h3>Velg tabell</h3>
                <select name='table_name' id='table_list'>
                    <?php
                    $result = $dbApi->getTableNames();
                    while($row = mysqli_fetch_row($result)) {
                        echo("<option value='$row[0]'");
                        if ($row[0]==$valgt_table) {
                            echo("selected");
                        }
                        echo(">" . $row[0] . "</option>");
                    }
                    ?>
                </select><input type='submit' value='OK' name='velgtabellknapp' id='velgtabellknapp' onclick="list()">
            </form>

        </div>
        <div id="innholdRight"><?php if($valgt_table){require_once("list.php");}?></div>
        <div id="popup"><?php require_once("edit.php") ?></div>
        <?PHP
        if(isset($errorMsg) && $errorMsg) {
            echo "<div style='color:red'>",htmlspecialchars(@$errorMsg),"</div>\n\n";
            echo "<div style='color:green'>",htmlspecialchars(@$successMsg),"</div>\n\n";
        }
        ?>
    </div> <!-- Content end -->
    <!-- Hidden element with session id -->
    <form id="hiddenFormSessionID" hidden><?php print(session_id()) ?></form>

    <script src="../nettsted/js/jquery-1.11.3.js"></script>
    <script src="../nettsted/js/jquery-ui-1.11.4/jquery-ui.js"></script>
    <script src="../nettsted/js/jquery-ui-1.11.4/datepicker-no.js"></script>
    <link rel="stylesheet" href="../nettsted/js/jquery-ui-1.11.4/jquery-ui.css">
    <script src="js/index.js"></script>

<?php require_once("template/end.html");
 ?>