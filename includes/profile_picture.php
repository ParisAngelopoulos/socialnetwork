<?php
// Round profile picture: the uploaded one, or a default one by gender. $width sets the size (e.g. '40px').
$size = isset($width) ? (int)$width : 40;
if (isset($row['user_id'])) {
    $target = glob("data/images/profiles/" . (int)$row['user_id'] . ".*");
    if ($target) {
        $src = $target[0];
    } else if (($row['user_gender'] ?? '') == 'F') {
        $src = 'data/images/profiles/F.jpg';
    } else {
        $src = 'data/images/profiles/M.jpg';
    }
    echo '<img src="' . h($src) . '" alt="" class="rounded-full object-cover bg-gray-200 shrink-0" style="width:' . $size . 'px;height:' . $size . 'px">';
}
?>
