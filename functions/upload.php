<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['profile']) || isset($_POST['post'])){
        $filename = basename($_FILES["fileUpload"]["name"]);
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // get file extension and check its type.
        if(!in_array($filetype, ["png", "jpg", "jpeg", "gif"], true)){
            // Stop here: otherwise a file like "photo.php" would be saved and could run as code on the server
            echo 'Only JPG, JPEG, PNG & GIF formats are allowed.';
        }
        else if(exif_imagetype($_FILES["fileUpload"]["tmp_name"])){ // Check if the file is actually an image.
            if(isset($_POST['profile'])){
                $success = 0;
                $filepath = "data/images/profiles/" . $_SESSION['user_id'] . '.' . $filetype;
                if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $filepath)){
                    $query5 = db_query("INSERT INTO posts (post_caption, post_public, post_time, post_by) VALUES (?, 'N', datetime('now', 'localtime'), ?)",
                            [$row['user_firstname'] . " " . $row['user_lastname'] . " has changed his profile picture.", $_SESSION['user_id']]);
                    $last_id = db_insert_id();
                    $filepath2 = "data/images/posts/" . $last_id . '.' . $filetype;
                    copy($filepath, $filepath2);
                    if(!$query5)
                        echo db_error();
                    else
                        $success = 1;

                }
                if($success == 1)
                    header("location:profile.php?id=" . $_SESSION['user_id']);
            }
            else if(isset($_POST['post'])){
                $filepath = "data/images/posts/" . $last_id . '.' . $filetype;
                if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $filepath)){
                    header("refresh:5; url=home.php");
                }
            }
        }
    }
}
?>