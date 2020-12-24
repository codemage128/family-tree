<?php
//
$id = $_POST['id'];

//$servername = "localhost";
//$db_username = 'root';
//$db_password = '';

$databasename = "memoreebook";
$conn = new mysqli($servername, $db_username, $db_password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "select * from familytree";
$result = $conn->query($sql);
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
}
for ($i = 0; $i < count($data); $i++) {
    if (in_array(strval($id), json_decode($data[$i]['childid']))) {
        $array = [];
        array_push($array, strval($id));
        $sqldelete = "update familytree set childid='".json_encode(array_values(array_diff(json_decode($data[$i]['childid']), $array)))."' where id = ".$data[$i]['id'];
        $conn->query($sqldelete);
    }
}
$sql = "delete from familytree where firstid = " . intval($id);
$result = $conn->query($sql);
$sql = "delete from family_spouse where spid = " . intval($id) . " or userid = " . intval($id);
$result = $conn->query($sql);
$return['result'] = "success";
echo json_encode($return);
?>