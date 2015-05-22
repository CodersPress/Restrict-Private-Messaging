<?php
require_once '../../../wp-config.php';
$con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
if(!$con) die("couldnt connect".mysql_error());
mysql_select_db(DB_NAME)
or die("unable to select database".mysql_error());

global $wpdb;

$sql = "SELECT user_id
FROM $wpdb->usermeta
WHERE meta_key = 'nickname'
AND meta_value = '" . $_POST['usernamefield'] . "' ";

$userID = mysql_query($sql) or die (mysql_error());
$MemberID = mysql_fetch_array($userID);

$sql = "SELECT meta_value 
FROM $wpdb->usermeta 
WHERE meta_key = 'wlt_membership'
AND user_id = ' " . $MemberID[0] . " ' ";

$meta_value = mysql_query($sql) or die (mysql_error());
$MembershipID = mysql_fetch_array($meta_value);

echo $MembershipID[0];
?>