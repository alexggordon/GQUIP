<?php
session_start();

// this will check for inactivity period. The second number is in seconds
define('SESSION_EXPIRE',21600);
 
// check passage of time, force log-out session expire time after time set above
if(isset($_SESSION['last_activity']) && (time() - strtotime($_SESSION['last_activity']) > SESSION_EXPIRE)) {
    // destroy session
    session_unset();
    session_destroy();
}
 
// every time the user performs an action, we need to reset the expiration time
// so if a user is logged in and unexpired, update activity
if(isset($_SESSION['user'])) {
    // user has been logged in for this long
    $_SESSION['last_activity'] = date('Y-m-d H:i:s');
}
?>