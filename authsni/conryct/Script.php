<?php

require('config.php');
if(isset($_GET['inst_type'])){
$type = $_GET['inst_type'];


$db = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Could not connect: ' . mysql_error());


mysql_select_db(DB_NAME);

$sql = "SELECT * FROM inst WHERE inst_type = '$type'";
$result = mysql_query($sql)  or die('result failed Script ' . mysql_error());
$return = array();
while($row = mysql_fetch_assoc($result)){
$return[] = $row;
}
echo json_encode($return);
}

?>


