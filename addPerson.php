<?php
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$file = $_POST['file'];

/*local database test */
//$servername = "localhost";
//$db_username = 'root';
//$db_password = '';

$databasename = "memoreebook";
$conn = new mysqli($servername, $db_username, $db_password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$value = "('".$firstname."', '".$middlename."', '".$lastname."', '".$file."', '".$birthday."', ".$gender.")";

$sql = "insert into family_user (firstName, middleName, lastName, profileImage, birthday, gender) values ".$value;

if ($conn->query($sql) === TRUE) {
    $userId = $conn->insert_id;
    $usersql = "select * from family_user where id=".$userId;
    $result = $conn->query($usersql);
    $data = $result->fetch_assoc();
    $fileName = explode("assets/users/", $data['profileImage']);
    mkdir("assets/users/" . $userId, 0700);
    rename($data['profileImage'], $location = "assets/users/" . $userId . "/" . $fileName[1]);
    $newLocation = "assets/users/" . $userId . "/" . $fileName[1];
    $updatesql = "update family_user set profileImage='".$newLocation."' where id=".$userId;
    $conn->query($updatesql);
}
header("Location: home.php");
?>