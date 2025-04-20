<?php include '../fungsi.php'; ?>
<?php
// Ambil tanggal dari parameter GET (jika ada)
$tanggal = $_GET['tanggal'] ?? null;

$limit = 2;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$img_count = "SELECT COUNT(*) FROM gambar_thumbnail" . ($tanggal ? " WHERE DATE(uploaded_at) = '$tanggal'" : "");

$total_res = $koneksi->query($img_count)->fetch_row()[0];
$total_pages = ceil($total_res / $limit);

$sql = "SELECT * FROM gambar_thumbnail" . 
($tanggal ? " WHERE DATE(uploaded_at) = '$tanggal'" : "") . 
" ORDER BY uploaded_at DESC LIMIT $limit OFFSET $offset";

$img = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Galeri Gambar Responsive</title>

    <!-- Link ke Bootstrap CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Link ke Bootstrap JS Bundle -->
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-4">Galeri Gambar</h2>

    <!-- Form Filter Berdasarkan Tanggal -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal) ?>">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-success">Filter Tanggal</button>
            <a href="galeri_bootstrap2.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <?php if ($tanggal): ?>
        <p class="text-muted">Menampilkan gambar yang diunggah pada tanggal: <strong><?= htmlspecialchars($tanggal) ?></strong></p>
    <?php endif; ?>

    <div class="row g-4">
        <?php
        // Pagination setup
        $limit = 2;  // jumlah gambar per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Hitung total gambar
        $count_sql = "SELECT COUNT(*) FROM gambar_thumbnail" . ($tanggal ? " WHERE DATE(uploaded_at) = '$tanggal'" : "");
        $total_result = $koneksi->query($count_sql)->fetch_row()[0];
        $total_pages = ceil($total_result / $limit);

        // Query gambar dengan limit dan offset
        $sql = "SELECT * FROM gambar_thumbnail" . ($tanggal ? " WHERE DATE(uploaded_at) = '$tanggal'" : "") . " ORDER BY uploaded_at DESC LIMIT $limit OFFSET $offset";
        $result = $koneksi->query($sql);
        ?>

        <!-- Galeri Gambar -->
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='col-12 col-sm-6 col-md-4 col-lg-3'>
                    <div class='card shadow-sm h-100'>
                        <img src='{$row['thumbpath']}' class='card-img-top img-thumbnail' alt='Thumbnail' data-bs-toggle='modal' data-bs-target='#modal{$row['id']}'>
                        <div class='card-body'>
                            <p class='card-text'><strong>Ukuran:</strong> {$row['width']}x{$row['height']}</p>
                            <a href='{$row['filepath']}' class='btn btn-sm btn-primary' target='_blank'>Lihat Asli</a>
                            <form action='hapus.php' method='POST' onsubmit='return confirm(\"Yakin ingin menghapus gambar ini?\")'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <input type='hidden' name='filepath' value='{$row['filepath']}'>
                                <input type='hidden' name='thumbpath' value='{$row['thumbpath']}'>
                                <button type='submit' class='btn btn-sm btn-danger mt-2'>Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class='modal fade' id='modal{$row['id']}' tabindex='-1'>
                    <div class='modal-dialog modal-dialog-centered modal-lg'> 
                        <div class='modal-content'>
                            <div class='modal-body p-0'>
                                <img src='{$row['filepath']}' class='img-fluid w-100' alt='Full Image'>
                            </div>
                        </div>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p class='text-center'>Belum ada gambar diunggah.</p>";
        }
        ?>

    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-4">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>

<!-- Bootstrap JS Bundle (in case it's missing from the previous include) -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
