<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE namauser='$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data && $password === $data['password']) {
        $_SESSION['iduser'] = $data['iduser'];
        $_SESSION['nama'] = $data['namauser'];
        $_SESSION['role'] = $data['role'];

        if (in_array($data['role'], ['admin', 'waiter', 'kasir', 'owner'])) {
            header("Location: dashboard_admin.php");
            exit;
        } else {
            echo "<script>alert('Role tidak diizinkan!'); window.location.href='login.php';</script>";
            exit;
        }
    }

    $pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan WHERE namapelanggan='$username'");
    $data_pelanggan = mysqli_fetch_assoc($pelanggan);

    if ($data_pelanggan && $password === $data_pelanggan['password']) {
        echo "<script>alert('Login gagal: Pelanggan tidak diizinkan mengakses dashboard ini'); window.location.href='login.php';</script>";
        exit;
    }

    echo "<script>alert('Username atau Password salah!'); window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Pegawai</title>
    <style>
        body {
            background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230929/pngtree-3d-rendering-of-restaurant-signboard-in-mockup-image_13526069.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.92);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            width: 360px;
            backdrop-filter: blur(8px);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 15px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .info {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Nama User" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
