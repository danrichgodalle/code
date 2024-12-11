<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $fileTmpPath = $_FILES["fileToUpload"]["tmp_name"];
        $fileName = $_FILES["fileToUpload"]["name"];
        $encryptionKey = $_POST["encryption_key"];

        // Basahin ang laman ng file (binary-safe)
        $fileContents = file_get_contents($fileTmpPath);

        // Gumawa ng Initialization Vector (IV)
        $iv = openssl_random_pseudo_bytes(16);

        // I-encrypt ang file gamit ang AES-256-CBC
        $encryptedContents = openssl_encrypt(
            $fileContents,
            'aes-256-cbc',
            $encryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Isulat ang IV at encrypted na laman sa output file
        $encryptedFilePath = "uploads/" . basename($fileName) . ".enc";
        $encryptedFile = fopen($encryptedFilePath, 'wb');
        fwrite($encryptedFile, $iv); // Isulat ang IV muna
        fwrite($encryptedFile, $encryptedContents); // Isulat ang encrypted na laman
        fclose($encryptedFile);

        echo "File encrypted succesfully! <br><a href='$encryptedFilePath'>download the Encrypted File</a>";
    } else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }
}
?>
