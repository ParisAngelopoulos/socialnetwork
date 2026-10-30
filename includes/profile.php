<?php
// Profile card: picture, name, details, friendship button and phone numbers
$row = db_fetch($profilequery);
$statuses = ['S' => 'Single', 'E' => 'Engaged', 'M' => 'Married'];
$genders = ['M' => 'Male', 'F' => 'Female'];

echo '<section class="bg-white rounded-xl border border-gray-200 p-6 text-center">';
$width = '120px';
echo '<div class="flex justify-center">';
include __DIR__ . '/profile_picture.php';
echo '</div>';
echo '<h1 class="mt-4 text-xl font-bold text-gray-900">' . h($row['user_firstname'] . ' ' . $row['user_lastname']) . '</h1>';
if (!empty($row['user_nickname'])) {
    echo '<p class="text-sm text-gray-500">@' . h($row['user_nickname']) . '</p>';
}
if (!empty($row['user_about'])) {
    echo '<p class="mt-3 text-gray-700">' . h($row['user_about']) . '</p>';
}

echo '<dl class="mt-4 text-sm text-left divide-y divide-gray-100">';
$details = [
    'Hometown' => $row['user_hometown'] ?? '',
    'Birthday' => !empty($row['user_birthdate']) ? date('d M Y', strtotime($row['user_birthdate'])) : '',
    'Gender' => $genders[$row['user_gender']] ?? '',
    'Status' => $statuses[$row['user_status'] ?? ''] ?? '',
];
foreach ($details as $label => $value) {
    if ($value !== '') {
        echo '<div class="flex justify-between py-2"><dt class="text-gray-500">' . $label . '</dt><dd class="text-gray-900">' . h($value) . '</dd></div>';
    }
}
$query4 = db_query("SELECT user_phone FROM user_phone WHERE user_id = ?", [$row['user_id']]);
while ($row4 = db_fetch($query4)) {
    echo '<div class="flex justify-between py-2"><dt class="text-gray-500">Phone</dt><dd class="text-gray-900">' . h($row4['user_phone']) . '</dd></div>';
}
echo '</dl>';

// Friendship status (only on someone else's profile)
if ($flag == 1) {
    echo '<div class="mt-5">';
    if (isset($row['friendship_status']) && $row['friendship_status'] == 1) {
        echo '<span class="inline-block w-full py-2 rounded-lg bg-green-50 text-green-700 text-sm font-medium">✓ Friends</span>';
    } else if (isset($row['friendship_status']) && $row['friendship_status'] == 0) {
        echo '<span class="inline-block w-full py-2 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">Request pending</span>';
    } else {
        echo '<form method="post"><button type="submit" name="request" class="w-full py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Send friend request</button></form>';
    }
    echo '</div>';
}
echo '</section>';
?>
