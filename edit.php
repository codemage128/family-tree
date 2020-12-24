<!DOCTYPE html>

<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <title>Edit Person</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>
<body>
<?php
$id = $_GET['id'];

//$servername = "localhost";
//$db_username = 'root';
//$db_password = '';

$databasename = "memoreebook";
$conn = new mysqli($servername, $db_username, $db_password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$usersql = "select * from family_user where id=" . $id;
$result = $conn->query($usersql);
$data = $result->fetch_assoc();
?>

<div class="container mt-3">
    <div class="row">
        <div class="offset-4 col-md-7">
            <button class="btn btn-info btn-back"><i class="fas fa-long-arrow-alt-left"></i> Back</button>
        </div>
        <div class="offset-2 col-md-8 mt-5 ">
            <form action="./editPerson.php" method="post">
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">First Name :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" value="<?= $data['firstName'] ?>"
                                   placeholder="Enter First Name" name="firstname">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Middle Name :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" value="<?= $data['middleName'] ?>"
                                   placeholder="Enter Middle Name" name="middlename">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Last Name :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" value="<?= $data['lastName'] ?>"
                                   placeholder="Enter LastName" name="lastname">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Birthday :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="date" class="form-control" value="<?= $data['birthday'] ?>"
                                   placeholder="Enter Birthday" name="birthday">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Gender :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <select name="gender" class="form-control">
                                <option value="1" <?php if($data['gender'] == 1) echo "selected" ?> > Male</option>
                                <option value="2" <?php if($data['gender'] == 2) echo "selected" ?> >Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">ProfileImage :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" class="form-control" id="file">
                            <input type="hidden" class="form-control" name="file" id="filepath"
                                   value="<?= $data['profileImage'] ?>"/>
                            <input type="hidden" class="form-control" name="userId" id="userId"
                                   value="<?= $data['id'] ?>"/>
                            <img src="<?= $data['profileImage'] ?>" class="mt-2" width="100%" id="preview"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-info btn-block">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.btn-back').click(function () {
            window.history.back();
        })
        $('#file').change(function () {
            var file = $(this)[0].files[0];
            var formData = new FormData();
            formData.append('file', file);
            formData.append('id', $('#userId').val());
            $.ajax({
                type: 'POST',
                url: './uploadimage.php',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#preview").attr('src', data.url);
                    $('#filepath').val(data.url);
                }
            })
        })
    })
</script>
</body>
</html>