<?php
// registerpelanggan.php

include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namapelanggan = $_POST['namapelanggan'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO pelanggan (namapelanggan, jeniskelamin, nohp, alamat) 
            VALUES ('$namapelanggan', '$jeniskelamin', '$nohp', '$alamat')";

    if (mysqli_query($conn, $sql)) {
        $message = "Registrasi pelanggan berhasil!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Pelanggan</title>
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
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #28a745;
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
            background-color: #1e7e34;
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
        <h2>Register Pelanggan</h2>
        <form method="post" action="">
            <label>Nama Pelanggan:</label>
            <input type="text" name="namapelanggan" required>

            <label>Jenis Kelamin:</label>
            <select name="jeniskelamin" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <label>No HP:</label>
            <input type="text" name="nohp" required>

            <label>Alamat:</label>
            <textarea name="alamat" required></textarea>

            <input type="submit" value="Register Pelanggan">
        </form>

        <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>
    </div>
</body>
</html>
