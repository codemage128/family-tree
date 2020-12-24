<!DOCTYPE html>

<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <title>Add Person</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>
<body>
<div class="container mt-3">
    <div class="row">
        <div class="offset-4 col-md-7">
            <button class="btn btn-primary btn-back"><i class="fas fa-long-arrow-alt-left"></i> Back</button>
        </div>
        <div class="offset-2 col-md-8 mt-5 ">
            <form action="./addPerson.php" method="post">
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">First Name :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Enter First Name" name="firstname" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Middle Name :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Enter Middle Name"  name="middlename">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Last Name :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Enter Last Name"  name="lastname" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Gender</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <select class="form-control" name="gender">
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">Birthday :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="date" class="form-control" placeholder="Enter Birthday" name="birthday" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <label for="email">ProfileImage :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="file" class="form-control" id="file" required/>
                            <input type="hidden" class="form-control" name="file" id="filepath" />
                            <img src="./assets/default.png" class="mt-2" width="100%" id="preview" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-block">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.btn-back').click(function(){
            window.history.back();
        })
        $('#file').change(function () {
            var file = $(this)[0].files[0];
            var formData = new FormData();
            formData.append('file', file);
            formData.append('id', "-1");
            $.ajax({
                type: 'POST',
                url: './uploadimage.php',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#preview").attr('src', data.url);
                    $("#filepath").val(data.url);
                }
            })
        })
    })
</script>
</body>
</html>