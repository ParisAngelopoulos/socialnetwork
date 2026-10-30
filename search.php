<?php
require 'functions/functions.php';
session_start();
// Check whether user is logged on or not
if (!isset($_SESSION['user_id'])) {
    header("location:index.php");
}
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
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Search results for “<?php echo h($_GET['query'] ?? ''); ?>”</h1>
        <?php
            $location = $_GET['location'];
            $key = $_GET['query'];
            if($location == 'emails') {
                $query = db_query("SELECT * FROM users WHERE users.user_email = ?", [$key]);
                include 'includes/userquery.php';
            } else if($location == 'names') {
                $name = explode(' ', $key, 2); // Break String into Array.
                if(empty($name[1])) {
                    $query = db_query("SELECT * FROM users WHERE users.user_firstname = ? OR users.user_lastname = ?", [$name[0], $name[0]]);
                } else {
                    $query = db_query("SELECT * FROM users WHERE users.user_firstname = ? AND users.user_lastname = ?", [$name[0], $name[1]]);
                }
                include 'includes/userquery.php';
            } else if($location == 'hometowns') {
                $query = db_query("SELECT * FROM users WHERE users.user_hometown = ?", [$key]);
                include 'includes/userquery.php';
            } else if($location == 'posts') {
                $sql = "SELECT posts.post_caption, posts.post_time, posts.post_public, users.user_firstname,
                                users.user_lastname, users.user_id, users.user_gender, posts.post_id
                        FROM posts
                        JOIN users
                        ON posts.post_by = users.user_id
                        WHERE (posts.post_public = 'Y' OR users.user_id = {$_SESSION['user_id']}) AND posts.post_caption LIKE ?
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
                        WHERE posts.post_public = 'N' AND posts.post_caption LIKE ?
                        ORDER BY post_time DESC";
                $like = '%' . $key . '%';
                $query = db_query($sql, [$like, $like]);
                $width = '40px'; // Profile Image Dimensions
                $height = '40px';
                if(!$query){
                    echo db_error();
                }
                if(db_count($query) == 0){
                    echo '<div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-500">';
                    echo 'There are no results for your search, try broadening your query.';
                    echo '</div>';
                }
                while($row = db_fetch($query)){
                    include 'includes/post.php';
                }
            }    
        ?>
    </main>

</body>
</html>
