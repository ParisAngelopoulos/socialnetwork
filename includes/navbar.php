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
