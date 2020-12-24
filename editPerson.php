<?php
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$file = $_POST['file'];
$userId = $_POST['userId'];

$servername = "database-1-instance-1.cwsml3emw73t.us-east-2.rds.amazonaws.com";
$db_username = 'admin';
$db_password = 'bit7wise';

/*local database test */

//$servername = "localhost";
//$db_username = 'root';
//$db_password = '';

$databasename = "memoreebook";
$conn = new mysqli($servername, $db_username, $db_password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "update family_user set firstName='".$firstname."', middleName='".$middlename."', lastName='".$lastname."', birthday='".$birthday."', gender=".$gender.", profileImage='".$file."' where id=".$userId;
$conn->query($sql);
if ($conn->query($sql) === TRUE) {
    header("Location: home.php");
}
?>