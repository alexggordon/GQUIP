<?php

// file: login.php
// created by: Alex Gordon, Elliott Staude
// date: 02-19-2014
// purpose: allowing a user of the GQUIP database to access the content within by way of authenticate.php
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
// 

// include the domain authentication php function
include("authenticate.php");

// if already logged in, redirect to home
if(isset($_SESSION['user'])) {
    header('Location: home.php');
}

// check to see if user is logging out
if(isset($_GET['out'])) {
    // destroy session
    session_unset();
    $_SESSION = array();
    unset($_SESSION['user'],$_SESSION['access']);
    session_destroy();
}

// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
    // run information through authenticator
    if(authenticate_with_ad($_POST['userLogin'],$_POST['userPassword']))
    {
        // authentication passed
        header("Location: home.php");
        die();
    } else {
        // authentication failed
        $error = 1;
    }
}
// output error to user
if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";
?>

// Second File

<?php

// file: authenticate.php
// created by: Alex Gordon, Elliott Staude
// date: 02-19-2014
// purpose: this file's operation checks a user's credentials against the records contained within Active Directory
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
// 
// function: authenticate_with_ad
// @param $user: the username that the function must to verify
// @param $password: the password that the function must to verify
// purpose: this function obtains the necessary credentials from GQUIP's forms and then confirms
// - whether the user is legitimate based upon the groups the user is part of in the Active
// - Directory records

// start the php session cookie
session_start();

// Constants
	// Symbolic constants for different levels of access
    const NO_ACCESS = 0;
    const USER_ACCESS = 1;
    const FACULTY_ACCESS = 2;
    const MANAGER_ACCESS = 3;
    // Domain, for purposes of constructing $user
    const USER_DOMAIN = "@gordon.edu";


function authenticate_with_ad($user, $password) {

    // ldap => Lightweight Directory Access Protocol, a means by which a php page can get content from a server's Active Directory
	// Active Directory server => the location of the records that will be queried against
	$ldap_host = "Elder2.gordon.edu";

    // This is the organizational structure of Gordon's Active Directory Structure
	$ldap_dn = "OU=Gordon College,DC=gordon,DC=edu";

	// Active Directory user group: this group has access to edit privleges and basic create privleges, but no import or admin functions. 
	$ldap_user_group = "CTS-Helpdesk-Students";

	// Active Directory faculty group: this group has access to see computers.
	$ldap_faculty_group = "CTS-SG";

	// Active Directory administrator: this group has full administrator access
	$ldap_manager_group = "ST-ISG";

	// Connect to active directory
	$ldap = ldap_connect($ldap_host);

	// Attempt to bind to Active Directory - check that the username and password are paired in the records
	if($bind = @ldap_bind($ldap, $user . USER_DOMAIN, $password)) {

		// If this section is executing, the binding has been successful
		// Make sure that the user exists in the groups
		$filter = "(sAMAccountName=" . $user . ")";
		$attr = array("memberof");
		
		// The actual check is performed here
		$result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
		$info = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);

		// check groups that are associated with a user to see which groups
        foreach($info[0]['memberof'] as $grps) {
            
            // Check against the stored groups to see if the person logging in is a user
            if (strpos($grps, $ldap_user_group)) { $access = USER_ACCESS; break; }

            // Check against the stored groups to see if the person logging in is a faculty
            if (strpos($grps, $ldap_faculty_group)) { $access = FACULTY_ACCESS; break; }

            // Check against the stored groups to see if the person logging in is a admin
            if (strpos($grps, $ldap_manager_group)) { $access = MANAGER_ACCESS; }
        
        }
        // if the user fits into one of the groups
		if ($access != NO_ACCESS) {
			// establish session variables for access
			$_SESSION['user'] = $user;
			$_SESSION['access'] = $access;
			return true;
		} else {
			// user has no rights to access gquip
			return false;
		}
	} else {
		// invalid name or password in active directory
		return false;
	}
}
?>