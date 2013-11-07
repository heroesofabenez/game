<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
function db_connect($config) {
  if(!is_array($config)) { return false; }
  extract($config);
  switch ($driver) {
  case "mysqli":
    $conn = new mysqli($host, $username, $password, $database);
    if(mysqli_connect_error()) {
      die("Connect Error (" . mysqli_connect_errno() . ") " .mysqli_connect_error() .")");
    }
    break;
  case "mysql":
    $conn = mysql_connect($host, $username, $password);
    if(!$conn) {
      die('Could not connect: ' . mysql_error());
    }
    $db_selected = mysql_select_db($database, $conn);
    if(!$db_selected) {
      die ('Can\'t use $database : ' . mysql_error());
    }
    break;
  default:
  	
    break;
  }
  return $conn;
}
?>