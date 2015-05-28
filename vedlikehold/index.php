<?php
    session_start();
    require_once("template/start.html");
    require_once("../nettsted/php/classes/DBConnector.php");
    require_once("../nettsted/php/classes/Database.php");

    $dbCon = new DBConnector();
    $dbApi = new Database();

    @$valgt_table=$_POST ["table_name"];
    if (!$valgt_table) {
        $valgt_table=$_SESSION["table_name"];
    }

    // store session data
    $_SESSION["table_name"] = "$valgt_table";

    @$id = $_POST['rowID'];
    $column = array();
    $input = array();
    $data = array();
?>

<div id="content">
    <div id="innholdLeft"> 

        <form method="post" action="" id="velgtabellknapp" name="velgtabellknapp">
        <h3>Velg tabell</h3>
        <select name='table_name' id='table_list'>
        <?php
            $result = $dbApi->getTableNames();
            while($row = mysqli_fetch_row($result)) 
                {
                    echo("<option value='" . $row[0] . "'");
                    if ($row[0]==$valgt_table) {echo("selected");}
                    echo(">" . $row[0] . "</option>");
                }
        ?>
        </select><input type='submit' value='OK' name='velgtabellknapp' id='velgtabellknapp'></form></div>
    <div id="innholdRight">


        <?php
        @$velgtabellknapp=$_POST ["velgtabellknapp"]; 
        if ($velgtabellknapp || $valgt_table)
            {
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
                        if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelRoomTypeID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID") {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");

                            $result = $dbApi->getAllRows("$foreigntable");
                             while($row = mysqli_fetch_row($result)) 
                           {
                            echo("<option value='" . $row[0] . "'>" . $row[0] . "</option>");
                            }

                            print ("</select></td>");
                        }
                        else if ($column[$nr]=="RoomID") {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");
                            echo("<option value='NULL'>-</option>");
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
                print ("<td><input type='submit' value='Add' name='addknapp' id='addknapp'></td> <input type='hidden' size='10' type='text' name='table_name' value='$valgt_table'></form><tr></table><br>");
                
            }

                    @$addknapp=$_POST ["addknapp"];
                    if ($addknapp)
                        {
                       
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
                        
                        for ($x=2;$x<=$rows;$x++)
                                {
                                
                                $data[$column[$nr]] = $input[$nr2];
                                
                             $nr++;
                             $nr2++;
                             }
                             print_r($data);
                        $result = $dbApi->insertRow($valgt_table, $data);
                        if($result) {
                            echo("<br><span style='color:limegreen'>Successfully inserted row in table $valgt_table!</span><meta http-equiv='refresh' content='0'>");
                        }
                        else {
                            echo("<br><span style='color:red'><strong>Inserting of row into table $valgt_table FAILED!</strong></span>");
                        }
                    }

                       
            @$editknapp=$_POST ["editknapp"];
                    if ($editknapp)
                        {
                $result = $dbApi->getColumnNames("$valgt_table");
                print("<table><tr>");
                while($row = mysqli_fetch_row($result)) 
                {
                    echo("<th>" . $row[0] . "</th>");                
                }
                print("</tr>");
                
                $result = $dbApi->getRow($valgt_table, $id);
                while($row = mysqli_fetch_row($result)) 
                {
                    echo ("<tr><form action='' method='post' id='EditForm' name='EditForm'>");
                    $rows=count($column);
                    $nr=0;
                    for ($x=1;$x<=$rows;$x++)
                     {
                        if ($column[$nr]=="RoomTypeID" || $column[$nr]=="ImageID" || $column[$nr]=="HotelRoomTypeID" || $column[$nr]=="HotelID"  || $column[$nr]=="CustomerOrderID" ) 
                            {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");
                            $result2 = $dbApi->getAllRows("$foreigntable");
                            while($row2 = mysqli_fetch_row($result2)) 
                                {
                                echo("<option value='" . $row2[0] . "'");
                                if ($row2[0]==$row[$nr]) 
                                    {
                                        echo("selected");
                                    }
                                echo(">" . $row2[0] . "</option>");
                                }
                            print ("</select></td>");
                            }
                            else if ($column[$nr]=="RoomID") 
                            {
                            $foreigntable= substr($column[$nr], 0, -2);
                            print ("<td><select name=$nr type='input'>");
                            echo("<option value='NULL'>-</option>");
                            $result2 = $dbApi->getAllRows("$foreigntable");
                            while($row2 = mysqli_fetch_row($result2)) 
                                {
                                echo("<option value='" . $row2[0] . "'");
                                if ($row2[0]==$row[$nr]) 
                                    {
                                        echo("selected");
                                    }
                                echo(">" . $row2[0] . "</option>");
                                }
                            print ("</select></td>");
                            }
                            else if ($column[$nr]=="ID") 
                            {
                                echo("<td>" . $row["$nr"] . "</td>");
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
                          </td></form></tr></table>");
                }
                        
                    }
           

                    @$updateknapp=$_POST ["updateknapp"];
                    if ($updateknapp)
                        {
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
                        for ($x=2;$x<=$rows;$x++)
                                {
                                $data[$column[$nr]] = $input[$nr2];
                             $nr++;
                             $nr2++;
                             }
                        print_r($data);
                        $result = $dbApi->updateRow($valgt_table, $id, $data);
                        if($result) {
                            echo("<br><span style='color:limegreen'>Successfully updated row in table $valgt_table!</span><meta http-equiv='refresh' content='0'>");
                        }
                        else {
                            echo("<br><span style='color:red'><strong>Updating of row into table $valgt_table FAILED!</strong></span>");
                        }
                    }

            @$deleteknapp=$_POST ["deleteknapp"];
                    if ($deleteknapp)
                        {
                        $result = $dbApi->deleteRow($valgt_table, $id);
                         if($result) {
                            echo("<br><span style='color:limegreen'>Successfully deleted row in table $valgt_table!</span><meta http-equiv='refresh' content='0'>");
                        }
                        else {
                            echo("<br><span style='color:red'><strong>Deleting of row ($id) in table $valgt_table FAILED!</strong></span>");
                        }
                    }



        ?></div>
</div>

<?php require_once("template/end.html") ?>