<?php
if (isset($_POST['file'])) {
    $file = $_POST['file'];
    $source = 'uploads/' . $file;
    $destination = 'deleted/' . $file;

    // Check if the file exists in the uploads folder
    if (file_exists($source)) {
        // Move the file to the deleted folder
        if (rename($source, $destination)) {
            header("Location: index.php#deleted-section"); // Redirect to the deleted section
        } else {
            echo "Error deleting the file.";
        }
    } else {
        echo "File does not exist.";
    }
}
?>
