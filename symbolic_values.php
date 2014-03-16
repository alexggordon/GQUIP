<?php

    // Stuff specifically for Paginate
    const PAGE_VARIABLE_MIDDLE = 7;
    const SMALL_ITEMS_PER_PAGE = 10;
    const DEFAULT_ITEMS_PER_PAGE = 25;
    const BIG_ITEMS_PER_PAGE = 50;
    const GIANT_ITEMS_PER_PAGE = 100;
    
    // GQUIP user permission levels
    const USER_PERMISSION = 1;
    const FACULTY_PERMISSION = 2;
    const ADMIN_PERMISSION = 3;
    
    // Size constants for columns
    const SMALL_VARCHAR_SIZE = 45;
    const BIG_VARCHAR_SIZE = 255;


    $columnArray['computers'] = array("computer_id",
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
    $columnArray['comments'] = array("index_id",
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "user_name",
 "computer_id",
 "body");
    $columnArray['changes'] = array("computer_id", 
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "creator",
 "body");
    $columnArray['hardware_assignments'] = array("id",
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "user_id",
 "computer",
 "department_id",
 "full_time",
 "primary_computer",
 "replace_with_recycled",
 "start_assignment",
 "end_assignment",
 "assignment_type",
 "nextneed_note");
    $columnArray['FacandStaff'] = array("ID",
 "OnCampusDepartment",
 "Dept",
 "Type",
 "FirstName",
 "LastName",
 "Email");
    $columnArray['gordonstudents'] = array("id",
 "FirstName",
 "MiddleName",
 "LastName",
 "Class",
 "Email",
 "grad_student");
    $columnArray['software'] = array("index_id",
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "name",
 "software_type");
    $columnArray['licenses'] = array("index_id",
 "last_updated_by",
 "last_updated_at",
 "created_at",
 "date_sold",
 "id",
 "seller",
 "software_id");

    $columnReadableArray['computers'] = array("Computer ID",
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
    $columnReadableArray['comments'] = array("Comment ID",
 "Last updated by",
 "Last updated at",
 "Created at",
 "Commenter",
 "ID of computer commented on",
 "Contents");
    $columnReadableArray['changes'] = array("ID of computer changed",
 "Last updated by",
 "Last updated at",
 "Created at",
 "Change creator",
 "Contents");
    $columnReadableArray['hardware_assignments'] = array("Hardware assignment ID",
 "Last updated by",
 "Last updated at",
 "Created at",
 "User assigned",
 "ID of computer assigned",
 "Department of assignment",
 "Is a full time assignment",
 "Is user's primary computer",
 "Should be replaced with a recycled unit",
 "Start of assignment",
 "End of assignment",
 "Type of assignment",
 "Notes on next computer needed");
    $columnReadableArray['FacandStaff'] = array("ID",
 "Department",
 "DPT.",
 "Type",
 "First name",
 "Last name",
 "Email");
    $columnReadableArray['gordonstudents'] = array("ID",
 "First name",
 "Middle name",
 "Last name",
 "Class",
 "Email",
 "Is a grad student");
    $columnReadableArray['software'] = array("Software ID",
 "Last updated by",
 "Last updated at",
 "Created at",
 "Software name",
 "Software type");
    $columnReadableArray['licenses'] = array("License ID",
 "Last updated by",
 "Last updated at",
 "Created at",
 "Date_sold",
 "ID of student sold to",
 "Seller to student",
 "Software ID");
 
 $tableReadableArray = array("FacandStaff" => "Faculty", "computers" => "Computers", "gordonstudents" => "Students",
 "hardware_assignments" => "Computer Assignments", "changes" => "Computer Records", "comments" => "Comments on Computers",
 "software" => "Software", "licenses" => "Software Licenses");
?>