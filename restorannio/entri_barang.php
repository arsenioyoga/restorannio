<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namamenu = $_POST['namamenu'];
    $harga = $_POST['harga'];
    mysqli_query($conn, "INSERT INTO menu (namamenu, harga) VALUES ('$namamenu', '$harga')");
    header("Location: entri_barang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM menu WHERE idmenu = $id");
    header("Location: entri_barang.php");
    exit;
}

$menu = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230929/pngtree-3d-rendering-of-restaurant-signboard-in-mockup-image_13526069.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            padding: 12px;
            display: block;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-size: 16px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .content h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
        }

        .content p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .logout-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        .sidebar img {
            width: 50px;
            margin-bottom: 20px;
            display: block;
            margin: 0 auto;
        }

        .logout-link {
            font-size: 16px;
        }

        /* Styling for form */
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

        /* Table styling */
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

<div class="wrapper">
    <div class="sidebar">
        <img src="https://yourlogo.png" alt="Logo" />
        <h2>Admin Panel</h2>
        <a href="dashboard_admin.php">📊 Dashboard</a>
        <a href="entri_barang.php">📦 Entri Barang</a>
        <a href="entri_meja.php">🪑 Entri Meja</a>
        <a href="entri_order.php">📝 Entri Order</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="card">
            <h1>Entri Barang</h1>
            <form method="POST">
                <label>Nama Menu</label>
                <input type="text" name="namamenu" required>
                <label>Harga</label>
                <input type="number" name="harga" required>
                <button type="submit">Simpan</button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Menu</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($m = mysqli_fetch_assoc($menu)) : ?>
                <tr>
                    <td><?= $m['idmenu'] ?></td>
                    <td><?= $m['namamenu'] ?></td>
                    <td>Rp<?= number_format($m['harga']) ?></td>
                    <td><a class="hapus" href="?hapus=<?= $m['idmenu'] ?>" onclick="return confirm('Yakin ingin hapus menu ini?')">Hapus</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
