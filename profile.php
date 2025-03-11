<?php 
require 'functions/functions.php';
session_start();
ob_start();
// Check whether user is logged on or not
if (!isset($_SESSION['user_id'])) {
    header("location:index.php");
}
// Establish Database Connection
$conn = connect();
?>

<?php
if(isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
    $current_id = $_GET['id'];
    $flag = 1;
} else {
    $current_id = $_SESSION['user_id'];
    $flag = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .usernav { background-color: #4267b2; }
        .usernav ul { float: right; }
        .usernav ul li { display: inline; }
        .usernav ul li a:hover { background-color: #23385f; }
        .createpostbuttons { width: 50%; margin: auto; overflow: auto; }
        .createpostbuttons img { display: inline-block; width: 12%; height: auto; }
        .createpostbuttons label { cursor: pointer; }
        .createpostbuttons input[type="file"] { display: none; }
        .createpostbuttons input[type=submit] { width: 86%; float: right; background-color: #4267b2; }
        .createpostbuttons input[type=submit]:hover { background-color: #23385f; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <?php include 'includes/navbar.php'; ?>
        
        <h1 class="text-center text-3xl font-semibold text-blue-800 mb-6">Profile</h1>
        
        <?php
        $postsql;
        if($flag == 0) { // Your Own Profile       
            $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                posts.post_id
                        FROM posts
                        JOIN users
                        ON users.user_id = posts.post_by
                        WHERE posts.post_by = $current_id
                        ORDER BY posts.post_time DESC";
            $profilesql = "SELECT users.user_id, users.user_gender, users.user_hometown, users.user_status, users.user_birthdate,
                                 users.user_firstname, users.user_lastname
                          FROM users
                          WHERE users.user_id = $current_id";
            $profilequery = mysqli_query($conn, $profilesql);
        } else { // Another Profile ---> Retrieve User data and friendship status
            $profilesql = "SELECT users.user_id, users.user_gender, users.user_hometown, users.user_status, users.user_birthdate,
                                    users.user_firstname, users.user_lastname, userfriends.friendship_status
                            FROM users
                            LEFT JOIN (
                                SELECT friendship.user1_id AS user_id, friendship.friendship_status
                                FROM friendship
                                WHERE friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']}
                                UNION
                                SELECT friendship.user2_id AS user_id, friendship.friendship_status
                                FROM friendship
                                WHERE friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id
                            ) userfriends
                            ON userfriends.user_id = users.user_id
                            WHERE users.user_id = $current_id";
            $profilequery = mysqli_query($conn, $profilesql);
            $row = mysqli_fetch_assoc($profilequery);
            mysqli_data_seek($profilequery,0);
            if(isset($row['friendship_status'])){
                if($row['friendship_status'] == 1){ // Friend
                    $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                        posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                        users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                        posts.post_id
                                FROM posts
                                JOIN users
                                ON users.user_id = posts.post_by
                                WHERE posts.post_by = $current_id
                                ORDER BY posts.post_time DESC";
                }
                else if($row['friendship_status'] == 0){ // Requested as a Friend
                    $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                        posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                        users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                        posts.post_id
                                FROM posts
                                JOIN users
                                ON users.user_id = posts.post_by
                                WHERE posts.post_by = $current_id AND posts.post_public = 'Y'
                                ORDER BY posts.post_time DESC";
                }
            } else { // Not a friend
                $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                    posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                    users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                    posts.post_id
                            FROM posts
                            JOIN users
                            ON users.user_id = posts.post_by
                            WHERE posts.post_by = $current_id AND posts.post_public = 'Y'
                            ORDER BY posts.post_time DESC";
            }
        }
        $postquery = mysqli_query($conn, $postsql);    
        ?>
        
        <div class="container mx-auto">
    <div class="flex space-x-4">
        <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_POST['request'])) { 
        $sql3 = "INSERT INTO friendship(user1_id, user2_id, friendship_status)
                 VALUES ({$_SESSION['user_id']}, $current_id, 0)";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['remove'])) { 
        $sql3 = "DELETE FROM friendship
                 WHERE ((friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']})
                 OR (friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id))
                 AND friendship.friendship_status = 1";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['phone'])) { 
        $sql3 = "INSERT INTO user_phone(user_id, user_phone) VALUES ({$_SESSION['user_id']},{$_POST['number']})";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        } 
    }
    sleep(4);
}
?>

<div class="sm:container sm:mx-auto">
    <div class="flex flex-col space-y-4">
        <!-- Left Aligned Profile Section (PHP Content) -->
    <div class="w-full md:w-1/4 bg-white shadow-lg p-4 mt-4">
        <center class="text-blue-800 font-semibold">Profile Information</center>
        <br>
        <?php
            echo '<div class="profile">';
            echo '<center>';
            $row = mysqli_fetch_assoc($profilequery);
            // Name and Nickname
            if(!empty($row['user_nickname']))
                echo $row['user_firstname'] . ' ' . $row['user_lastname'] . ' (' . $row['user_nickname'] . ')';
            else
                echo $row['user_firstname'] . ' ' . $row['user_lastname'];
            echo '<br>';
            // Profile Info & View
            $width = '168px';
            $height = '168px';
            include 'includes/profile_picture.php';
            echo '<br>';
            // Gender
            if($row['user_gender'] == "M")
                echo 'Male';
            else if($row['user_gender'] == "F")
                echo 'Female';
            echo '<br>';
            // Status
            if(!empty($row['user_status'])){
                if($row['user_status'] == "S")
                    echo 'Single';
                else if($row['user_status'] == "E")
                    echo 'Engaged';
                else if($row['user_status'] == "M")
                    echo 'Married';
                echo '<br>';
            }
            // Birthdate
            echo $row['user_birthdate'];
            // Additional Information
            if(!empty($row['user_hometown'])){
                echo '<br>';
                echo $row['user_hometown'];
            }
            if(!empty($row['user_about'])){
                echo '<br>';
                echo $row['user_about'];
            }
            // Friendship Status
            if($flag == 1){
                echo '<br>';
                if(isset($row['friendship_status'])) {
                    if($row['friendship_status'] == 1){
                        echo '<form method="post">';
                        echo '<input type="submit" value="Friends" disabled="disabled" id="special">';
                        echo '</form>';
                    } else if ($row['friendship_status'] == 0){
                        echo '<form method="post">';
                        echo '<input type="submit" value="Request Pending" disabled="disabled" id="special">';
                        echo '</form>';
                    }
                } else {
                    echo '<form method="post">';
                    echo '<input type="submit" value="Send Friend Request" name="request">';
                    echo'</form>';
                }
            }

            echo '<center>';
            echo'</div>';

            $query4 = mysqli_query($conn, "SELECT * FROM user_phone WHERE user_id = {$row['user_id']}");
            if(!$query4){
                echo mysqli_error($conn);
            }
            if(mysqli_num_rows($query4) > 0){
                echo '<br>';
                echo '<div class="profile">';
                echo '<center class="changeprofile">';
                echo 'Phones:';
                echo '<br>';
                while($row4 = mysqli_fetch_assoc($query4)){
                    echo $row4['user_phone'];
                    echo '<br>';
                }
                echo '</center>';
                echo '</div>';
            }
        ?>
    </div>
        <!-- Left Column: Profile Section -->
        <div class="w-full md:w-1/4 bg-white shadow-lg p-4">
            <center class="text-blue-800 font-semibold">Add Phone Number</center>
            <br>
            <form method="post" onsubmit="return validateNumber()">
                <center>
                    <input type="text" name="number" id="phonenum" class="p-2 border border-gray-400 w-full mb-2 rounded">
                    <div class="required text-red-500"></div>
                    <br>
                    <input type="submit" value="Submit" name="phone" class="w-full bg-blue-600 text-white py-2 rounded mt-4 hover:bg-blue-800">
                </center>
            </form>
        </div>

        <div class="w-full md:w-1/4 bg-white shadow-lg p-4">
            <center class="text-blue-800 font-semibold">Change Profile Picture</center>
            <br>
            <form action="" method="post" enctype="multipart/form-data">
                <center>
                    <label class="cursor-pointer bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-800" onchange="showPath()">
                        <span id="path" class="text-white">... Browse</span>
                        <input type="file" name="fileUpload" id="selectedFile" class="hidden">
                    </label>
                </center>
                <br>
                <input type="submit" value="Upload Image" name="profile" class="w-full bg-blue-600 text-white py-2 mt-4 rounded hover:bg-blue-800">
            </form>
        </div>
    </div>
</div>

<script>
    function showPath(){
        var path = document.getElementById("selectedFile").value;
        path = path.replace(/^.*\\/, "");
        document.getElementById("path").innerHTML = path;
    }

    function validateNumber(){
        var number = document.getElementById("phonenum").value;
        var required = document.getElementsByClassName("required");
        if(number == ""){
            required[0].innerHTML = "You must type Your Number.";
            return false;
        } else if(isNaN(number)){
            required[0].innerHTML = "Phone Number must contain digits only."
            return false;
        }
        return true;
    }
</script>



        <!-- Right Column: Posts Section -->
        <div class="w-3/4">
            <?php
            if($postquery) {
                // Posts
                $width = '40px'; 
                $height = '40px';
                if(mysqli_num_rows($postquery) == 0) {
                    if($flag == 0) {
                        echo '<div class="post p-4 bg-white shadow-md mb-4">';
                        echo 'You don\'t have any posts yet';
                        echo '</div>';
                    } else {
                        echo '<div class="post p-4 bg-white shadow-md mb-4">';
                        echo 'There are no public posts to show.';
                        echo '</div>';
                    }  
                } else {
                    while($row = mysqli_fetch_assoc($postquery)) {
                        echo '<div class="bg-white p-6 mb-6 rounded-lg shadow-md border border-gray-300">';  // Border added here
            include 'includes/post.php'; // Assuming your post rendering is done in this include
            echo '</div>';
                    }
                }
            }
            ?>
            <br>
        </div>
    </div>
</div>

<script>
    function showPath(){
        var path = document.getElementById("selectedFile").value;
        path = path.replace(/^.*\\/, "");
        document.getElementById("path").innerHTML = path;
    }

    function validateNumber(){
        var number = document.getElementById("phonenum").value;
        var required = document.getElementsByClassName("required");
        if(number == ""){
            required[0].innerHTML = "You must type Your Number.";
            return false;
        } else if(isNaN(number)){
            required[0].innerHTML = "Phone Number must contain digits only."
            return false;
        }
        return true;
    }
</script>

</body>
</html>

<?php include 'functions/upload.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_POST['request'])) { 
        $sql3 = "INSERT INTO friendship(user1_id, user2_id, friendship_status)
                 VALUES ({$_SESSION['user_id']}, $current_id, 0)";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['remove'])) { 
        $sql3 = "DELETE FROM friendship
                 WHERE ((friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']})
                 OR (friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id))
                 AND friendship.friendship_status = 1";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['phone'])) { 
        $sql3 = "INSERT INTO user_phone(user_id, user_phone) VALUES ({$_SESSION['user_id']},{$_POST['number']})";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        } 
    }
    sleep(4);
}
?>
