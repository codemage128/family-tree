<?php

$firstname = $_POST['firstName'];
$middlename = $_POST['middleName'];
$lastname = $_POST['lastName'];
//$birthday = $_POST['birthday'];


//$servername = "localhost";
//$db_username = 'root';
//$db_password = '';

$databasename = "memoreebook";
$conn = new mysqli($servername, $db_username, $db_password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "select * from family_user where firstName like'%" . $firstname . "%' and lastName like '%" . $lastname . "%' and middleName like '%" . $middlename . "%'";
$conn->query($sql);
$data = [];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
}
echo json_encode($data);

