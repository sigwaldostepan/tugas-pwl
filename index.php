<?php 
session_start(); // Memulai session
require "fungsi.php"; // Pastikan file fungsi.php berisi koneksi yang aman ke database

if (isset($_SESSION['iduser'])) {
    header("location:homeadmin.php"); 
   exit; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $passw = trim($_POST['password']); // Ambil password tanpa di-hash!
    
    if (!empty($username) && !empty($passw)) {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
            
        if ($result->num_rows ==1) {
            $user = $result->fetch_assoc();

            if (password_verify($passw, $user['password'])) {
                $_SESSION['iduser'] = $user['iduser']; 
                $_SESSION['username'] = $user['username'];
                header("location:homeadmin.php");
                exit;
            } else {
                echo "Password salah.";
            }
        } else {
            echo "User tidak ditemukan.";
        }

        $stmt->close();
    }
    $koneksi->close();
} else {
    echo "This script only accepts POST requests.";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="w-25 mx-auto text-center mt-5">
            <div class="card bg-dark text-light">
                <div class="card-body">
                    <h2 class="card-title">LOGIN</h2>
                    <?php if (!empty($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <form method="post">
                        
                        <div class="form-group">
                            <label for="username">Nama user</label>
                            <input class="form-control" type="text" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <label for="passw">Password</label>
                            <input class="form-control" type="password" name="password" id="password">
                        </div>
                        <div>
                            <button class="btn btn-info" type="submit">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
