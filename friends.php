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
    <main class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Friends</h1>
        <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
            <?php
                $sql = "SELECT users.user_id, users.user_firstname, users.user_lastname, users.user_gender, users.user_hometown
                        FROM users
                        JOIN (
                            SELECT friendship.user1_id AS user_id
                            FROM friendship
                            WHERE friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                            UNION
                            SELECT friendship.user2_id AS user_id
                            FROM friendship
                            WHERE friendship.user1_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                        ) userfriends
                        ON userfriends.user_id = users.user_id";
                $query = db_query($sql);
                if (db_count($query) == 0) {
                    echo '<div class="col-span-full bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-500">You don\'t have any friends yet. Use the search to find people.</div>';
                }
                while ($row = db_fetch($query)) {
                    echo '<a href="profile.php?id=' . (int)$row['user_id'] . '" class="bg-white rounded-xl border border-gray-200 p-5 text-center hover:shadow-md transition">';
                    echo '<div class="flex justify-center">';
                    $width = '80px';
                    include 'includes/profile_picture.php';
                    echo '</div>';
                    echo '<div class="mt-3 font-semibold text-gray-900">' . h($row['user_firstname'] . ' ' . $row['user_lastname']) . '</div>';
                    echo '<div class="text-sm text-gray-500">' . h($row['user_hometown']) . '</div>';
                    echo '</a>';
                }
            ?>
        </div>
    </main>
</body>
</html>
