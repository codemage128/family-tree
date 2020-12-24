<?php

if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $id = $_POST['id'];
    $response = "";
    if ($id == "-1") {
        $location = "assets/users/" . $filename;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);
        /* Valid extensions */
        $valid_extensions = array("jpg", "jpeg", "png");
        $response = 0;
        /* Check file extension */
        if (in_array(strtolower($imageFileType), $valid_extensions)) {
            /* Upload file */
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $response = $location;
            }
        }
    } else {
        $location = "assets/users/" . $id . "/" . $filename;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);
        /* Valid extensions */
        $valid_extensions = array("jpg", "jpeg", "png");
        $response = 0;
        /* Check file extension */
        if (in_array(strtolower($imageFileType), $valid_extensions)) {
            /* Upload file */
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $response = $location;
            }
        }
    }
    /* Location */
    echo json_encode(array('url' => $response));
}

?>
