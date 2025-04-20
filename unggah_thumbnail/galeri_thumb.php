<?php
include '../fungsi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $thumb_dir = "thumbs/";
    $file_name = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi gambar
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        die("File bukan gambar.");
    }

    // Validasi ekstensi
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed)) {
        die("Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.");
    }

    // Validasi ukuran
    if ($_FILES["gambar"]["size"] > (2 * 1024 * 1024)) {
        die("Ukuran file terlalu besar (maks 2MB).");
    }

    // Validasi MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["gambar"]["tmp_name"]);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
        die("Tipe MIME tidak sesuai.");
    }

    // Upload gambar asli
    if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        die("Gagal mengunggah file.");
    }

    // Buat thumbnail
    list($width, $height) = getimagesize($target_file);
    $new_width = 200;
    $new_height = floor($height * ($new_width / $width));
    $thumbpath = $thumb_dir . "thumb_" . $file_name;

    switch ($imageFileType) {
        case 'jpg':
            $src = imagecreatefromjpeg($target_file);
            break;
        case 'jpeg':
            $src = imagecreatefromjpeg($target_file);
            break;
        case 'png':
            $src = imagecreatefrompng($target_file);
            break;
        case 'gif':
            $src = imagecreatefromgif($target_file);
            break;
    }

    $thumb = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    switch ($imageFileType) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($thumb, $thumbpath, 80);
            break;
        case 'png':
            imagepng($thumb, $thumbpath);
            break;
        case 'gif':
            imagegif($thumb, $thumbpath);
            break;
    }

    imagedestroy($src);
    imagedestroy($thumb);

    // Simpan ke database
    $stmt = $koneksi->prepare("INSERT INTO gambar_thumbnail (filename, filepath, thumbpath, width, height) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $file_name, $target_file, $thumbpath, $width, $height);
    $stmt->execute();

    echo "Gambar berhasil diunggah dan thumbnail dibuat.";
}
?>

<!-- Tampilkan Gambar -->
<?php
$result = $koneksi->query("SELECT * FROM gambar_thumbnail ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "<img src='" . $row['thumbpath'] . "' width='150' style='margin:10px;'><br>";
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload Gambar + Thumbnail</title>
</head>
<body>
    <h2>Upload Gambar</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="gambar" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
