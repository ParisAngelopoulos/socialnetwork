<?php
// People found by search.php ($query was already run with db_query() there)
if (db_count($query) == 0) {
    echo '<div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-500">';
    echo 'We couldn\'t find anyone for: ' . h($key);
    echo '</div>';
} else {
    while ($row = db_fetch($query)) {
        echo '<a href="profile.php?id=' . (int)$row['user_id'] . '" class="flex items-center gap-4 bg-white rounded-xl border border-gray-200 p-4 mb-3 hover:shadow-md transition">';
        $width = '56px';
        include 'includes/profile_picture.php';
        echo '<div><div class="font-semibold text-gray-900">' . h($row['user_firstname'] . ' ' . $row['user_lastname']) . '</div>';
        echo '<div class="text-sm text-gray-500">' . h($row['user_hometown']) . '</div></div>';
        echo '</a>';
    }
}
?>
