<?php
$conn = new mysqli("localhost", "root", "", "akademik06980");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["gambar"])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $file_name = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        exit;
    }

    if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
        echo "Ukuran file terlalu besar. Maksimal 2MB.";
        exit;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed)) {
        echo "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['gambar']['tmp_name']);
    finfo_close($finfo);

    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime, $allowed_mime)) {
        echo "Tipe MIME tidak sesuai.";
        exit;
    }

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        echo "File " . htmlspecialchars($file_name) . " berhasil diupload.<br>";

        if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
            $src = imagecreatefromjpeg($target_file);
        } elseif ($imageFileType == 'png') {
            $src = imagecreatefrompng($target_file);
        } elseif ($imageFileType == 'gif') {
            $src = imagecreatefromgif($target_file);
        } else {
            echo "Format gambar tidak didukung untuk resize.";
            exit;
        }

        $width = imagesx($src);
        $height = imagesy($src);
        $new_width = 200;
        $new_height = floor($height * ($new_width / $width));
        $tmp = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $resized_file = $target_dir . "resized_" . $file_name;
        imagejpeg($tmp, $resized_file, 80);

        $sql = "INSERT INTO gambar (nama_file, lokasi_file) VALUES ('$file_name', '$resized_file')";
        if ($conn->query($sql) === TRUE) {
            echo "Gambar disimpan ke database.<br>";
        } else {
            echo "Gagal menyimpan ke database: " . $conn->error;
        }
    } else {
        echo "Gagal mengupload file.";
    }
}

$result = $conn->query("SELECT * FROM gambar ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "<img src='" . $row['lokasi_file'] . "' width='150' style='margin:10px;'><br>";
}
?>
