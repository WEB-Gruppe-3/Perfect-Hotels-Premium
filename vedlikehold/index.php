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
                        <input type='hidden' size='10' type='text' name='table_name' value='$valgt_table'>
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
                    print ("Table: $valgt_table<br>");
                    $nr="1";
                    $nr2="0";
                    for ($x=2;$x<=$rows;$x++)
                         {
                         print("$column[$nr] = $input[$nr2]<br>");
                         $nr++;
                          $nr2++;
                          }
                    
                    $nr="1";
                    $nr2="0";
                    $data = array();
                    for ($x=2;$x<=$rows;$x++)
                            {
                            $data[$column[$nr]] = $input[$nr2];
                         $nr++;
                         $nr2++;
                         }
                    $result = $dbApi->insertRow($valgt_table, $data);
                    if($result) {
                        echo("<br><span style='color:limegreen'>Successfully inserted row in table $valgt_table!</span>");
                    }
                    else {
                        echo("<br><span style='color:red'><strong>Inserting of row into table $valgt_table FAILED!</strong></span>");
                    }
                }

        @$deleteknapp=$_POST ["deleteknapp"];
                if ($deleteknapp)
                    {
                    $id = $_POST['rowID'];
                    $valgt_table=$_POST ["table_name"];
                    print ("Row ($id) is now 'deleted'<br>"); 
                    
                    $result = $dbApi->deleteRow($valgt_table, $id);
                     if($result) {
                        echo("<br><span style='color:limegreen'>Successfully deleted row in table $valgt_table!</span>");
                    }
                    else {
                        echo("<br><span style='color:red'><strong>Deleting of row ($id) into table $valgt_table FAILED!</strong></span>");
                    }

                }

        
        @$editknapp=$_POST ["editknapp"];
                if ($editknapp)
                    {
                    $id = $_POST['rowID'];
                    $valgt_table=$_POST ["table_name"];

                    $result = $dbApi->getRow($valgt_table, $id);
                    

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
            $result = $dbApi->getRow($valgt_table, $id);

            while($row = mysqli_fetch_row($result)) 
            {
                echo ("<tr><form action='' method='post' id='EditForm' name='EditForm'>");
                $rows=count($column);
                $nr=0;
                for ($x=1;$x<=$rows;$x++)
                 {
                    if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelRoomTypeID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID" || $column[$nr]=="RoomID") 
                        {
                        $foreigntable= substr($column[$nr], 0, -2);
                        print ("<td><select name=$nr type='input'>");
                        $result2 = $dbApi->getAllRows("$foreigntable");
                        while($row2 = mysqli_fetch_row($result2)) 
                            {
                            echo("<option value='" . $row2[0] . "'>" . $row2[0] . "</option>");
                            }
                        print ("</select></td>");
                        }
                    else 
                        {
                            echo ("<td><input type='text' size='10' type='text' name='$nr' value='" . $row["$nr"] . "'></td>");
                        }

                        $nr++;

                 }
                echo ("<td><input type='submit' value='Update' name='updateknapp' id='updateknapp'>
                        <input type='hidden' size='10' type='text' name='rowID' value='" . $row[0] . "'>
                        <input type='hidden' size='10' type='text' name='table_name' value='$valgt_table'>
                      </td></form></tr>");
            }
                    print ("Here is where you edit the selected row $id<br>");
                }
       

                @$updateknapp=$_POST ["updateknapp"];
                if ($updateknapp)
                    {
                    $valgt_table=$_POST ["table_name"];
                    $id = $_POST['rowID'];
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
                    print ("Table: $valgt_table<br>");
                    $nr="1";
                    $nr2="0";
                    print("$column[$nr] = $input[$nr2]<br>");
                    for ($x=2;$x<=$rows;$x++)
                         {
                         print("$column[$nr] = $input[$nr2]<br>");
                         $nr++;
                          $nr2++;
                          }
                    
                    $nr="1";
                    $nr2="0";
                    $data = array();
                    for ($x=2;$x<=$rows;$x++)
                            {
                            $data[$column[$nr]] = $input[$nr2];
                         $nr++;
                         $nr2++;
                         }
                    print_r($data);
                    $result = $dbApi->updateRow($valgt_table, $id, $data);
                    if($result) {
                        echo("<br><span style='color:limegreen'>Successfully updated row in table $valgt_table!</span>");
                    }
                    else {
                        echo("<br><span style='color:red'><strong>Updating of row into table $valgt_table FAILED!</strong></span>");
                    }
                }





?>
