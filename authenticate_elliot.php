<?php

// file: authenticate.php
// created by: Alex Gordon, Elliott Staude
// date: 02-19-2014
// purpose: this file's operation checks a user's credentials against the records contained within Active Directory
// part of the collection of files for the GQUIP project, designed for Gordon College, 2013-2014
// 
//
// function: authenticate_with_ad
// @param $user: the username that the function must to verify
// @param $password: the password that the function must to verify
// purpose: this function obtains the necessary credentials from GQUIP's forms and then confirms
// - whether the user is legitimate based upon the groups the user is part of in the Active
// - Directory records


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

	// Active Directory server: the location of the records that will be queried against
	$ldap_host = "Elder2.gordon.edu";

	$ldap_dn = "OU=Gordon College,DC=gordon,DC=edu";

	// Active Directory user group: the name of the group one must be a part of to gain basic access to GQUIP
	$ldap_user_group = "CTS-Helpdesk-Students";

	// Active Directory faculty group: the name of the group one must be a part of to edit GQUIP data
	$ldap_faculty_group = "CTS-SG";

	// Active Directory administrator: the name of the group one must be a part of for full access to GQUIP
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
		$access = 0;

		// check groups
        foreach($info[0]['memberof'] as $grps) {
            
            // regular User
            if (strpos($grps, $ldap_user_group) !== false) { $access = max($access, USER_ACCESS); }
            if (strpos($grps, $ldap_faculty_group) !== false) { $access = max($access, FACULTY_ACCESS); }
            if (strpos($grps, $ldap_manager_group) !== false) { $access = max($access, MANAGER_ACCESS); }
        
        }
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