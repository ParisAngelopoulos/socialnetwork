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
    $current_id = (int)$_GET['id']; // only a number can end up in the queries below
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
    <title>Profile · Pynch</title>
    <link rel="stylesheet" href="resources/css/tailwind.css">
</head>
<body class="bg-gray-100 min-h-screen">
<?php include 'includes/navbar.php'; ?>

<main class="max-w-5xl mx-auto px-4 py-8">
    
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
                                 users.user_firstname, users.user_lastname, users.user_nickname, users.user_about
                          FROM users
                          WHERE users.user_id = $current_id";
            $profilequery = db_query($profilesql);
        } else { // Another Profile ---> Retrieve User data and friendship status
            $profilesql = "SELECT users.user_id, users.user_gender, users.user_hometown, users.user_status, users.user_birthdate,
                                    users.user_firstname, users.user_lastname, users.user_nickname, users.user_about, userfriends.friendship_status
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
            $profilequery = db_query($profilesql);
            $row = db_fetch($profilequery);
            db_rewind($profilequery);
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
        $postquery = db_query($postsql);
        ?>
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Profile card, and on your own profile: change picture and add a phone number -->
            <aside class="space-y-4">
                <?php include 'includes/profile.php'; ?>
                <?php if($flag == 0){ ?>
                <section class="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 class="font-semibold text-gray-900 mb-3">Change profile picture</h2>
                    <form action="" method="post" enctype="multipart/form-data" class="space-y-3">
                        <label class="block cursor-pointer text-center text-sm px-3 py-2 rounded-lg border border-dashed border-gray-300 text-gray-600 hover:border-blue-500 hover:text-blue-600">
                            <span id="path">Choose a picture…</span>
                            <input type="file" name="fileUpload" id="selectedFile" class="hidden" onchange="showPath()">
                        </label>
                        <input type="submit" value="Upload picture" name="profile" class="w-full py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold cursor-pointer hover:bg-blue-700">
                    </form>
                </section>
                <section class="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 class="font-semibold text-gray-900 mb-3">Add phone number</h2>
                    <form method="post" onsubmit="return validateNumber()" class="space-y-2">
                        <input type="text" name="number" id="phonenum" placeholder="0612345678" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="required text-xs text-red-600"></div>
                        <input type="submit" value="Add number" name="phone" class="w-full py-2 rounded-lg bg-gray-800 text-white text-sm font-semibold cursor-pointer hover:bg-gray-900">
                    </form>
                </section>
                <?php } ?>
            </aside>

            <!-- Posts -->
            <section class="md:col-span-2">
                <h2 class="text-lg font-semibold text-gray-900 mb-3"><?php echo $flag == 0 ? 'Your posts' : 'Posts'; ?></h2>
                <?php
                if (db_count($postquery) == 0) {
                    echo '<div class="bg-white rounded-xl border border-gray-200 p-6 text-gray-500 text-center">';
                    echo $flag == 0 ? 'You don\'t have any posts yet. <a href="home.php" class="text-blue-600 underline">Write your first one</a>.' : 'There are no posts to show yet.';
                    echo '</div>';
                }
                while ($row = db_fetch($postquery)) {
                    include 'includes/post.php';
                }
                ?>
            </section>
        </div>
</main>
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
        $query3 = db_query($sql3);
        if(!$query3){
            echo db_error();
        }
    } else if(isset($_POST['remove'])) { // Remove
        $sql3 = "DELETE FROM friendship
                 WHERE ((friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']})
                 OR (friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id))
                 AND friendship.friendship_status = 1";
        $query3 = db_query($sql3);
        if(!$query3){
            echo db_error();
        }
    } else if(isset($_POST['phone'])) { // Add a Phone Number to Your Profile
        $query3 = db_query("INSERT INTO user_phone(user_id, user_phone) VALUES (?, ?)", [$_SESSION['user_id'], $_POST['number']]);
        if(!$query3){
            echo db_error();
        } 
    }
    sleep(4);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_POST['delete_post'])) { // Als de verwijderknop is ingedrukt
        $post_id = (int)$_POST['post_id'];

        // SQL-query om de post te verwijderen
        $sql_delete_post = "DELETE FROM posts WHERE post_id = $post_id AND post_by = {$_SESSION['user_id']}";
        $query_delete_post = db_query($sql_delete_post);

        if ($query_delete_post) {
            echo "<script>alert('Post verwijderd!'); window.location.href = 'home.php';</script>";
        } else {
            echo "<script>alert('Fout bij het verwijderen van de post.');</script>";
        }
    }
}
?>
