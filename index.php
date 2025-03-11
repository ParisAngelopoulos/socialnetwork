<?php 
require 'functions/functions.php';
session_start();
if (isset($_SESSION['user_id'])) {
    header("location:home.php");
}
session_destroy();
session_start();
ob_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
        <h1 class="text-4xl font-semibold text-center text-blue-600 mb-6">Welcome to Pynch</h1>

        <div class="tabs">
            <div class="flex justify-center space-x-4 mb-6">
                <button class="text-xl font-medium py-2 px-4 w-full text-blue-600 border-b-2 border-blue-600" id="link1" onclick="openTab(event,'signin')">Login</button>
                <button class="text-xl font-medium py-2 px-4 w-full text-gray-600 hover:text-blue-600" id="link2" onclick="openTab(event,'signup')">Sign Up</button>
            </div>
        </div>

        <div class="tabcontent" id="signin">
            <form method="post" onsubmit="return validateLogin()">
                <label for="loginuseremail" class="block text-gray-700 font-medium">Email<span class="text-red-500">*</span></label>
                <input type="text" name="useremail" id="loginuseremail" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <label for="loginuserpass" class="block text-gray-700 font-medium">Password<span class="text-red-500">*</span></label>
                <input type="password" name="userpass" id="loginuserpass" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <div class="text-red-500 mb-4 text-sm" id="login-error"></div>

                <input type="submit" value="Login" name="login" class="w-full p-3 bg-blue-600 text-white font-semibold rounded-lg shadow-sm hover:bg-blue-700">
            </form>
        </div>

        <div class="tabcontent hidden" id="signup">
            <form method="post" onsubmit="return validateRegister()">
                <!-- Highly Required Information -->
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Highly Required Information</h2>
                <hr class="border-t-2 border-gray-200 mb-4">

                <label for="userfirstname" class="block text-gray-700 font-medium">First Name<span class="text-red-500">*</span></label>
                <input type="text" name="userfirstname" id="userfirstname" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <label for="userlastname" class="block text-gray-700 font-medium">Last Name<span class="text-red-500">*</span></label>
                <input type="text" name="userlastname" id="userlastname" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <label for="usernickname" class="block text-gray-700 font-medium">Nickname</label>
                <input type="text" name="usernickname" id="usernickname" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <label for="userpass" class="block text-gray-700 font-medium">Password<span class="text-red-500">*</span></label>
                <input type="password" name="userpass" id="userpass" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <label for="userpassconfirm" class="block text-gray-700 font-medium">Confirm Password<span class="text-red-500">*</span></label>
                <input type="password" name="userpassconfirm" id="userpassconfirm" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <label for="useremail" class="block text-gray-700 font-medium">Email<span class="text-red-500">*</span></label>
                <input type="text" name="useremail" id="useremail" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <div class="flex space-x-2 mb-4">
                    <select name="selectday" class="w-1/3 p-3 border rounded-lg shadow-sm">
                        <?php for($i=1; $i<=31; $i++) { echo '<option value="' . $i . '">' . $i . '</option>'; } ?>
                    </select>
                    <select name="selectmonth" class="w-1/3 p-3 border rounded-lg shadow-sm">
                        <?php 
                        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        foreach ($months as $index => $month) { 
                            echo '<option value="' . ($index+1) . '">' . $month . '</option>';
                        } 
                        ?>
                    </select>
                    <select name="selectyear" class="w-1/3 p-3 border rounded-lg shadow-sm">
                        <?php for($i=2017; $i>=1900; $i--) { echo '<option value="' . $i . '">' . $i . '</option>'; } ?>
                    </select>
                </div>

                <div class="flex items-center mb-4">
                    <input type="radio" name="usergender" value="M" id="malegender" class="usergender">
                    <label for="malegender" class="ml-2 text-gray-700">Male</label>
                    <input type="radio" name="usergender" value="F" id="femalegender" class="usergender ml-4">
                    <label for="femalegender" class="ml-2 text-gray-700">Female</label>
                </div>

                <label for="userhometown" class="block text-gray-700 font-medium">Hometown</label>
                <input type="text" name="userhometown" id="userhometown" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-4">Additional Information</h2>
                <hr class="border-t-2 border-gray-200 mb-4">

                <div class="flex space-x-2 mb-4">
                    <input type="radio" name="userstatus" value="S" id="singlestatus" class="userstatus">
                    <label for="singlestatus" class="text-gray-700">Single</label>
                    <input type="radio" name="userstatus" value="E" id="engagedstatus" class="userstatus">
                    <label for="engagedstatus" class="text-gray-700">Engaged</label>
                    <input type="radio" name="userstatus" value="M" id="marriedstatus" class="userstatus">
                    <label for="marriedstatus" class="text-gray-700">Married</label>
                </div>

                <label for="userabout" class="block text-gray-700 font-medium">About Me</label>
                <textarea rows="4" name="userabout" id="userabout" class="w-full p-3 mb-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>

                <input type="submit" value="Create Account" name="register" class="w-full p-3 bg-blue-600 text-white font-semibold rounded-lg shadow-sm hover:bg-blue-700">
            </form>
        </div>
    </div>

    <script src="resources/js/main.js"></script>
