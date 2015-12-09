<?php
// print "<p>SQL: " . $sql . "<pre>"; print_r($data); print "</pre></p>";     
// print "<p>Array:<pre>"; print_r($results); print "</pre></p>";
require_once('../bin/Database.php');

$dbUserName = get_current_user().'_writer';
$whichPass = "w";
$dbName = strtoupper(get_current_user()).'_FINAL';

$thisDatabaseWriter = new Database($dbUserName,$whichPass,$dbName);

$dbUserNameR = get_current_user().'_reader';
$whichPassR = "r";
$dbNameR = strtoupper(get_current_user()).'_FINAL';

$thisDatabaseReader = new Database($dbUserNameR,$whichPassR,$dbNameR);

?>