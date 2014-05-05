<?php

// *************************************************************
// file: edit_item.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The page used to edit information on an equipment item. This loads all the data from the database into a page that allows easy editing. 
// 
// *************************************************************


// include nav bar and other default page items
include('header.php');

// check the session to see if the person is authenticated
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}
// Manager or User
if($_SESSION['access']==ADMIN_PERMISSION  OR $_SESSION['access']==USER_PERMISSION ) {

// Set aside the info for this unit
/* 
    assignment exists
        assignment is unchanged
        assignement is changed
        assignment is removed       - change says *de-assigned* for department/user

    assignment does not exist
        assignment is not created
        assignment is created       - change says *assigned* for department/user
*/

// assignment status flag has 5 values; 0 means assignment does not exist and was not made
//                                      1 means assignment does not exist and was made
//                                      2 means assignment exists and was not changed
//                                      3 means assignment exists and was changed
//                                      4 means assignment exists and was removed
$grandChangeFlag = 0;
$assignmentStatusFlag = 0;
$thisUnitInfo;
$thereIsAssignmentData;
$assignmentInfo;
$userID;
$departmentID;
$computer_id;
$changeAsgnType = 0;
$changeAsgnDept = 0;
$changeAsgnFullTime = 0;
$changeAsgnPrimary = 0;
$changeAsgnFacstaff = 0;

include('open_db.php');
if (isset($_GET['control']))
{
    $unitQuery = "SELECT *
    from computers
    WHERE control = " . $_GET['control'] . ";";
    $unitItem = sqlsrv_query($conn, $unitQuery);
    sqlsrvErrorLinguist($unitItem, "There was a problem getting that unit's information");
    $thisUnitInfo = sqlsrv_fetch_array($unitItem);
    $computer_id = $thisUnitInfo['computer_id'];
    $commentquery = "SELECT * FROM comments 
    WHERE computer_id = " . $computer_id . "
    ORDER BY created_at;";
    $commentresult = sqlsrv_query($conn, $commentquery);
    sqlsrvErrorLinguist($commentresult, "SQL problem output 103");
}
if (isset($_POST['compID']))
{
    $unitQuery = "SELECT *
    from computers
    WHERE computer_id = " . $_POST['compID'] . ";";
    $unitItem = sqlsrv_query($conn, $unitQuery);
    sqlsrvErrorLinguist($unitItem, "There was a problem getting that unit's information");
    $thisUnitInfo = sqlsrv_fetch_array($unitItem);
    $computer_id = $thisUnitInfo['computer_id'];
    $thisUnitChanges = $thisUnitInfo;
    for ($computerCol = 0; $computerCol < count($thisUnitInfo); $computerCol++)
    {
        $thisUnitChanges[$computerCol] = 0;
    }
    $thisUnitChanges['purchase_date'] = 0;
}
$assignmentquery = "SELECT * FROM hardware_assignments WHERE computer = " . $computer_id . " AND end_assignment IS NULL;";
$assignmentresult = sqlsrv_query($conn, $assignmentquery);
sqlsrvErrorLinguist($assignmentresult, "SQL problem output 282");
$thereIsAssignmentData = sqlsrv_has_rows( $assignmentresult );

$commentquery = "SELECT * FROM comments 
            WHERE computer_id = " . $computer_id . "
            ORDER BY created_at DESC;";
            $commentresult = sqlsrv_query($conn, $commentquery);
            sqlsrvErrorLinguist($commentresult, "SQL problem output 103");

$populationQuery = "SELECT DISTINCT OnCampusDepartment
                FROM FacStaff
                WHERE OnCampusDepartment IS NOT NULL;";
$facultyQuery = "SELECT FirstName, LastName, ID
                FROM FacStaff ORDER by LastName ASC;";
$populationResult = sqlsrv_query($conn, $populationQuery);
sqlsrvErrorLinguist($populationResult, "SQL problem output 361");
//The sqlsrv_query function allows PHP to make a query against the database
//and returns the resulting data
$facultyResult = sqlsrv_query($conn, $facultyQuery);
sqlsrvErrorLinguist($facultyResult, "SQL problem: the user for that computer could not be found");


$assignmentChanges;
if ($thereIsAssignmentData)
{
    $assignmentInfo = sqlsrv_fetch_array($assignmentresult);
    $userID = $assignmentInfo["user_id"];
    $departmentID = $assignmentInfo["department_id"];
    if ($userID == "unassigned" && $departmentID == "unassigned")
    {
        $assignmentStatusFlag = 2;
    }
}

if (isset($userID))
{
$holderQuery = "SELECT FirstName, LastName
                FROM FacStaff 
                WHERE ID = $userID
                ORDER by LastName ASC;";
$holderResult = sqlsrv_query($conn, $holderQuery);
sqlsrvErrorLinguist($holderResult, "SQL problem with getting user for this computer");
$facstaffRow = sqlsrv_fetch_array($holderResult);
}

if (isset($_SESSION['user'])) {
    $lastUpdatedBy = $_SESSION['user'];
}

        if (isset($_POST['submit'])){
            //connect to the database 
            $serverName = "sql05train1.gordon.edu";
            $connectionInfo = array(
            'Database' => 'CTSEquipment');
            $conn = sqlsrv_connect($serverName, $connectionInfo);    
            //display error if database cannot be accessed 
            if (!$conn ) 
            {
                echo('<div data-alert class="alert-box warning">
                      Sorry! Database is unavailable. 
                      <a href="#" class="close">&times;</a>
                    </div>');
                echo( print_r( sqlsrv_errors(), true));
            }

            $timezone = new DateTimeZone("UTC");
            $lastUpdatedAt = new DateTime("now", $timezone);
            $lastUpdatedAtString = $lastUpdatedAt->format('Y-m-d H:i:s');
            $createdAt = new DateTime("now", $timezone);
            $createdAtString = $createdAt->format('Y-m-d H:i:s');
            $startAssignment = new DateTime("now", $timezone);
            $startAssignmentString = $startAssignment->format('Y-m-d H:i:s');

            //assign form input to variables
            // post data 
            $computerID = $_POST['compID'];

            $controlNumber = $_POST['controlNumber'];
            $manufacturer = $_POST['manufacturer'];
            $model = $_POST['model'];
            $serialNumber = $_POST['serialNumber'];
            $ram = $_POST['ram'];
            $hdSize = $_POST['hdSize'];
            $partNumber = $_POST['partNumber'];
            $equipmentType;
            if (isset($_POST['equipmentType'])) {
                $equipmentType = $_POST['equipmentType'];
            } else {
                $equipmentType = 0;
            }
            $warrantyLength = $_POST['warrantyLength'];
            $accountNumber = $_POST['accountNumber'];
            $purchaseDate = $_POST['purchaseDate'];
            $warrantyStart = $purchaseDate;
            $purchasePrice = $_POST['purchasePrice'];
            $usageStatus = $_POST['usageStatus'];
            $replacementYear = $_POST['replacementYear'];

            $selectFacultyStaffID = $_POST['userName'];
            $department = $_POST['department'];
            $department = str_replace("'", "''", $department);
            $assignmentType = $_POST['assignmentType'];
            $primary_computer;
            $full_time;

            if (isset($_POST['primaryComputer'])) {
                $primary_computer = $_POST['primaryComputer'];
            } else {
                $primary_computer = 0;
            }

            if (isset($_POST['fullTime'])) {
                $full_time = $_POST['fullTime'];
            } else {
                $full_time = 0;
            }

            // Check for changes to this unit's info
            if ($controlNumber != $thisUnitInfo['control'])
            {
                $thisUnitChanges['control'] = 1;
                $grandChangeFlag = 1;
            }
            if ($manufacturer != $thisUnitInfo['manufacturer'])
            {
                $thisUnitChanges['manufacturer'] = 1;
                $grandChangeFlag = 1;
            }
            if ($model != $thisUnitInfo['model'])
            {
                $thisUnitChanges['model'] = 1;
                $grandChangeFlag = 1;
            }
            if ($serialNumber != $thisUnitInfo['serial_num'])
            {
                $thisUnitChanges['serial_num'] = 1;
                $grandChangeFlag = 1;
            }
            if ($ram != $thisUnitInfo['memory'])
            {
                $thisUnitChanges['memory'] = 1;
                $grandChangeFlag = 1;
            }
            if ($hdSize != $thisUnitInfo['hard_drive'])
            {
                $thisUnitChanges['hard_drive'] = 1;
                $grandChangeFlag = 1;
            }
            if ($partNumber != $thisUnitInfo['part_number'])
            {
                $thisUnitChanges['part_number'] = 1;
                $grandChangeFlag = 1;
            }
            if ($equipmentType != $thisUnitInfo['computer_type'])
            {
                $thisUnitChanges['computer_type'] = 1;
                $grandChangeFlag = 1;
            }
            if ($warrantyLength != $thisUnitInfo['warranty_length'])
            {
                $thisUnitChanges['warranty_length'] = 1;
                $grandChangeFlag = 1;
            }
            if ($accountNumber != $thisUnitInfo['purchase_acct'])
            {
                $thisUnitChanges['purchase_acct'] = 1;
                $grandChangeFlag = 1;
            }
            if ($purchaseDate != $thisUnitInfo['purchase_date']->format('Y-m-d'))
            {
                $thisUnitChanges['purchase_date'] = 1;
                $grandChangeFlag = 1;
            }
            if ($purchasePrice != $thisUnitInfo['purchase_price'])
            {
                $thisUnitChanges['purchase_price'] = 1;
                $grandChangeFlag = 1;
            }
            if ($replacementYear != $thisUnitInfo['replacement_year'])
            {
                $thisUnitChanges['replacement_year'] = 1;
                $grandChangeFlag = 1;
            }
            if ($usageStatus != $thisUnitInfo['usage_status'])
            {
                $thisUnitChanges['usage_status'] = 1;
                $grandChangeFlag = 1;
            }

            if ($selectFacultyStaffID != "unassigned" || $department != "unassigned")
            {
                if (!$thereIsAssignmentData)
                {
                    $assignmentStatusFlag = 1;
                }
                else
                {
                    if ($selectFacultyStaffID != $facultyResult['ID'])
                    {
                        $changeAsgnFacstaff = 1;
                        if ($thereIsAssignmentData)
                        {
                            $assignmentStatusFlag = 3;
                        }
                    }
                    if ($primary_computer != $assignmentInfo['primary_computer'])
                    {
                        $changeAsgnPrimary = 1;
                        if ($thereIsAssignmentData)
                        {
                            $assignmentStatusFlag = 3;
                        }
                    }
                    if ($department != $assignmentInfo['department_id'])
                    {
                        $changeAsgnDept = 1;
                        if ($thereIsAssignmentData)
                        {
                            $assignmentStatusFlag = 3;
                        }
                    }
                    if ($full_time != $assignmentInfo['full_time'])
                    {
                        $changeAsgnFullTime = 1;
                        if ($thereIsAssignmentData)
                        {
                            $assignmentStatusFlag = 3;
                        }
                    }
                    if ($assignmentType != $assignmentInfo['assignment_type'])
                    {
                        $changeAsgnType = 1;
                        if ($thereIsAssignmentData)
                        {
                            $assignmentStatusFlag = 3;
                        }
                    }
                }
            }
            else
            {
                if ($thereIsAssignmentData)
                {
                    $assignmentStatusFlag = 4;
                }
            }



            $notes = "N/A";
            if (isset($_POST['notes']))
            {
                $notes = $_POST['notes'];
                $notes = str_replace("'", "''", $notes);
            }

            if (isset($_POST['notes']) && $_POST['notes'] != " " && $_POST['notes'] != "")
            {
                $commentContent = $notes;
                $commentquery = "INSERT INTO
                comments
                ([body],[last_updated_by],[user_name],[last_updated_at],[created_at],[computer_id])VALUES('$commentContent','$lastUpdatedBy','$lastUpdatedBy','$createdAtString','$createdAtString','$computer_id');";
                $commentresult = sqlsrv_query($conn, $commentquery);
                sqlsrvErrorLinguist($commentresult, "SQL problem output 101");
            }
                
                if(isset($_POST['controlNumber']))
                {
                    $search = $_POST['controlNumber'];

                    //SQL query to insert variables above into table
                    $insertComputer = "UPDATE dbo.computers 
                                    set last_updated_by = '$lastUpdatedBy', 
                                    last_updated_at = '$lastUpdatedAtString', 
                                    control = '$controlNumber',
                                    serial_num = '$serialNumber',
                                    model = '$model',
                                    manufacturer = '$manufacturer',
                                    purchase_date = '$purchaseDate',
                                    purchase_price = '$purchasePrice',
                                    purchase_acct = '$accountNumber',
                                    usage_status = '$usageStatus',
                                    memory = $ram,
                                    hard_drive = $hdSize,
                                    warranty_length = $warrantyLength,
                                    warranty_start = '$warrantyStart',
                                    replacement_year = $replacementYear,
                                    computer_type = $equipmentType,
                                    part_number = '$partNumber'
                                    WHERE computer_id = $computerID;";

                    $computer = sqlsrv_query($conn, $insertComputer);
                    sqlsrvErrorLinguist($computer, "There was a problem with making that change
                     - check to see if there is a computer with that control already");

                    $warrantyLenParsed = getWarrantyOutputFromValue($warrantyLength);
                    $equipmentTypeParsed = getEquipmentTypeOutputFromValue($equipmentType);
                    if(isset($assignmentInfo['assignment_type']))
                    {
                        $assignmentTypeParsed = getAssignmentTypeOutputFromValue($assignmentInfo['assignment_type']);
                    $assignmentTypeParsed; 
                    if (isset($assignmentInfo['assignment_type']))
                    {
                    $assignmentTypeParsed = getAssignmentTypeOutputFromValue($assignmentInfo['assignment_type']);
                    }
                    $changeContent = "";
                    if ($grandChangeFlag == 1)
                    {
                        // Add the changes content
                        
                        $changeContent .= "ID: $computer_id\n";
                        if($thisUnitChanges['control'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Control: " . $thisUnitInfo['control'] . "\n";
                        if($thisUnitChanges['serial_num'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Serial number: " . $thisUnitInfo['serial_num'] . "\n";
                        if($thisUnitChanges['usage_status'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Usage status: " . $thisUnitInfo['usage_status'] . "\n";
                        if($thisUnitChanges['memory'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Memory: " . $thisUnitInfo['memory'] . "\n";
                        if($thisUnitChanges['hard_drive'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Hard drive: " . $thisUnitInfo['hard_drive'] . "\n";
                        if($thisUnitChanges['warranty_length'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Warranty length: " . $warrantyLenParsed . "\n";
                        if($thisUnitChanges['replacement_year'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Replacement year: $" . $thisUnitInfo['replacement_year'] . "\n";
                        if($thisUnitChanges['manufacturer'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Manufacturer: " . $thisUnitInfo['manufacturer'] . "\n";
                        if($thisUnitChanges['part_number'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Part number: " . $thisUnitInfo['part_number'] . "\n";
                        if($thisUnitChanges['purchase_date'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Purchase date: " . $thisUnitInfo['purchase_date']->format('Y-m-d') . "\n";
                        if($thisUnitChanges['purchase_acct'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Purchase account: " . $thisUnitInfo['purchase_acct'] . "\n";
                        if($thisUnitChanges['computer_type'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Computer type: " . $equipmentTypeParsed . "\n";
                        if($thisUnitChanges['purchase_price'] == 1){$changeContent .= "*** CHANGED *** ";}
                        $changeContent .= "Purchase price: " . $thisUnitInfo['purchase_price'] . "\n";
                    }
                    if ($assignmentStatusFlag != 0 && $assignmentStatusFlag != 2)
                    {
                        if ($assignmentStatusFlag == 1)
                        {
                            $changeContent .= "*** ASSIGNMENT ADDED *** \n";
                            $changeContent .= "*** ASSIGNMENT ADDED *** \n";
                            $changeContent .= "*** ASSIGNMENT ADDED *** \n";
                            $changeContent .= "*** ASSIGNMENT ADDED *** \n";
                            $changeContent .= "*** ASSIGNMENT ADDED *** \n";
                        }
                        if ($assignmentStatusFlag == 3)
                        {
                            if($changeAsgnFacstaff == 1){$changeContent .= "*** CHANGED *** ";}
                            $changeContent .= "Assigned user: ";
                            if (isset($facstaffRow['FirstName']))
                            {
                                $changeContent .= $facstaffRow['FirstName'] . " " . $facstaffRow['LastName'];
                            }
                            $changeContent .=  "\n";
                            if($changeAsgnDept == 1){$changeContent .= "*** CHANGED *** ";}
                            $changeContent .= "Department assigned: ";
                            if (isset($assignmentInfo['department_id']))
                            {
                                $changeContent .= $assignmentInfo['department_id'];
                            }
                            $changeContent .=  "\n";
                            if($changeAsgnType == 1){$changeContent .= "*** CHANGED *** ";}
                            $changeContent .= "Assignment type: " . $assignmentTypeParsed . "\n";
                            if($changeAsgnPrimary == 1){$changeContent .= "*** CHANGED *** ";}
                            $changeContent .= "Is primary computer: " . $assignmentInfo['primary_computer'] . "\n";
                            if($changeAsgnFullTime == 1){$changeContent .= "*** CHANGED *** ";}
                            $changeContent .= "Is full time: " . $assignmentInfo['full_time'] . "\n";
                        }
                        if ($assignmentStatusFlag == 4)
                        {
                            $changeContent .= "*** ASSIGNMENT REMOVED *** Assigned user: ";
                            if (isset($facstaffRow['FirstName']))
                            {
                                $changeContent .= $facstaffRow['FirstName'] . " " . $facstaffRow['LastName'];
                            }
                            $changeContent .=  "\n";
                            $changeContent .= "*** ASSIGNMENT REMOVED *** Department assigned: ";
                            if (isset($assignmentInfo['department_id']))
                            {
                                $changeContent .= $assignmentInfo['department_id'];
                            }
                            $changeContent .=  "\n";
                            $changeContent .= "*** ASSIGNMENT REMOVED *** Assignment type: " . $assignmentTypeParsed . "\n";
                            $changeContent .= "*** ASSIGNMENT REMOVED *** Is primary computer: " . $assignmentInfo['primary_computer'] . "\n";
                            $changeContent .= "*** ASSIGNMENT REMOVED *** Is full time: " . $assignmentInfo['full_time'] . "\n";
                        }
                    }
                    if ($grandChangeFlag == 1 || ($assignmentStatusFlag != 0 && $assignmentStatusFlag != 2))
                    {
                        $changeQuery = "INSERT INTO
                        changes
                        ([body],[last_updated_by],[creator],[last_updated_at],[created_at],[computer_id])VALUES('$changeContent','$lastUpdatedBy','$lastUpdatedBy','$createdAtString','$createdAtString','$computer_id');";
                        $changeResult = sqlsrv_query($conn, $changeQuery);
                        sqlsrvErrorLinguist($changeResult, "SQL problem output 108");
                    }

                    $timezone = new DateTimeZone("UTC");
                    $endTime = new DateTime("now", $timezone);
                    $endTimeFormatted = $endTime->format("Y-m-d\TH:i:s");
                    if (!$thereIsAssignmentData )
                    {
                        if ($department != "unassigned" || $selectFacultyStaffID != "unassigned")
                        {
                            if ($department == "unassigned")
                            {
                                // THIS IS IN CASE A "NULL" DEPARTMENT EVER NEEDS A DIFFERENT DEFAULT VALUE
                            }
                            if ($selectFacultyStaffID == "unassigned")
                            {
                                $insertAssignment = "INSERT INTO dbo.hardware_assignments ( [computer],  [last_updated_by], [last_updated_at], [created_at], [department_id], [full_time], [primary_computer], [start_assignment], [assignment_type], [nextneed_note])VALUES($computer_id, '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', '$department', '$full_time', '$primary_computer', '$startAssignmentString', '$assignmentType', '$notes')";
                            }
                            else
                            {
                                $insertAssignment = "INSERT INTO dbo.hardware_assignments ( [computer],  [last_updated_by], [last_updated_at], [created_at], [user_id], [department_id], [full_time], [primary_computer], [start_assignment], [assignment_type], [nextneed_note])VALUES($computer_id, '$lastUpdatedBy', '$lastUpdatedAtString', '$createdAtString', $selectFacultyStaffID, '$department', '$full_time', '$primary_computer', '$startAssignmentString', '$assignmentType', '$notes')";
                            }

                            $assignment = sqlsrv_query($conn, $insertAssignment);
                            sqlsrvErrorLinguist($assignment, "SQL problem with creating this assignment");
                        }
                    }
                    else
                    {
                        if ($department != "unassigned" || $selectFacultyStaffID != "unassigned")
                        {
                            if ($department == "unassigned")
                            {
                                // THIS IS IN CASE A "NULL" DEPARTMENT EVER NEEDS A DIFFERENT DEFAULT VALUE
                            }
                            if ($selectFacultyStaffID == "unassigned")
                            {
                                $insertAssignment = "UPDATE dbo.hardware_assignments 
                                                set computer = $computerID,
                                                last_updated_by = '$lastUpdatedBy',
                                                last_updated_at = '$lastUpdatedAtString',
                                                user_id = NULL,
                                                department_id = '$department',
                                                full_time = '$full_time',
                                                primary_computer = '$primary_computer',
                                                assignment_type = '$assignmentType',
                                                nextneed_note = '$notes'
                                                WHERE computer = $computerID;";
                            }
                            else
                            {
                                $insertAssignment = "UPDATE dbo.hardware_assignments 
                                                set end_assignment = '" . $endTimeFormatted . "'
                                                WHERE computer = $computerID
                                                AND end_assignment IS NULL;";

                                $insertAssignment = "UPDATE dbo.hardware_assignments 
                                                set computer = $computerID,
                                                last_updated_by = '$lastUpdatedBy',
                                                last_updated_at = '$lastUpdatedAtString',
                                                user_id = $selectFacultyStaffID,
                                                department_id = '$department',
                                                full_time = '$full_time',
                                                primary_computer = '$primary_computer',
                                                assignment_type = '$assignmentType',
                                                nextneed_note = '$notes'
                                                WHERE computer = $computerID;";
                            }

                            $assignment = sqlsrv_query($conn, $insertAssignment);
                            sqlsrvErrorLinguist($assignment, "SQL problem with updating this assignment");
                        }
                        else
                        {

                            $insertAssignment = "UPDATE dbo.hardware_assignments 
                                                set end_assignment = '" . $endTimeFormatted . "'
                                                WHERE computer = $computerID
                                                AND end_assignment IS NULL;";
                                                
                            $assignment = sqlsrv_query($conn, $insertAssignment);
                            sqlsrvErrorLinguist($assignment, "SQL problem output 123");
                        }
                    }

                    ?>
                    <div class="row">
                    <div class="large-10 columns">
                    <h1>Successfully Edited Item</h1>
                    <a class="button" href="<?php echo "info.php?id=". $search; ?>">Click here to view the item</a>
                    </div>
                    </div>
                    <?php
                    $unitQuery = "SELECT *
                    from computers
                    WHERE computer_id = $computerID;";
                    $unitItem = sqlsrv_query($conn, $unitQuery);
                    sqlsrvErrorLinguist($unitItem, "There was a problem getting this unit's information");
                    $thisUnitInfo = sqlsrv_fetch_array($unitItem);
           }    
        // not a post request
        } else 
        {     
            // Check the URL variables 
            if(isset($_GET['control']))
            {   
                $search = $_GET['control'];
                $itemquery = "SELECT * FROM computers
                        WHERE control = " . $search . " ";

                $commentquery = "SELECT * FROM comments JOIN users 
                        WHERE comments.computer = " . $search . "
                        ORDER BY comment.created_at;";

                $result = sqlsrv_query($conn, $itemquery);
                sqlsrvErrorLinguist($result, "SQL problem output 151");
                /* <D> COMMENT NEEDS ID $commentresult = sqlsrv_query($conn, $commentquery);
                sqlsrvErrorLinguist($commentresult, "SQL problem output 153"); */

                // If a computer matches this control number, return its data
                while($row = sqlsrv_fetch_array($result))
                {
            ?>
            <!-- begin form -->
            <div class="row">
            <div class="large-12 large-centered columns">
            <h1>Edit Equipment Item</h1>
            <ul class="breadcrumbs">
              <li><a href="home.php">Home</a></li>
              <li class="current"><a href="#">Edit Item</a></li>
            </ul>
            <form data-abide action="edit_item.php" method="POST">
                <?php echo "<input type='hidden' name='compID' value=" . $row['computer_id'] . ">"; ?>
                <fieldset>
                    <legend>Equipment Info</legend>

                    <div class="row">
                        <div class="large-4 columns">
                            <label>Control Number</label>
                                <input type="text" name="controlNumber" value=<?php echo $row["control"]; ?> maxlength="10" required>
                            <small class="error">A valid Control Number is required.</small>
                        </div>
                        <div class="large-4 columns">
                            <label>Manufacturer</label>
                                <input type="text" name="manufacturer" value=<?php echo $row["manufacturer"]; ?> maxlength="20" required>
                        </div>
                        <div class="large-4 columns">
                            <label>Model</label>
                                <input type="text" name="model" value=<?php echo $row["model"]; ?> maxlength="10" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-3 columns">
                            <label>Serial Number</label>
                                <input type="text" name="serialNumber" value=<?php echo $row["serial_num"]; ?> maxlength="20" required>
                        </div>
                        <div class="large-3 columns">           
                            <div class="row collapse">      
                                <label>Memory Amount </label>
                                <div class="small-9 columns">
                                        <input type="number" name="ram" value=<?php echo $row["memory"]; ?> max='2000000000' required>
                                </div>
                                <div class="small-3 columns">
                                     <span class="postfix">GB's</span >
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">           
                            <div class="row collapse">      
                                <label>Hard Drive Size</label>
                                <div class="small-9 columns">
                                        <input type="number" name="hdSize" value=<?php echo $row["hard_drive"]; ?> max='2000000000' required>
                                </div>
                                <div class="small-3 columns">
                                     <span class="postfix">GB's</span>
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">           
                            <div class="row collapse">      
                                <label>Usage Status</label>
                                <select class="medium" name="usageStatus" required>   
                                    <option <?php if($row["usage_status"] == "circulation") {echo "selected";} ?> value="circulation">In Circulation</option>
                                    <option <?php if($row["usage_status"] == "retired") {echo "selected";} ?> value="retired">Retired</option>
                                    <option <?php if($row["usage_status"] == "sold") {echo "selected";} ?> value="sold">Sold</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-4 columns">
                            <label>Part Number</label>
                                <input type="text" name="partNumber" value=<?php echo $row["part_number"]; ?> maxlength="20" required>
                        </div>
                        <div class="large-4 columns">
                            <label>Equipment Type</label>
                                <select class="medium" name="equipmentType" required>   
                                    <option <?php if($row["computer_type"] == "1") {echo "selected";} ?> value="1">Laptop</option>
                                    <option <?php if($row["computer_type"] == "2") {echo "selected";} ?> value="2">Desktop</option>
                                    <option <?php if($row["computer_type"] == "3") {echo "selected";} ?> value="3">Tablet</option>
                                </select>
                        </div>
                        <div class="large-4 columns">
                            <label>Warranty Length</label>
                            <select class="medium" name="warrantyLength" >
                                <option <?php if($row["warranty_length"] == "1") {echo "selected";} ?> value="1">1 Year</option>
                                <option <?php if($row["warranty_length"] == "2") {echo "selected";} ?> value="2">2 Years</option>
                                <option <?php if($row["warranty_length"] == "3") {echo "selected";} ?> value="3">3 Years</option>
                                <option <?php if($row["warranty_length"] == "4") {echo "selected";} ?> value="4">4 Years</option>
                                <option <?php if($row["warranty_length"] == "5") {echo "selected";} ?> value="5">Expired</option>
                            </select>
                        </div>

                        </div>
                </fieldset>
                <fieldset>
                    <legend>Purchasing Info</legend>

                    <div class="row">
                        <div class="large-3 columns">
                            <label>Purchasing Account Number</label>
                                <input type="text" name="accountNumber" value=<?php echo $row["purchase_acct"]; ?> maxlength="20" required>
                        </div>
                        <div class="large-3 columns">
                            <label>Purchasing Date</label>
                                <input type="date" name="purchaseDate" value=<?php  echo $row["purchase_date"]->format('Y-m-d') ; ?> required>
                        </div>
                        <div class="large-3 columns">       
                            <div class="row collapse">      
                                <label>Purchasing Price</label>
                                <div class="small-3 columns">
                                    <span class="prefix">&#36;</span>
                                </div>
                                <div class="small-9 columns">
                                    <input type="number" name="purchasePrice" value=<?php echo $row["purchase_price"]; ?> max='2000000000' required>
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">
                            <label>Replacement Year</label>
                                <input type="text" name="replacementYear" value=<?php echo $row["replacement_year"]; ?> maxlength="10" required>
                        </div>
                    </div>
                </fieldset>
            <?php  
                }
            $selectNewInsert = "SELECT computer_ID from dbo.computers where control = " . $search . " ";
            $computer_id_result = sqlsrv_query($conn, $selectNewInsert);
            sqlsrvErrorLinguist($computer_id_result, "SQL problem output 272");
            while( $row = sqlsrv_fetch_array( $computer_id_result, SQLSRV_FETCH_ASSOC) ) 
                {

                  $computer_id = $row['computer_ID'];
                }

            
                ?>
                    <fieldset>
                        <legend>Computer Assignment Info</legend> 
                        <div class="row">
                            <div class="large-4 columns">
                                <label>User Name</label>
                                <select class="medium" name="userName" required>
                                    <option value="unassigned">Unassigned</option>
                                      <?php 
                                      while($row = sqlsrv_fetch_array($facultyResult))
                                        {
                                        echo "<option ";
                                        if (isset($userID) && $userID == $row["ID"] && $thereIsAssignmentData) {
                                            echo "selected ";
                                        }    
                                        echo "value=\"";
                                        echo $row["ID"];
                                        echo "\">";
                                        echo $row["LastName"] . ", " . $row["FirstName"] . "</option>";
                                        }
                                      ?>
                                </select>
                            </div>
                            <div class="large-4 columns">
                                <label>Department (if not assigned to a person)</label>
                                <select class="medium" name="department" required>
                                    <option value="unassigned">Unassigned</option>
                                    <?php 
                                    while($row = sqlsrv_fetch_array($populationResult))
                                      {
                                      echo "<option ";
                                      if (isset($departmentID) && $departmentID == $row["OnCampusDepartment"] && $thereIsAssignmentData) {
                                          echo "selected ";
                                      }
                                      echo "value=\"";
                                      echo $row["OnCampusDepartment"];
                                      echo "\">";
                                      echo $row["OnCampusDepartment"];
                                      }
                                    ?>
                                </select>
                            </div>
                            <div class="large-4 columns">       
                                <label>Assignment Type</label>
                                <select class="medium" name="assignmentType" required>
                                <?php
                                $assignmentresult = sqlsrv_query($conn, $assignmentquery);
                                sqlsrvErrorLinguist($assignmentresult, "SQL problem output 356");
                                $assignmentRow = sqlsrv_fetch_array($assignmentresult);
                                 ?>
                                    <option value="1" <?php if($assignmentRow["assignment_type"] == DEDICATED_COMPUTER_ASSIGNMENT_TYPE) {echo " selected";} ?>>Dedicated Computer</option>
                                    <option value="2" <?php if($assignmentRow["assignment_type"] == SPECIAL_ASSIGNMENT_TYPE) {echo " selected";} ?>>Special</option>
                                    <option value="3" <?php if($assignmentRow["assignment_type"] == LAB_ASSIGNMENT_TYPE) {echo " selected";} ?>>Lab</option>
                                    <option value="4" <?php if($assignmentRow["assignment_type"] == KIOSK_ASSIGNMENT_TYPE) {echo " selected";} ?>>Kiosk</option>
                                    <option value="5" <?php if($assignmentRow["assignment_type"] == PRINTER_ASSIGNMENT_TYPE) {echo " selected";} ?>>Printer</option>
                                </select>
                            </div>
                        </div>    
                            <div class="row">
                                <div class="large-2 columns">
                                    <label>Check if Primary Computer</label>
                                    <input type="checkbox" name="primaryComputer" value="1" <?php if($assignmentRow["primary_computer"] == "1") {echo "checked";} ?>>

                                </div>  
                                <div class="large-2 columns">
                                    <label>Check if Full Time Assignment</label>
                                    <input type="checkbox" name="fullTime" value="1" data-invalid <?php if($assignmentRow["full_time"] == "1") {echo "checked";} ?>>
                                </div>
                                <div class="large-8 columns">
                                    <label>Notes</label>
                                    <textarea name="notes"></textarea>
                                </div>
                            </div>

                            <div class="large-12 columns">
                                <dl class="accordion" data-accordion>
                <?php 

                $panelNum = 0;
                while ($thisChange = sqlsrv_fetch_array($commentresult))
                {
                    $panelNum++;
                    ?>
                <dd>
                    <?php echo "<a href=\"#panel" . $panelNum . "\">"; ?>Commentary made at <?php  echo " " . $thisChange['created_at']->format('Y-m-d H:i:s') . " by " . $thisChange['user_name'] . " "; ?></a>
                    <?php echo "<div id=\"panel" . $panelNum . "\" class=\"content\">"; ?><kbd>
                    <?php

                    echo $thisChange['body'];
                    ?>
                    </kbd></div>
                </dd>
                    <?php
                }

                ?>
                </dl>
                            </div>
                    </fieldset>
                    <div class="large-12 columns">
                        <div class="row" align="center">
                            <input type="submit" name="submit" value="Save Changes" class="button expand" formmethod="post">
                        </div>
                    </div>
                    </div>
            <?php
            
            // else no control
        } else {
            
            // If the user does not set a control value, redirect to the home page.
            // header('Location: home.php');
        }
    // else not post
    }
    // Faculty
    // end user or admin
}    
if($_SESSION['access']==FACULTY_PERMISSION ) {
// Faculty should not have access to this page. 
header('Location: home.php');
}
//footer
sqlsrv_close($conn);
include('footer.php')
?>