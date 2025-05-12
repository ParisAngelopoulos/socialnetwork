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
<html>
<head>
    <title>Social Network</title>
    <link rel="stylesheet" type="text/css" href="resources/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <style>
    .post{
        margin-right: 50px;
        float: right;
        margin-bottom: 18px;
    }
    .profile{
        margin-left: 50px;
        background-color: white;
        box-shadow: 0 0 5px #4267b2;
        width: 220px;
        padding: 20px;
    }
    input[type="file"]{
        display: none;
    }
    label.upload{
        cursor: pointer;
        color: white;
        background-color: #4267b2;
        padding: 8px 12px;
        display: inline-block;
        max-width: 80px;
        overflow: auto;
    }
    label.upload:hover{
        background-color: #23385f;
    }
    .changeprofile{
        color: #23385f;
        font-family: Fontin SmallCaps;
    }
    </style>
</head>
<body>
    <div class="container mx-auto">
<div class="bg-white shadow-md p-4">
    <?php
        // Friend requests count query
        $sql2 = "SELECT COUNT(*) AS count FROM friendship 
                 WHERE friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 0";
        $query2 = mysqli_query($conn, $sql2);
        $row = mysqli_fetch_assoc($query2);
    ?>
    <div class="container mx-auto flex justify-between items-center">
        <!-- Navbar Links -->
        <ul class="flex space-x-8">
            <li>
                <a href="requests.php" class="text-gray-700 hover:text-blue-600">
                    Friend Requests (<?php echo $row['count'] ?>)
                </a>
            </li>
            <li>
                <a href="profile.php" class="text-gray-700 hover:text-blue-600">Profile</a>
            </li>
            <li>
                <a href="friends.php" class="text-gray-700 hover:text-blue-600">Friends</a>
            </li>
            <li>
                <a href="home.php" class="text-gray-700 hover:text-blue-600">Home</a>
            </li>
            <li>
                <a href="logout.php" class="text-gray-700 hover:text-blue-600">Log Out</a>
            </li>
        </ul>

        <!-- Search Form -->
        <div class="relative">
            <form method="get" action="search.php" onsubmit="return validateField()">
                <div class="flex items-center space-x-2">
                    <select name="location" class="px-3 py-1 rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="emails">Emails</option>
                        <option value="names">Names</option>
                        <option value="hometowns">Hometowns</option>
                        <option value="posts">Posts</option>
                    </select>
                    <input type="text" name="query" id="query" placeholder="Search" class="px-3 py-1 rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <input type="submit" value="Search" id="querybutton" class="px-4 py-1 bg-blue-600 text-white rounded-lg cursor-pointer hover:bg-blue-800">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateField(){
    var query = document.getElementById("query");
    var button = document.getElementById("querybutton");
    if(query.value == "") {
        query.placeholder = 'Type something!';
        return false;
    }
    return true;
}
</script>

<div class="container mx-auto py-10">
    
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
            if(isset($row['friendship_status'])){ // Either a friend or requested as a friend
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
        if($postquery){
            // Posts
            $width = '40px'; 
            $height = '40px';
            if(mysqli_num_rows($postquery) == 0){ // No Posts
                if($flag == 0){ // Message shown if it's your own profile
                    echo '<div class="post">';
                    echo 'You don\'t have any posts yet';
                    echo '</div>';
                } else { // Message shown if it's another profile other than you.
                    echo '<div class="post">';
                    echo 'There is no public posts to show.';
                    echo '</div>';
                }
                include 'includes/profile.php';
            } else {
                while($row = mysqli_fetch_assoc($postquery)){
                    include 'includes/post.php';
                }
                // Profile Info
                include 'includes/profile.php';
                ?>
                <br>
                <?php if($flag == 0){?>
                <div class="profile">
                    <center class="changeprofile">Change Profile Picture</center>
                    <br>
                    <form action="" method="post" enctype="multipart/form-data">
                        <center>
                            <label class="upload" onchange="showPath()">
                                <span id="path" style="color: white;">... Browse</span>
                                <input type="file" name="fileUpload" id="selectedFile">
                            </label>
                        </center>
                        <br>
                        <input type="submit" value="Upload Image" name="profile">
                    </form>
                </div>
                <br>
                <div class="profile">
                    <center class="changeprofile">Add Phone Number</center>
                    <br>
                    <form method="post" onsubmit="return validateNumber()">
                        <center>
                            <input type="text" name="number" id="phonenum">
                            <div class="required"></div>
                            <br>
                            <input type="submit" value="Submit" name="phone">
                        </center>
                    </form>
                </div>
                <br>
                <?php } ?>
                <?php
            }
        }
        ?>
    </div>
    </div>
</body>
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
</html>
<?php include 'functions/upload.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // A form is posted
    if (isset($_POST['request'])) { // Send a Friend Request
        $sql3 = "INSERT INTO friendship(user1_id, user2_id, friendship_status)
                 VALUES ({$_SESSION['user_id']}, $current_id, 0)";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['remove'])) { // Remove
        $sql3 = "DELETE FROM friendship
                 WHERE ((friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']})
                 OR (friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id))
                 AND friendship.friendship_status = 1";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['phone'])) { // Add a Phone Number to Your Profile
        $sql3 = "INSERT INTO user_phone(user_id, user_phone) VALUES ({$_SESSION['user_id']},{$_POST['number']})";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        } 
    }
    sleep(4);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_POST['delete_post'])) { // Als de verwijderknop is ingedrukt
        $post_id = $_POST['post_id'];

        // SQL-query om de post te verwijderen
        $sql_delete_post = "DELETE FROM posts WHERE post_id = $post_id AND post_by = {$_SESSION['user_id']}";
        $query_delete_post = mysqli_query($conn, $sql_delete_post);

        if ($query_delete_post) {
            echo "<script>alert('Post verwijderd!'); window.location.href = 'home.php';</script>";
        } else {
            echo "<script>alert('Fout bij het verwijderen van de post.');</script>";
        }
    }
}
?>
