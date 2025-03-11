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
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CDN -->
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <?php include 'includes/navbar.php'; ?>
        <div class="py-10">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Create Post</h2>
                <hr class="mb-6">
                <form method="post" action="" onsubmit="return validatePost()" enctype="multipart/form-data">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <label for="public" class="flex items-center text-sm text-gray-600">
                                <input type="checkbox" id="public" name="public" class="mr-2">
                                <span>Public</span>
                            </label>
                        </div>
                        <span class="text-xs text-gray-400">*Caption is required</span>
                    </div>

                    <textarea rows="4" name="caption" class="w-full p-3 border rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write your caption..."></textarea>

                    <!-- Image preview -->
                    <div class="mt-4 text-center">
                        <img id="preview" src="" style="max-width: 580px; display:none;" class="mx-auto rounded-md" />
                    </div>

                    <!-- File Upload -->
                    <div class="mt-4 flex items-center justify-between">
                        <label for="imagefile" class="cursor-pointer">
                            <img src="images/photo.png" class="w-8 h-8" alt="Upload Image" />
                        </label>
                        <input type="file" name="fileUpload" id="imagefile" class="hidden" />
                        <input type="submit" value="Post" name="post" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    </div>
                </form>
            </div>
            <!-- News Feed -->
            <h1 class="text-3xl font-semibold mt-10">News Feed</h1>
            <div class="mt-8">
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
    $query = mysqli_query($conn, $sql);
    if(!$query){
        echo mysqli_error($conn);
    }
    if(mysqli_num_rows($query) == 0){
        echo '<div class="bg-white p-4 rounded-lg shadow-md text-center mb-4">';
        echo 'There are no posts yet to show.';
        echo '</div>';
    }
    else{
        while($row = mysqli_fetch_assoc($query)){
            echo '<div class="bg-white p-6 mb-6 rounded-lg shadow-md border border-gray-300">';  // Border added here
            include 'includes/post.php'; // Assuming your post rendering is done in this include
            echo '</div>';
        }
    }
    ?>
</div>

        </div>
    </div>

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
    $sql = "INSERT INTO posts (post_caption, post_public, post_time, post_by)
            VALUES ('$caption', '$public', NOW(), $poster)";
    $query = mysqli_query($conn, $sql);
    // Action on Successful Query
    if($query){
        // Upload Post Image If a file was chosen
        if (!empty($_FILES['fileUpload']['name'])) {
            // Retrieve Post ID
            $last_id = mysqli_insert_id($conn);
            include 'functions/upload.php'; // Assuming this handles the image upload
        }
        header("location: home.php");
    }
}
?>
