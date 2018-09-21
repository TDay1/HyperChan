<?php
include './api/config.php';
$error = false;

$mysqli = new mysqli($servername, $username, $password);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$mysqli->query("CREATE SCHEMA hyperchan;") ||
    !$mysqli->query("CREATE TABLE `hyperchan`.`posts` (postid int NOT NULL AUTO_INCREMENT, post varchar(3000), dateid varchar(32), imageenabled bit, imageext varchar(4), ipaddr varchar(40), PRIMARY KEY (postid) );") ||
    !$mysqli->query("CREATE TABLE `hyperchan`.`comments` (commentid int NOT NULL AUTO_INCREMENT, postid int, comment varchar(3000), dateid varchar(32), imageenabled bit, imageext varchar(4), ipaddr varchar(40), PRIMARY KEY (commentid) );")) {
    echo "<h1>Error!</h1><br><h3></h3><p>Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error . "</p>";
    $error = true;
}
if ($error == false) {
  echo "<center><h1>Successfully setup HyperChan</h1><p>install.php will now be deleted.</p><p>Please click <a href='/'>here</a> to continue to TDayChan</p></center>";
  unlink('./install.php');
}

else {
  echo "<center><h1>Error in setting up HyperChan</h1><p>Please ensure that config.php has the correct details <a href='/'>here</a> to continue to TDayChan</p></center>";
}
?>
