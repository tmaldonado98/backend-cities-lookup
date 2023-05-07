<?php
session_start();
session_unset();
session_destroy();

// Clear the session cookie
setcookie(session_name(), "", time() - 3600, "/");

echo "Session destroyed";

?>