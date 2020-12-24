<?php
//
$id = $_POST['id'];
$type = $_POST['type'];
$relationList = $_POST['relationList'];


//$servername = "localhost";
//$db_username = 'root';
//$db_password = '';

$databasename = "memoreebook";
$conn = new mysqli($servername, $db_username, $db_password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$return['result'] = "success";

$sql = "select * from familytree where firstid=" . $id;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $childList = $data['childid'];
    $value = "";
    if ($type == 'spouse') {
        if (count($relationList) > 1) {
            $return['result'] = "error";
            $return['message'] = "Spouse Can't be two or more person!";
        } else {
            $query = "select * from family_spouse where userid = " . $id;
            $conn->query($query);
            $query_result = $conn->query($query);
            if ($query_result->num_rows > 0) {
                $return['result'] = "error";
                $return['message'] = "You have already one spouse!";
            } else {
                $query = "insert into family_spouse (userid, spid) values ( " . $id . ", " . $relationList[0] . ")";
                $conn->query($query);
            }
        }
    } else if ($type == "father") {
        if (count($relationList) > 1) {
            $return['result'] = "error";
            $return['message'] = "You can have only one Parent!";
        } else {
            $query = "select * from familytree";
            $conn->query($query);
            $query_result = $conn->query($query);
            $data_result = [];
            $childs = [];
            $blockUsers = [];
            array_push($childs, $id);
            if ($query_result->num_rows > 0) {
                while ($row = $query_result->fetch_assoc()) {
                    array_push($data_result, $row);
                }
                for ($i = 0; $i < count($data_result); $i++) {
                    $childlid = json_decode($data_result[$i]['childid']);
                    for ($j = 0; $j < count($childlid); $j++) {
                        array_push($blockUsers, $childlid[$j]);
                    }
                }
                if(in_array($id, $blockUsers)){
                    $return['result'] = 'error';
                    $return['message'] = "You have already parent!";
                }else if(in_array($relationList[0], $blockUsers)){
                    $return['result'] = 'error';
                    $return['message'] = "Can't add this person as a parent";
                } else {
                    $addsql = "insert into familytree (firstid, childid) values (".$relationList[0]. ", '".json_encode($childs)."')";
                    $conn->query($addsql);
                }
            }
        }
    } else {
        $value = array_unique(array_merge($relationList, json_decode($childList)), SORT_REGULAR);
        $sql = "update familytree set childid='" . json_encode($value) . "' where id=" . $data['id'];
        $conn->query($sql);
    }

} else {
    $value = "";
    if ($type == 'spouse') {
        if (count($relationList) > 1) {
            $return['result'] = "error";
            $return['message'] = "Spouse Can't be two or more person!";
        } else {
            $query = "select * from family_spouse where userid = " . $id;
            $conn->query($query);
            $query_result = $conn->query($query);
            if ($query_result->num_rows > 0) {
                $return['result'] = "error";
                $return['message'] = "You have already one spouse!";
            } else {
                $query = "insert into family_spouse (userid, spid) values ( " . $id . ", " . $relationList[0] . ")";
                $conn->query($query);
            }
        }
    } else if ($type == "father") {
        $return['result'] = 'error';
        $return['message'] = "You have to have only one Parent!";
    } else {
        $value = "(" . $id . ", '" . json_encode($relationList) . "')";
        $sql = "insert into familytree (firstid, childid) values " . $value;
        $conn->query($sql);
    }
}
echo json_encode($return);
?>