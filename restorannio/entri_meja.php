<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeja = $_POST['nomeja'];
    mysqli_query($conn, "INSERT INTO meja (nomeja) VALUES ('$nomeja')");
    header("Location: entri_meja.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM meja WHERE idmeja = $id");
    header("Location: entri_meja.php");
    exit;
}

$meja = mysqli_query($conn, "SELECT * FROM meja");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Meja</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background-color: #1e272e;
            color: white;
        }

        .sidebar {
            position: fixed;
            height: 100vh;
            width: 200px;
            background-color: #2f3640;
            padding-top: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            padding: 14px 20px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #353b48;
        }

        .main {
            margin-left: 220px;
            padding: 40px;
        }

        .card {
            background-color: #353b48;
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            margin-bottom: 40px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
        }

        input {
            background-color: #2d3436;
            color: white;
        }

        button {
            background-color: #00a8ff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0097e6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2d3436;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #444;
            text-align: center;
        }

        th {
            background-color: #487eb0;
        }

        .hapus {
            color: #ff6b6b;
            text-decoration: none;
        }

        .hapus:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="dashboard_admin.php">📊 <span>Dashboard</span></a>
    <a href="entri_barang.php">📦 <span>Entri Barang</span></a>
    <a href="entri_meja.php">🪑 <span>Entri Meja</span></a>
    <a href="entri_order.php">📝 <span>Entri Order</span></a>
    <a href="logout.php">🚪 <span>Logout</span></a>
</div>

<div class="main">
    <div class="card">
        <h2>Entri Meja</h2>
        <form method="POST">
            <label>Nomor Meja</label>
            <input type="number" name="nomeja" required>
            <button type="submit">Simpan</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Meja</h2>
        <table>
            <tr>
                <th>ID Meja</th>
                <th>Nomor Meja</th>
                <th>Aksi</th>
            </tr>
            <?php while ($m = mysqli_fetch_assoc($meja)) : ?>
            <tr>
                <td><?= $m['idmeja'] ?></td>
                <td><?= $m['nomeja'] ?></td>
                <td><a class="hapus" href="?hapus=<?= $m['idmeja'] ?>" onclick="return confirm('Hapus meja ini?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
