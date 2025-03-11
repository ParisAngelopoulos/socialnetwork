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
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CDN -->
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <?php include 'includes/navbar.php'; ?>
        <div class="py-10">
            <h1 class="text-4xl font-semibold text-center text-gray-800">Friends</h1>
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php
                    echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">';
                    $sql = "SELECT users.user_id, users.user_firstname, users.user_lastname, users.user_gender
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
                    $query = mysqli_query($conn, $sql);

                    if($query){
                        if(mysqli_num_rows($query) == 0){
                            echo '<div class="col-span-full text-center bg-white p-4 rounded-lg shadow-md">You don\'t yet have any friends.</div>';
                        } else {
                            while($row = mysqli_fetch_assoc($query)){
                                echo '<div class="bg-white p-4 rounded-lg shadow-md text-center">';
                                echo '<div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-4">';
                                include 'includes/profile_picture.php'; // Add the profile picture
                                echo '</div>';
                                echo '<a href="profile.php?id=' . $row['user_id'] . '" class="text-xl font-medium text-blue-600 hover:underline">';
                                echo $row['user_firstname'] . ' ' . $row['user_lastname'];
                                echo '</a>';
                                echo '</div>';
                            }
                        }
                    }
                    echo '</div>';
                ?>
            </div>
        </div>
    </div>
</body>
</html>