</body>
</html>

<?php
$conn = connect();
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // A form is posted
    if (isset($_POST['login'])) { // Login process
        $useremail = $_POST['useremail'];
        $userpass = md5($_POST['userpass']);
        $query = mysqli_query($conn, "SELECT * FROM users WHERE user_email = '$useremail' AND user_password = '$userpass'");
        if($query){
            if(mysqli_num_rows($query) == 1) {
                $row = mysqli_fetch_assoc($query);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['user_firstname'] . " " . $row['user_lastname'];
                header("location:home.php");
            }
            else {
                ?> <script>
                    document.getElementById("login-error").innerHTML = "Invalid Login Credentials.";
                </script> <?php
            }
        } else{
            echo mysqli_error($conn);
        }
    }
    if (isset($_POST['register'])) { // Register process
        // Retrieve Data
        $userfirstname = $_POST['userfirstname'];
        $userlastname = $_POST['userlastname'];
        $usernickname = $_POST['usernickname'];
        $userpassword = md5($_POST['userpass']);
        $useremail = $_POST['useremail'];
        $userbirthdate = $_POST['selectyear'] . '-' . $_POST['selectmonth'] . '-' . $_POST['selectday'];
        $usergender = $_POST['usergender'];
        $userhometown = $_POST['userhometown'];
        $userabout = $_POST['userabout'];
        $userstatus = $_POST['userstatus'] ?? NULL;

        // Check for Some Unique Constraints
        $query = mysqli_query($conn, "SELECT user_nickname, user_email FROM users WHERE user_nickname = '$usernickname' OR user_email = '$useremail'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
            if($usernickname == $row['user_nickname'] && !empty($usernickname)){
                ?> <script>
                document.getElementsByClassName("required")[4].innerHTML = "This Nickname already exists.";
                </script> <?php
            }
            if($useremail == $row['user_email']){
                ?> <script>
                document.getElementsByClassName("required")[7].innerHTML = "This Email already exists.";
                </script> <?php
            }
        }
        // Insert Data
        $sql = "INSERT INTO users(user_firstname, user_lastname, user_nickname, user_password, user_email, user_gender, user_birthdate, user_status, user_about, user_hometown)
                VALUES ('$userfirstname', '$userlastname', '$usernickname', '$userpassword', '$useremail', '$usergender', '$userbirthdate', '$userstatus', '$userabout', '$userhometown')";
        $query = mysqli_query($conn, $sql);
        if($query){
            $query = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email = '$useremail'");
            $row = mysqli_fetch_assoc($query);
            $_SESSION['user_id'] = $row['user_id'];
            header("location:home.php");
        }
    }
}
?>
