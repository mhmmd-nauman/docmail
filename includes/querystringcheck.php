<?php 
$quryarr= array('');

//This stops SQL Injection in POST vars
function forarray($array) {
   foreach($array as $key=>$value) {
      if(is_array($value)) { forarray($value); }
      else { $array[$key] = mysql_real_escape_string($value); }
   }
}
forarray($_POST);

//This stops SQL Injection in GET vars
foreach ($_GET as $key => $value) {
if(in_array($key,$quryarr) && !is_numeric($value)){exit();}
$_GET[$key] = mysql_real_escape_string($value);
}
?>
