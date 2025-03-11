<?php
// Set the default width and height for the profile picture
$width = 100;  // You can adjust this value based on your requirements
$height = 100; // You can adjust this value based on your requirements

// Ensure $row is properly set before trying to access it
if (isset($row['user_id']) && isset($row['user_gender'])) {
    // Get the profile picture from the "profiles" directory based on user_id
    $target = glob("data/images/profiles/" . $row['user_id'] . ".*");
    
    if($target) {
        // If a profile picture exists, display it
        echo '<img src="' . $target[0] . '" width="' . $width . '" height="' . $height . '">';
    } else {
        // If no profile picture exists, display a default based on gender
        if ($row['user_gender'] == 'M') {
            echo '<img src="data/images/profiles/M.jpg" width="' . $width . '" height="' . $height . '">';
        } else if ($row['user_gender'] == 'F') {
            echo '<img src="data/images/profiles/F.jpg" width="' . $width . '" height="' . $height . '">';
        } else {
            // In case gender is not 'M' or 'F', provide a fallback (optional)
            echo '<img src="data/images/profiles/default.jpg" width="' . $width . '" height="' . $height . '">';
        }
    }
} else {
    // Error message if $row is not set properly or missing required fields
    echo "Error: User data is missing or incomplete.";
}
?>
