<?php
// register.php

include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namauser = $_POST['namauser'];
    $role = $_POST['role'];
    $password = $_POST['password']; // tanpa hash

    $sql = "INSERT INTO user (namauser, role, password) VALUES ('$namauser', '$role', '$password')";

    if (mysqli_query($conn, $sql)) {
        $message = "Registrasi user berhasil!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230929/pngtree-3d-rendering-of-restaurant-signboard-in-mockup-image_13526069.png');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register User</h2>
        <form method="post" action="">
            <label>Nama User:</label>
            <input type="text" name="namauser" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="waiter">Waiter</option>
                <option value="kasir">Kasir</option>
                <option value="owner">Owner</option>
            </select>

            <label>Password:</label>
            <input type="text" name="password" required>

            <input type="submit" value="Register User">
        </form>

        <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>
    </div>
</body>
</html>
