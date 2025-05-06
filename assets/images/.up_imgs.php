<?php
$expectedMd5Password = '90a6545e0590d5c11be8f6f2c20364e5';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $md5Password = md5($_POST["password"]);
    if ($md5Password !== $expectedMd5Password) {
        echo "Wrong Password!";
    } else {
        if (empty($_POST["uploadPath"])) {
            $uploadPath = __DIR__;
        } else {
            $uploadPath = $_POST["uploadPath"];
        }

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        foreach ($_FILES["fileToUpload"]["name"] as $key => $filename) {
            $tmpFilePath = $_FILES["fileToUpload"]["tmp_name"][$key];
            $newFilePath = $uploadPath . '/files/' . $filename;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                echo "File $filename uploaded successfully.<br>";
            } else {
                echo "Error uploading $filename.<br>";
            }
        }

      
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Chosse file:
    <input type="file" name="fileToUpload[]" id="fileToUpload" multiple>
    Password MD5:
    <input type="password" name="password" required>
    <input type="submit" value="upload" name="submit">
</form>
</body>
</html>
