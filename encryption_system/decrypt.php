<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["fileToDecrypt"]) && $_FILES["fileToDecrypt"]["error"] == 0) {
        $fileTmpPath = $_FILES["fileToDecrypt"]["tmp_name"];
        $decryptionKey = $_POST["decryption_key"];

        // Basahin ang encrypted file
        $encryptedContents = file_get_contents($fileTmpPath);

        // Kunin ang IV mula sa unang 16 bytes
        $iv = substr($encryptedContents, 0, 16);
        $cipherText = substr($encryptedContents, 16);

        // I-decrypt ang file gamit ang AES-256-CBC
        $decryptedContents = openssl_decrypt(
            $cipherText,
            'aes-256-cbc',
            $decryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Gumawa ng unique na pangalan para sa decrypted file
        $originalFileName = "decrypted_file_" . time(); // Halimbawa lang
        $decryptedFilePath = "uploads/" . $originalFileName;

        // Tukuyin ang file type mula sa MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($decryptedContents);
        $extension = match ($mimeType) {
            'application/pdf' => '.pdf',
            'text/plain' => '.txt',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
            default => '.bin', // Default kung hindi matukoy ang type
        };

        $decryptedFilePath .= $extension;

        // Isulat ang decrypted file
        file_put_contents($decryptedFilePath, $decryptedContents);

        echo "File decrypt succesfully! <br><a href='$decryptedFilePath'>download the Decrypted File</a>";
    } else {
        echo "Error: " . $_FILES["fileToDecrypt"]["error"];
    }
}
?>
