<?php
// One post as a card: author, time, public/friends badge, text, optional photo, and delete for your own posts
echo '<article class="bg-white rounded-xl border border-gray-200 p-5 mb-4">';
echo '<div class="flex items-center gap-3">';
$width = '44px';
include __DIR__ . '/profile_picture.php';
echo '<div class="flex-1">';
echo '<a class="font-semibold text-gray-900 hover:text-blue-600" href="profile.php?id=' . (int)$row['user_id'] . '">' . h($row['user_firstname'] . ' ' . $row['user_lastname']) . '</a>';
echo '<div class="text-xs text-gray-500">' . h(date('d M Y, H:i', strtotime($row['post_time']))) . '</div>';
echo '</div>';
if ($row['post_public'] == 'Y') {
    echo '<span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Public</span>';
} else {
    echo '<span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Friends</span>';
}
echo '</div>';
echo '<p class="mt-3 text-gray-800 leading-relaxed">' . nl2br(h($row['post_caption'])) . '</p>';
$target = glob("data/images/posts/" . (int)$row['post_id'] . ".*");
if ($target) {
    echo '<img src="' . h($target[0]) . '" alt="" class="mt-3 rounded-lg max-h-96 w-full object-cover">';
}
if ($_SESSION['user_id'] == $row['user_id']) {
    echo '<form method="post" action="profile.php" class="mt-3 text-right">';
    echo '<input type="hidden" name="post_id" value="' . (int)$row['post_id'] . '">';
    echo '<button type="submit" name="delete_post" class="text-xs text-gray-400 hover:text-red-600" onclick="return confirm(\'Delete this post?\')">Delete post</button>';
    echo '</form>';
}
echo '</article>';
?>
