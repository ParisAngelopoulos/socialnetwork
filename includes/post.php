<?php

echo '<div class="post">';
if($row['post_public'] == 'Y') {
    echo '<p class="public">Public</p>';
}else {
    echo '<p class="public">Private</p>';
}
echo '<br>';
echo '<span class="postedtime">' . $row['post_time'] . '</span>';
echo '</p>';
echo '<div>';
include 'profile_picture.php';
echo '<a class="profilelink" href="profile.php?id=' . $row['user_id'] .'">' . $row['user_firstname'] . ' ' . $row['user_lastname'] . '</a>';
echo '</div>';
echo '<br>';
echo '<p class="caption">' . $row['post_caption'] . '</p>';
echo '<center>'; 
$target = glob("data/images/posts/" . $row['post_id'] . ".*");
if($target) {
    echo '<img src="' . $target[0] . '" style="max-width:580px">'; 
    echo '<br><br>';
}
echo '</center>';

// Verwijderknop toevoegen als de gebruiker de eigenaar is van de post
if ($_SESSION['user_id'] == $row['user_id']) { 
    echo '<form method="post" style="text-align:center;">';
    echo '<input type="hidden" name="post_id" value="' . $row['post_id'] . '">';
    echo '<button type="submit" name="delete_post" class="delete-btn">Verwijder Post</button>';
    echo '</form>';
}

echo '</div>';

?>
