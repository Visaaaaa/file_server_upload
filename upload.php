<?php

if (!empty($_FILES['attachment'])) {
    $file = $_FILES['attachment'];

    $srcFileName = $file['name'];
    $newFilePath = __DIR__ . '\upload' . $srcFileName;
    $fileSize = $file['size'];
    $limitBytes  = 1024 * 1024 * 8;
    $limitWidth  = 1280;
    $limitHeight = 768;
    $filePath = $file['tmp_name'];
    $image = getimagesize($filePath);

    $allowedExtensions = ['jpg', 'png', 'gif'];
    $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

    if ($fileSize > $limitBytes) {
        $error = 'Размер файла слишком большой';
    } elseif ($file['error'] == UPLOAD_ERR_INI_SIZE) {
        $error = 'Размер файла слишком большой';
    } elseif ($image[1] > $limitHeight || $image[0] > $limitWidth){
        $error = 'Привышенно допустимое разрешение картинки';
    } elseif (!in_array($extension, $allowedExtensions)) {
        $error = 'Загрузка файлов с таким расширением запрещена!';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Ошибка при загрузке файла.';
    } elseif (file_exists($newFilePath)) {
        $error = 'Файл с таким именем уже существует';
    } elseif (!move_uploaded_file($filePath, $newFilePath)) {
        $error = 'Ошибка при загрузке файла';
    } else {
        $result = 'http://myproject.loc/upload/upload.php' . $srcFileName;
    }
}

?>

<html>
<head>
    <title>Загрузка файла</title>
</head>
<body>
<?php if (!empty($error)): ?>
    <?= $error ?>
<?php elseif (!empty($result)): ?>
    <?= $result ?>
<?php endif; ?>
<br>
<form action="/upload/upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="attachment">
    <input type="submit">
</form>
</body>
</html>