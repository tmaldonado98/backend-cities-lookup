<?php
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
  header("HTTP/1.1 401 Unauthorized");
  exit();
}

?>