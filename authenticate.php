<?php session_start();

function authenticate_with_ad($user, $password) {
	// Active Directory server
	$ldap_host = "Elder2.gordon.edu";

	$ldap_dn = "OU=Students,OU=Gordon College,DC=gordon,DC=edu";

	// Active Directory user group
	$ldap_user_group = "CTS-Helpdesk-Students";

	// Active Directory faculty group
	$ldap_faculty_group = "CTS-SG";

	// Active Directory administrator 
	$ldap_manager_group = "ST-ISG";

	// Domain, for purposes of constructing $user
	$ldap_usr_dom = "@gordon.edu";

	// connect to active directory
	$ldap = ldap_connect($ldap_host);

	// verify user and password
	if($bind = @ldap_bind($ldap, $user . $ldap_usr_dom, $password)) {

		// valid credentials
		// check presence in groups
		$filter = "(sAMAccountName=" . $user . ")";
		$attr = array("memberof");
		$result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
		$info = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);

		// check groups
        foreach($info[0]['memberof'] as $grps) {
            // extract Group name from string
            $temp = substr($grps, 0, stripos($grps, ","));

            // strip the CN= and change to lowercase for easy handling
            $temp = strtolower(str_replace("CN=", "", $temp));

            // create a group array to check for access
            $groups[] .= $temp;
            
            // regular User
            if (strpos($grps, $ldap_user_group)) { $access = 1; break; }
            if (strpos($grps, $ldap_faculty_group)) { $access = 2; break; }
            if (strpos($grps, $ldap_manager_group)) { $access = 3; }
        
        }
		if ($access != 0) {
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