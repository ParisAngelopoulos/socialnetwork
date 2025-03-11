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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto">
        <?php include 'includes/navbar.php'; ?>
        <h1 class="text-3xl font-bold text-center mt-8 text-gray-800">Friend Requests</h1>
        
        <?php
        // Responding to Request
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['accept'])) {
                $sql = "UPDATE friendship
                        SET friendship.friendship_status = 1
                        WHERE friendship.user1_id = {$_GET['id']} AND friendship.user2_id = {$_SESSION['user_id']}";
                $query = mysqli_query($conn, $sql);
                if($query){
                    echo '<div class="userquery text-center text-green-600 font-semibold">';
                    echo 'You have accepted ' . $_GET['name'];
                    echo '<br><br>';
                    echo 'Redirecting in 5 seconds';
                    echo '<br><br>';
                    echo '</div>';
                    echo '<br>';
                    header("refresh:5; url=requests.php" );
                }
                else{
                    echo mysqli_error($conn);
                }
            } else if(isset($_GET['ignore'])) {
                $sql6 = "DELETE FROM friendship
                        WHERE friendship.user1_id = {$_GET['id']} AND friendship.user2_id = {$_SESSION['user_id']}";
                $query6 = mysqli_query($conn, $sql6);
                if($query6){
                    echo '<div class="userquery text-center text-red-600 font-semibold">';
                    echo 'You have Ignored ' . $_GET['name'];
                    echo '<br><br>';
                    echo 'Redirecting in 5 seconds';
                    echo '<br><br>';
                    echo '</div>';
                    echo '<br>';
                    header("refresh:5; url=requests.php" );
                }
            }
        }
        ?>

        <?php
        $sql = "SELECT users.user_gender, users.user_id, users.user_firstname, users.user_lastname
                FROM users
                JOIN friendship
                ON friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 0 AND friendship.user1_id = users.user_id";
        $query = mysqli_query($conn, $sql);
        $width = '168px';
        $height = '168px';
        if(!$query)
            echo mysqli_error($conn);
        if($query){
            if(mysqli_num_rows($query) == 0){
                echo '<div class="userquery text-center text-gray-600">';
                echo 'You have no pending friend requests.';
                echo '<br><br>';
                echo '</div>';
            }
            while($row = mysqli_fetch_assoc($query)){
                echo '<div class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md mb-4">';
                include 'includes/profile_picture.php'; // Make sure the profile picture has the appropriate Tailwind classes
                echo '<div class="ml-4">';
                echo '<a class="text-xl font-semibold text-gray-800 hover:text-blue-500" href="profile.php?id=' . $row['user_id'] .'">' . $row['user_firstname'] . ' ' . $row['user_lastname'] . '</a>';
                echo '<div class="flex mt-2 space-x-4">';
                echo '<form method="get" action="requests.php" class="flex space-x-2">';
                echo '<input type="hidden" name="id" value="' . $row['user_id'] . '">';
                echo '<input type="hidden" name="name" value="' . $row['user_firstname'] . '">';
                echo '<button type="submit" name="accept" class="bg-blue-500 text-white py-1 px-4 rounded-full hover:bg-blue-600 transition duration-300">Accept</button>';
                echo '<button type="submit" name="ignore" class="bg-gray-500 text-white py-1 px-4 rounded-full hover:bg-gray-600 transition duration-300">Ignore</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</body>
</html>
