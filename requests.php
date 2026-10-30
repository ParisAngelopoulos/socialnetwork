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
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Friend requests</h1>
        
        <?php
        // Responding to Request
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['accept'])) {
                $sql = "UPDATE friendship
                        SET friendship_status = 1
                        WHERE friendship.user1_id = ? AND friendship.user2_id = ?";
                $query = db_query($sql, [$_GET['id'], $_SESSION['user_id']]);
                if($query){
                    echo '<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">';
                    echo 'You have accepted ' . h($_GET['name']);
                    echo '</div>';
                }
                else{
                    echo db_error();
                }
            } else if(isset($_GET['ignore'])) {
                $sql6 = "DELETE FROM friendship
                        WHERE friendship.user1_id = ? AND friendship.user2_id = ?";
                $query6 = db_query($sql6, [$_GET['id'], $_SESSION['user_id']]);
                if($query6){
                    echo '<div class="mb-4 rounded-lg bg-gray-50 border border-gray-200 text-gray-700 px-4 py-3">';
                    echo 'You have Ignored ' . h($_GET['name']);
                    echo '</div>';
                }
            }
        }
        ?>

        <?php
        $sql = "SELECT users.user_gender, users.user_id, users.user_firstname, users.user_lastname
                FROM users
                JOIN friendship
                ON friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 0 AND friendship.user1_id = users.user_id";
        $query = db_query($sql);
        $width = '168px';
        $height = '168px';
        if(!$query)
            echo db_error();
        if($query){
            if(db_count($query) == 0){
                echo '<div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-500">You have no open friend requests.</div>';
            }
            while($row = db_fetch($query)){
                echo '<div class="flex items-center gap-4 bg-white rounded-xl border border-gray-200 p-4 mb-3">';
                $width = '56px';
                include 'includes/profile_picture.php';
                echo '<a class="flex-1 font-semibold text-gray-900 hover:text-blue-600" href="profile.php?id=' . (int)$row['user_id'] .'">' . h($row['user_firstname'] . ' ' . $row['user_lastname']) . '</a>';
                echo '<form method="get" action="requests.php" class="flex gap-2">';
                echo '<input type="hidden" name="id" value="' . (int)$row['user_id'] . '">';
                echo '<input type="hidden" name="name" value="' . h($row['user_firstname']) . '">';
                echo '<button type="submit" name="accept" class="bg-blue-600 text-white text-sm font-semibold py-1.5 px-4 rounded-lg hover:bg-blue-700">Accept</button>';
                echo '<button type="submit" name="ignore" class="bg-gray-100 text-gray-700 text-sm font-semibold py-1.5 px-4 rounded-lg hover:bg-gray-200">Ignore</button>';
                echo '</form>';
                echo '</div>';
            }
        }
        ?>
    </main>
</body>
</html>
