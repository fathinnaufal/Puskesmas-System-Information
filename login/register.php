<?php
require_once '../dbkoneksi.php';

// Mulai session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak sesuai!'); window.location='register.php';</script>";
        exit();  // Hentikan eksekusi lebih lanjut
    } else {
        $query = "SELECT id FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Username sudah terdaftar!'); window.location='login.php';</script>";
            exit();  // Hentikan eksekusi lebih lanjut jika username sudah ada
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $insertQuery)) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
                exit();  // Sukses dan alihkan ke halaman login
            } else {
                // Menampilkan pesan error untuk debugging, di produksi sebaiknya disimpan di log
                $error = "Error pada database: " . mysqli_error($conn);
                echo "<script>alert('" . addslashes($error) . "'); window.location='register.php';</script>";
                exit();  // Hentikan eksekusi jika query gagal
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="logo">
            <img src="logo.png" alt="Logo" />
        </div>
        <form action="register.php" method="post">
            <h1>Register</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required />
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required />
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
                <i class="bx bxs-lock-alt"></i>
            </div>
            <button type="submit" class="btn">Register</button>
            <div class="register-link">
                <p>Already have an account? <a href="login.html">Login</a></p>
            </div>
        </form>
    </div>
</body>

</html>