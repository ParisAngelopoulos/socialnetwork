<?php
    // Number of open friend requests, shown as a badge in the menu
    $requestsRow = db_fetch(db_query("SELECT COUNT(*) AS count FROM friendship WHERE user2_id = ? AND friendship_status = 0", [$_SESSION['user_id']]));
    $requestCount = (int)($requestsRow['count'] ?? 0);
    $page = basename($_SERVER['PHP_SELF']);
    $link = function ($file, $label) use ($page) {
        $active = $page === $file ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600';
        return '<a href="' . $file . '" class="' . $active . '">' . $label . '</a>';
    };
?>
<header class="bg-white border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
        <a href="home.php" class="text-2xl font-bold text-blue-600">Pynch</a>

        <nav class="flex items-center gap-5 text-sm">
            <?php echo $link('home.php', 'Home'); ?>
            <?php echo $link('profile.php', 'Profile'); ?>
            <?php echo $link('friends.php', 'Friends'); ?>
            <a href="requests.php" class="<?php echo $page === 'requests.php' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600'; ?>">
                Requests<?php if ($requestCount > 0): ?> <span class="ml-1 inline-block min-w-5 px-1.5 rounded-full bg-red-500 text-white text-xs text-center"><?php echo $requestCount; ?></span><?php endif; ?>
            </a>
            <a href="logout.php" class="text-gray-400 hover:text-red-600">Log out</a>
        </nav>

        <form method="get" action="search.php" onsubmit="return validateField()" class="flex items-center gap-2 w-full sm:w-auto">
            <select name="location" class="px-2 py-1.5 rounded-lg border border-gray-300 text-sm bg-white">
                <option value="names">Names</option>
                <option value="posts">Posts</option>
                <option value="hometowns">Hometowns</option>
                <option value="emails">Emails</option>
            </select>
            <input type="text" name="query" id="query" placeholder="Search" class="flex-1 sm:w-44 px-3 py-1.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="submit" value="Search" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg cursor-pointer hover:bg-blue-700">
        </form>
    </div>
</header>

<script>
function validateField(){
    var query = document.getElementById("query");
    if(query.value == "") {
        query.placeholder = 'Type something!';
        return false;
    }
    return true;
}
</script>
