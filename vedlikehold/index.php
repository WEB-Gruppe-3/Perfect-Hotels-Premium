<?php
require_once("../php/classes/DBConnector.php");
require_once("../php/classes/Database.php");
$dbCon = new DBConnector();
$dbApi = new Database();
?>








<form method="post" action="" id="velgtabellknapp" name="velgtabellknapp">
<h3>Velg tabell</h3>
<select name='table_name' id='table_list'>
<?php
    $result = $dbApi->getTableNames();
    while($row = mysqli_fetch_row($result)) 
        {
        echo("<option value='" . $row[0] . "'>" . $row[0] . "</option>");
        }
?>
</select><input type='submit' value='OK' name='velgtabellknapp' id='velgtabellknapp'></form><br>





<?php
    @$velgtabellknapp=$_POST ["velgtabellknapp"];
    if ($velgtabellknapp)
        {
            $valgt_table=$_POST ["table_name"];
            $column = array();
            $result = $dbApi->getColumnNames("$valgt_table");
            print("<table><tr>");
            while($row = mysqli_fetch_row($result)) 
            {
                echo("<th>" . $row[0] . "</th>");
                $column[]=$row[0]; 
            }
            print("</tr>");
            print("<tr>");
            $result = $dbApi->getAllRows("$valgt_table");

            while($row = mysqli_fetch_row($result)) 
            {
                echo ("<tr><form action='' method='post' id='ListForm' name='ListForm'>");
                $antallRader3=count($row);
                $nr=0;
                for ($x=1;$x<=$antallRader3;$x++)
                 {
                echo ("<td>" . $row["$nr"] . "</td>");
                $nr++;
                 }
                echo ("<td><input type='submit' value='Edit' name='editknapp' id='editknapp'>
                        <input type='submit' value='Delete' name='deleteknapp' id='deleteknapp'>
                        <input type='hidden' size='10' type='text' name='rowID' value='" . $row[0] . "'>
                      </td></form></tr>");
            }
            print ("<tr><form action='' method='post' id='addForm' name='addForm'><td></td>");
                $nr=1;
                for ($x=2;$x<=$antallRader3;$x++)
                 {
                    if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelRoomTypeID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID" || $column[$nr]=="RoomID") {
                        $foreigntable= substr($column[$nr], 0, -2);
                        print ("<td><select name=$nr type='input'>");

                        $result = $dbApi->getAllRows("$foreigntable");
                         while($row = mysqli_fetch_row($result)) 
                       {
        

                        echo("<option value='" . $row[0] . "'>" . $row[0] . "</option>");
                        }

                        print ("</select></td>");
                    }
                    else {
                        print ("<td><input name=$nr type='input'></td>");
                    }
                    $nr++;
                 }
            print ("<td><input type='submit' value='Add' name='addknapp' id='addknapp'></td> <input type='hidden' size='10' type='text' name='table_name' value='$valgt_table'></form><tr><table><br>");
            
        }


        @$deleteknapp=$_POST ["deleteknapp"];
                if ($deleteknapp)
                    {
                    $ID = $_POST['rowID'];
                    print ("Row ($ID) is now 'deleted'<br>"); 
                    }

        @$editknapp=$_POST ["editknapp"];
                if ($editknapp)
                    {
                    $ID = $_POST['rowID'];
                    print ("Here is where you edit the selected row $ID<br>");
                    }
        @$addknapp=$_POST ["addknapp"];
                if ($addknapp)
                    {
                    $valgt_table=$_POST ["table_name"];
                    
                    $column = array();
                    $input = array();
                    $result = $dbApi->getColumnNames("$valgt_table");
                        while($row = mysqli_fetch_row($result)) {
                                $column[]=$row[0];
                        }

                    $rows=count($column);
                    $nr="1";
                    for ($x=1;$x<=$rows;$x++)
                            {
                            $input[] = $_POST[$nr];
                         $nr++;
                         }
                    $nr="1";
                    $nr2="0";
                    for ($x=2;$x<=$rows;$x++)
                         {
                         print("$column[$nr] = $input[$nr2]<br>");
                         $nr++;
                          $nr2++;
                          }
                    print ("Now it could be added to the table: $valgt_table<br>");


                    $nr="1";
                    $nr2="0";
                    $data = array();
                    for ($x=2;$x<=$rows;$x++)
                            {
                            $data[]= array($column[$nr] => $input[$nr2]);
                         $nr++;
                         $nr2++;
                         }
                    print($valgt_table);
                    print_r($data);


$result = $dbApi->insertRow($valgt_table, $data);
if($result) {
    echo("<span style='color:limegreen'>Successfully inserted row in table Test!</span>");
}
else {
    echo("<span style='color:red'><strong>Inserting of row into table Test FAILED!</strong></span>");
}


                    }






?>
