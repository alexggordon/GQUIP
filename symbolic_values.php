<?php

// *************************************************************
// file: symbolic_values.php
// created by: Alex Gordon, Elliott Staude
// date: 04-6-2014
// purpose: The symbolic values page defines most important aspects of GQUIP. This file mainly defines the user classes and other sitewide aspects that might change. 
// 
// *************************************************************



    // Stuff specifically for Paginate
    const PAGE_VARIABLE_MIDDLE = 7;
    const SMALL_ITEMS_PER_PAGE = 10;
    const DEFAULT_ITEMS_PER_PAGE = 25;
    const BIG_ITEMS_PER_PAGE = 50;
    const GIANT_ITEMS_PER_PAGE = 100;
    
    // GQUIP user permission levels
    const USER_PERMISSION = 1;
    const FACULTY_PERMISSION = 2;
    const STAFF_PERMISSION = 2;
    const ADMIN_PERMISSION = 3;
    
    // Size constants for columns
    const SMALL_VARCHAR_SIZE = 45;
    const BIG_VARCHAR_SIZE = 255;

    // Column size values
    const TINY_COLUMN_SIZE = 4;
    const SMALL_COLUMN_SIZE = 10;
    const MEDIUM_COLUMN_SIZE = 20;
    const BIG_COLUMN_SIZE = 28;
    const HUGE_COLUMN_SIZE = 50;
    const GIANT_COLUMN_SIZE = 140;

    // Constants for drop-down selections
    const ONE_YEAR_WARRANTY_LENGTH = "1";
    const TWO_YEAR_WARRANTY_LENGTH = "2";
    const THREE_YEAR_WARRANTY_LENGTH = "3";
    const FOUR_YEAR_WARRANTY_LENGTH = "4";
    const EXPIRED_WARRANTY_LENGTH = "5";

    const LAPTOP_EQUIPMENT_TYPE = "1";
    const DESKTOP_EQUIPMENT_TYPE = "2";
    const TABLET_EQUIPMENT_TYPE = "3";
    
    const DEDICATED_COMPUTER_ASSIGNMENT_TYPE = "1";
    const SPECIAL_ASSIGNMENT_TYPE = "2";
    const LAB_ASSIGNMENT_TYPE = "3";
    const KIOSK_ASSIGNMENT_TYPE = "4";
    const PRINTER_ASSIGNMENT_TYPE = "5";


	//This array provides the application with column names for each of the tables used

	$columnArray['computers'] = array(
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "control",
 "serial_num",
 "model",
 "manufacturer",
 "purchase_date",
 "purchase_price",
 "purchase_acct",
 "usage_status",
 "memory",
 "hard_drive",
 "warranty_length",
 "warranty_start",
 "warranty_type",
 "replacement_year",
 "computer_type",
 "cameron_id",
 "part_number",
 "ip_address",
 "inventoried");
	$columnArray['comments'] = array(
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "user_name",
 "computer_id",
 "body");
	$columnArray['changes'] = array(
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "creator",
 "body");
	$columnArray['hardware_assignments'] = array(
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "user_id",
 "computer",
 "department_id",
 "full_time",
 "primary_computer",
 "start_assignment",
 "end_assignment",
 "assignment_type",
 "nextneed_note");
	$columnArray['FacandStaff'] = array(
 "OnCampusDepartment",
 "Dept",
 "Type",
 "FirstName",
 "LastName",
 "Email");
	$columnArray['gordonstudents'] = array(
 "FirstName",
 "MiddleName",
 "LastName",
 "Class",
 "Email",
 "grad_student");
	$columnArray['software'] = array(
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "name",
 "software_type");
	$columnArray['licenses'] = array(
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "date_sold",
 "id",
 "seller",
 "software_id");



	//This array provides the application with user-readable column naming

	$columnReadableArray['computers'] = array(
 "Last updated by",
 "Last updated at",
 "Created at",
 "Control number",
 "Serial number",
 "Model",
 "Manufacturer",
 "Purchasing date",
 "Purchasing price",
 "Purchasing account",
 "Usage status",
 "Memory size",
 "Hard drive size",
 "Warranty length",
 "Warranty start",
 "Warranty type",
 "Replacement year",
 "Computer type",
 "Camerion ID",
 "Part number",
 "IP address",
 "Inventory status");
	$columnReadableArray['comments'] = array(
 "Last updated by",
 "Last updated at",
 "Created at",
 "Commenter",
 "ID of computer commented on",
 "Contents");
	$columnReadableArray['changes'] = array(
 "Last updated by",
 "Last updated at",
 "Created at",
 "Change creator",
 "Contents");
	$columnReadableArray['hardware_assignments'] = array(
 "Last updated by",
 "Last updated at",
 "Created at",
 "User assigned",
 "ID of computer assigned",
 "Department of assignment",
 "Is a full time assignment",
 "Is user's primary computer",
 "Start of assignment",
 "End of assignment",
 "Type of assignment",
 "Notes on next computer needed");
	$columnReadableArray['FacandStaff'] = array(
 "Department",
 "DPT.",
 "Type",
 "First name",
 "Last name",
 "Email");
	$columnReadableArray['gordonstudents'] = array(
 "First name",
 "Middle name",
 "Last name",
 "Class",
 "Email",
 "Is a grad student");
	$columnReadableArray['software'] = array(
 "Last updated by",
 "Last updated at",
 "Created at",
 "Software name",
 "Software type");
	$columnReadableArray['licenses'] = array(
 "Last updated by",
 "Last updated at",
 "Created at",
 "Date_sold",
 "ID of student sold to",
 "Seller to student",
 "Software ID");


	//This array provides the application with the pixel sizes for each table column

	$columnSizesArray['computers'] = array(
 "last_updated_by" => MEDIUM_COLUMN_SIZE,
 "last_updated_at" => MEDIUM_COLUMN_SIZE,
 "created_at" => MEDIUM_COLUMN_SIZE,
 "control" => MEDIUM_COLUMN_SIZE,
 "serial_num" => MEDIUM_COLUMN_SIZE,
 "model" => MEDIUM_COLUMN_SIZE,
 "manufacturer" => MEDIUM_COLUMN_SIZE,
 "purchase_date" => MEDIUM_COLUMN_SIZE,
 "purchase_price" => MEDIUM_COLUMN_SIZE,
 "purchase_acct" => MEDIUM_COLUMN_SIZE,
 "usage_status" => MEDIUM_COLUMN_SIZE,
 "memory" => MEDIUM_COLUMN_SIZE,
 "hard_drive" => MEDIUM_COLUMN_SIZE,
 "warranty_length" => MEDIUM_COLUMN_SIZE,
 "warranty_start" => MEDIUM_COLUMN_SIZE,
 "warranty_type" => MEDIUM_COLUMN_SIZE,
 "replacement_year" => MEDIUM_COLUMN_SIZE,
 "computer_type" => MEDIUM_COLUMN_SIZE,
 "cameron_id" => MEDIUM_COLUMN_SIZE,
 "part_number" => MEDIUM_COLUMN_SIZE,
 "ip_address" => MEDIUM_COLUMN_SIZE,
 "inventoried" => MEDIUM_COLUMN_SIZE);
	$columnSizesArray['comments'] = array(
 "last_updated_by" => MEDIUM_COLUMN_SIZE,
 "last_updated_at" => MEDIUM_COLUMN_SIZE,
 "created_at" => MEDIUM_COLUMN_SIZE,
 "user_name" => MEDIUM_COLUMN_SIZE,
 "computer_id" => MEDIUM_COLUMN_SIZE,
 "body" => MEDIUM_COLUMN_SIZE);
	$columnSizesArray['changes'] = array(
 "last_updated_by" => MEDIUM_COLUMN_SIZE,
 "last_updated_at" => MEDIUM_COLUMN_SIZE,
 "created_at" => MEDIUM_COLUMN_SIZE,
 "creator" => MEDIUM_COLUMN_SIZE,
 "body" => MEDIUM_COLUMN_SIZE);
	$columnSizesArray['hardware_assignments'] = array(
 "last_updated_by" => MEDIUM_COLUMN_SIZE,
 "last_updated_at" => MEDIUM_COLUMN_SIZE,
 "created_at" => MEDIUM_COLUMN_SIZE,
 "computer" => MEDIUM_COLUMN_SIZE,
 "department_id" => MEDIUM_COLUMN_SIZE,
 "full_time" => MEDIUM_COLUMN_SIZE,
 "primary_computer" => MEDIUM_COLUMN_SIZE,
 "start_assignment" => MEDIUM_COLUMN_SIZE,
 "end_assignment" => MEDIUM_COLUMN_SIZE,
 "assignment_type" => MEDIUM_COLUMN_SIZE,
 "nextneed_note" => MEDIUM_COLUMN_SIZE);
	$columnSizesArray['FacandStaff'] = array(
 "OnCampusDepartment" => HUGE_COLUMN_SIZE,
 "Dept" => SMALL_COLUMN_SIZE,
 "Type" => SMALL_COLUMN_SIZE,
 "FirstName" => MEDIUM_COLUMN_SIZE,
 "LastName" => MEDIUM_COLUMN_SIZE,
 "Email" => BIG_COLUMN_SIZE);
	$columnSizesArray['gordonstudents'] = array(
 "FirstName" => MEDIUM_COLUMN_SIZE,
 "MiddleName" => BIG_COLUMN_SIZE,
 "LastName" => BIG_COLUMN_SIZE,
 "Class" => TINY_COLUMN_SIZE,
 "Email" => HUGE_COLUMN_SIZE,
 "grad_student" => TINY_COLUMN_SIZE);
	$columnSizesArray['software'] = array(
 "last_updated_by" => MEDIUM_COLUMN_SIZE,
 "last_updated_at" => MEDIUM_COLUMN_SIZE,
 "created_at" => MEDIUM_COLUMN_SIZE,
 "name" => MEDIUM_COLUMN_SIZE,
 "software_type" => MEDIUM_COLUMN_SIZE);
	$columnSizesArray['licenses'] = array(
 "last_updated_by" => MEDIUM_COLUMN_SIZE,
 "last_updated_at" => MEDIUM_COLUMN_SIZE,
 "created_at" => MEDIUM_COLUMN_SIZE,
 "date_sold" => MEDIUM_COLUMN_SIZE,
 "id" => MEDIUM_COLUMN_SIZE,
 "seller" => MEDIUM_COLUMN_SIZE,
 "software_id" => MEDIUM_COLUMN_SIZE);
 
 
 
 $tableFontsArray = array("FacandStaff" => "Faculty", "computers" => "Computers", "gordonstudents" => "Students",
 "hardware_assignments" => "Computer Assignments", "changes" => "Computer Records", "comments" => "Comments on Computers",
 "software" => "Software", "licenses" => "Software Licenses");
 

 
 $tableReadableArray = array("FacandStaff" => "Faculty", "computers" => "Computers", "gordonstudents" => "Students",
 "hardware_assignments" => "Computer Assignments", "changes" => "Computer Records", "comments" => "Comments on Computers",
 "software" => "Software", "licenses" => "Software Licenses");


 function sqlsrvErrorLinguist($sqlResult, $errorMessage = "There was a SQL querying problem", $location = "home.php")
 {
 	if(!$sqlResult)
	{
        //echo print_r( sqlsrv_errors(), true);
        if( ($errorArray = sqlsrv_errors() ) != null)
        {
            echo "<div class='row'><div class='large-10 large-centered columns'>";
            echo "<h3 class='large-centered'>Database problem:</h3>";
            echo "<p class='large-centered'>" . $errorMessage . "</p>";
            echo "</div></div>";

            echo "<div class=\"row\"> <div class=\"large-6 large-centered columns\">";
            echo "<br /><a href=\"" . $location . "\" class='button small large-centered'>OK</a>";
            echo "</div></div>";
            exit;
        }
	}
 }


 function getAssignmentTypeOutputFromValue($assignmentVal)
 {
     switch ($assignmentVal)
     {
         case DEDICATED_COMPUTER_ASSIGNMENT_TYPE:
             return "Dedicated computer";
         case SPECIAL_ASSIGNMENT_TYPE:
             return "Special assignment";
         case LAB_ASSIGNMENT_TYPE:
             return "Lab assignment";
         case KIOSK_ASSIGNMENT_TYPE:
             return "Kiosk unit";
         case PRINTER_ASSIGNMENT_TYPE:
             return "Printer unit";
     }
 }

 function getEquipmentTypeOutputFromValue($equipmentVal)
 {
     switch ($equipmentVal)
     {
         case LAPTOP_EQUIPMENT_TYPE:
             return "Laptop";
         case DESKTOP_EQUIPMENT_TYPE:
             return "Desktop";
         case TABLET_EQUIPMENT_TYPE:
             return "Tablet";
     }
 }

 function getWarrantyOutputFromValue($warrantyVal)
 {
     switch ($warrantyVal)
     {
         case ONE_YEAR_WARRANTY_LENGTH:
             return "1 Year";
         case TWO_YEAR_WARRANTY_LENGTH:
             return "2 Years";
         case THREE_YEAR_WARRANTY_LENGTH:
             return "3 Years";
         case FOUR_YEAR_WARRANTY_LENGTH:
             return "4 Years";
         case EXPIRED_WARRANTY_LENGTH:
             return "Expired";
     }
 }



?>