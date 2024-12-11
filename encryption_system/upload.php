<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        
        // Get file and password from form input
        $fileTmpPath = $_FILES["fileToUpload"]["tmp_name"];
        $fileName = $_FILES["fileToUpload"]["name"];
        $encryptionKey = $_POST["encryption_key"]; // Password for encryption

        // Read the file contents
        $fileContents = file_get_contents($fileTmpPath);

        // Generate a random Initialization Vector (IV) for AES encryption
        $iv = openssl_random_pseudo_bytes(16); // AES block size is 16 bytes

        // Encrypt the file contents using AES-256-CBC
        $encryptedContents = openssl_encrypt(
            $fileContents,
            'aes-256-cbc',
            $encryptionKey, // Key from form input
            OPENSSL_RAW_DATA,
            $iv
        );

        // Save the encrypted file (with IV prepended to the encrypted contents)
        $encryptedFilePath = "uploads/" . basename($fileName) . ".enc";
        $encryptedFile = fopen($encryptedFilePath, 'wb');
        fwrite($encryptedFile, $iv); // Write IV first
        fwrite($encryptedFile, $encryptedContents); // Write the encrypted file contents
        fclose($encryptedFile);

        echo "File uploaded and encrypted successfully. <br><a href='$encryptedFilePath'>Download Encrypted File</a>";
    } else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }
}
?>
