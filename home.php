<!DOCTYPE html>

<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <title>Family Tree</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="./assets/custom.css" rel="stylesheet" />
</head>
<body>
<?php
//session_start();
$me = 1;
//if (count($_SESSION) > 0) {
//    $me = $_SESSION["_id"];
//}
$servername = "database-1-instance-1.cwsml3emw73t.us-east-2.rds.amazonaws.com";
$db_username = 'admin';
$db_password = 'bit7wise';

/*local database for test */
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

function grand_parent($conn, $me)
{
    $sql = "select * from familytree";
    $result = $conn->query($sql);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        $childList = [];
        $parentList = [];
        for ($i = 0; $i < count($data); $i++) {
            array_push($parentList, $data[$i]['firstid']);
            $childlid = json_decode($data[$i]['childid']);
            for ($j = 0; $j < count($childlid); $j++) {
                array_push($childList, $childlid[$j]);
            }
        }
        $grandParent = array_values(array_diff(array_unique($parentList), array_unique($childList)))[0];
    } else {
        $grandParent = $me;
    }

    return $grandParent;
}


$grandId = grand_parent($conn, $me);
$string = '<h1 style="color: black; text-align:center">Family Tree doesn`t exist!</h1>';
if ($grandId != -1) {
    $string = render_html($conn, $grandId, $me);
}

function spouse_html($conn, $id)
{
    $sql = "select * from family_spouse where userid=" . $id;
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $returnString = '';
    if ($result->num_rows > 0) {
        $_str = '<div class="card '.get_gender($conn, $id).'">' .
            '<div class="card-body">' .
            '<div class="row text-center">' .
            '<div class="col-md-12">' .
            '<img src="' . get_picture($conn, $data['spid']) . '" class="rounded-circle"/>' .
            '</div>' .
            '<div class="col-md-12 mt-3">' .
            '<a href="./edit.php?id=' . $data['spid'] . '"><h4>' . get_Name($conn, $data['spid']) . '</h4></a>' .
            '<h6>' . get_birthday($conn, $data['spid']) . '</h6>' .
            '</div>' .
            '</div>' .
            '<div class="row">' .
            '<div class="col-md-6">' .
            '<button type="button" class="btn btn-danger lg control" data-toggle="modal"' .
            'data-target="#deleteModal" data-key="' . $data['spid'] . '"><i class="fa fa-trash"></i></button>' .
            '</div>' .
            '<div class="col-md-6">' .
            '<button class="btn btn-success lg control" data-toggle="modal" data-target="#relationModal" data-key="' . $id . '"><i class="fas fa-user-plus"></i></button>' .
            '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
        $returnString .= $_str;
    }

    return $returnString;
}

function get_Name($conn, $id)
{
    $sql = "select * from family_user where id=" . $id;
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return ($data['firstName'] . "  " . $data['middleName'] . "  " . $data['lastName']);
}

function get_gender($conn, $id){
    $sql = "select * from family_user where id=" . $id;
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $return = "";
    if($data['gender'] == 1){
        $return = "men";
    } else {
        $return = "women";
    }
    return $return;
}

function get_birthday($conn, $id)
{
    $sql = "select * from family_user where id=" . $id;
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return ($data['birthday']);
}

function get_picture($conn, $id)
{
    $sql = "select * from family_user where id=" . $id;
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return ($data['profileImage']);
}

function render_html($conn, $id, $me)
{
    $uiString = "";
    $sql = "select * from familytree where firstid=" . $id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $childList = json_decode($data['childid']);
        $mestr = "";
        if ($me == $data["firstid"]) {
            $mestr = "me";
        }
        $_string = '<ul><li><div class="card '.get_gender($conn, $data["firstid"]). " " . $mestr . '">' .
            '<div class="card-body">' .
            '<div class="row text-center">' .
            '<div class="col-md-12">' .
            '<img src="' . get_picture($conn, $data["firstid"]) . '" class="rounded-circle"/>' .
            '</div>' .
            '<div class="col-md-12 mt-3">' .
            '<a href="./edit.php?id=' . $data["firstid"] . '"><h4>' . get_name($conn, $data["firstid"]) . '</h4></a>' .
            '<h6>' . get_birthday($conn, $data["firstid"]) . '</h6>' .
            '</div>' .
            '</div>' .
            '<div class="row">' .
            '<div class="col-md-6">' .
            '<button type="button" class="btn btn-danger lg control" data-toggle="modal"' .
            'data-target="#deleteModal" data-key="' . $data["firstid"] . '"><i class="fa fa-trash"></i></button>' .
            '</div>' .
            '<div class="col-md-6">' .
            '<button class="btn btn-success lg control" data-toggle="modal" data-target="#relationModal" data-key="' . $data["firstid"] . '"><i class="fas fa-user-plus"></i></button>' .
            '</div>' .
            '</div>' .
            '</div>' .
            '</div>' . spouse_html($conn, $data['firstid']);
        $_a = '<ul>';
        for ($j = 0; $j < count($childList); $j++) {
            $_str = '<li>' . render_html($conn, $childList[$j], $me) . '</li>';
            $_a = $_a . $_str;
        }
        $_a = $_a . '</ul>';
        $_string .= $_a;
        $_string = $_string . '</li></ul>';
        $uiString = $uiString . $_string;
    } else {
        $mestr = "";
        if ($me == $id) {
            $mestr = "me";
        }
        $_str = '<div class="card '.get_gender($conn, $id)." ". $mestr . '">' .
            '<div class="card-body">' .
            '<div class="row text-center">' .
            '<div class="col-md-12">' .
            '<img src="' . get_picture($conn, $id) . '" class="rounded-circle"/>' .
            '</div>' .
            '<div class="col-md-12 mt-3">' .
            '<a href="./edit.php?id=' . $id . '"><h4>' . get_Name($conn, $id) . '</h4></a>' .
            '<h6>' . get_birthday($conn, $id) . '</h6>' .
            '</div>' .
            '</div>' .
            '<div class="row">' .
            '<div class="col-md-6">' .
            '<button type="button" class="btn btn-danger lg control" data-toggle="modal"' .
            'data-target="#deleteModal" data-key="' . $id . '"><i class="fa fa-trash"></i></button>' .
            '</div>' .
            '<div class="col-md-6">' .
            '<button class="btn btn-success lg control" data-toggle="modal" data-target="#relationModal" data-key="' . $id . '"><i class="fas fa-user-plus"></i></button>' .
            '</div>' .
            '</div>' .
            '</div>' .
            '</div>' . spouse_html($conn, $id);
        $uiString .= $_str;
    }
    return $uiString;
}

?>
<div class="container text-center">
    <a href="./add.php" class="btn btn-primary btn-lg m-5 newPerson" style="border-radius: 50px"><i
                class="fa fa-user-plus"></i> New Person</a>
</div>
<div class="tree">
    <?= $string ?>
</div>

<div class="modal fade " id="relationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span> Add relationship</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row addError alert alert-danger text-center"></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" id="firstname" placeholder="First name"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" id="middlename" placeholder="Middle name"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="lastname" placeholder="Last name"/>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button class="btn btn-secondary btn-search"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mt-auto text-right">
                            <label class="control-label">Relation Type :</label>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" id="type">
                                <option value="father">Father</option>
                                <option value="daughter">Daughter</option>
                                <option value="son">Son</option>
                                <option value="spouse">Spouse</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" id="resultData">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addRelation">Add relationship
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-exclamation-triangle"></i></span> Delete
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Do you really delete this person?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete">Confirm</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="focusId"/>
<script src="./assets/custom.js" type="text/javascript"></script>
</body>
</html>