<?php 
require 'functions/functions.php';
session_start();
// Check whether user is logged on or not
if (!isset($_SESSION['user_id'])) {
    header("location:index.php");
}
$temp = $_SESSION['user_id'];
session_destroy();
session_start();
$_SESSION['user_id'] = $temp;
ob_start(); 
// Establish Database Connection
$conn = connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="resources/css/tailwind.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    <main class="max-w-2xl mx-auto px-4 py-8">
            <section class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="font-semibold text-gray-900 mb-3">Create a post</h2>
                <form method="post" action="" onsubmit="return validatePost()" enctype="multipart/form-data">
                    <textarea rows="3" name="caption" class="w-full p-3 border rounded-lg border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="What's on your mind?"></textarea>
                    <span class="required hidden text-xs text-red-600">Write something first.</span>

                    <!-- Image preview -->
                    <img id="preview" src="" style="display:none;" class="mt-3 rounded-lg max-h-72 mx-auto" alt="" />

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <label for="imagefile" class="cursor-pointer inline-flex items-center gap-2 hover:text-blue-600">
                                <img src="images/photo.png" class="w-6 h-6" alt="" /> Photo
                            </label>
                            <input type="file" name="fileUpload" id="imagefile" class="hidden" />
                            <label for="public" class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="public" name="public"> Public (otherwise friends only)
                            </label>
                        </div>
                        <input type="submit" value="Post" name="post" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold cursor-pointer hover:bg-blue-700">
                    </div>
                </form>
            </section>
            <!-- News Feed -->
            <h1 class="text-lg font-semibold text-gray-900 mt-8 mb-3">News feed</h1>
            <div>
    <?php 
    // Public Posts Union Friends' Private Posts
    $sql = "SELECT posts.post_caption, posts.post_time, posts.post_public, users.user_firstname,
                        users.user_lastname, users.user_id, users.user_gender, posts.post_id
            FROM posts
            JOIN users
            ON posts.post_by = users.user_id
            WHERE posts.post_public = 'Y' OR users.user_id = {$_SESSION['user_id']}
            UNION
            SELECT posts.post_caption, posts.post_time, posts.post_public, users.user_firstname,
                    users.user_lastname, users.user_id, users.user_gender, posts.post_id
            FROM posts
            JOIN users
            ON posts.post_by = users.user_id
            JOIN (
                SELECT friendship.user1_id AS user_id
                FROM friendship
                WHERE friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                UNION
                SELECT friendship.user2_id AS user_id
                FROM friendship
                WHERE friendship.user1_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
            ) userfriends
            ON userfriends.user_id = posts.post_by
            WHERE posts.post_public = 'N'
            ORDER BY post_time DESC";
    $query = db_query($sql);
    if(!$query){
        echo db_error();
    }
    if(db_count($query) == 0){
        echo '<div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-500">';
        echo 'There are no posts yet to show.';
        echo '</div>';
    }
    else{
        while($row = db_fetch($query)){
            include 'includes/post.php';
        }
    }
    ?>
</div>
    </main>

    <script src="resources/js/jquery.js"></script>
    <script>
        // Invoke preview when an image file is chosen.
        $(document).ready(function(){
            $('#imagefile').change(function(){
                preview(this);
            });
        });

        // Preview function
        function preview(input){
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (event){
                    $('#preview').attr('src', event.target.result);
                    $('#preview').css('display', 'initial');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form Validation
        function validatePost(){
            var required = document.getElementsByClassName("required");
            var caption = document.getElementsByTagName("textarea")[0].value;
            required[0].style.display = "none";
            if(caption == ""){
                required[0].style.display = "initial";
                return false;
            }
            return true;
        }
    </script>
</body>
</html>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') { // Form is Posted
    // Assign Variables
    $caption = $_POST['caption'];
    if(isset($_POST['public'])) {
        $public = "Y";
    } else {
        $public = "N";
    }
    $poster = $_SESSION['user_id'];
    // Apply Insertion Query
    $query = db_query("INSERT INTO posts (post_caption, post_public, post_time, post_by) VALUES (?, ?, datetime('now', 'localtime'), ?)", [$caption, $public, $poster]);
    // Action on Successful Query
    if($query){
        // Upload Post Image If a file was chosen
        if (!empty($_FILES['fileUpload']['name'])) {
            // Retrieve Post ID
            $last_id = db_insert_id();
            include 'functions/upload.php'; // Assuming this handles the image upload
        }
        header("location: home.php");
    }
}
?>
