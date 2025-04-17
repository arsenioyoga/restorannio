<?php
session_start();
include 'koneksi.php';

$allowed_roles = ['waiter', 'kasir', 'owner'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
$role_cap = ucfirst($role);

if (isset($_POST['tambah'])) {
    $tanggal = $_POST['tanggal'];
    $detail = $_POST['detail'];
    mysqli_query($conn, "INSERT INTO laporan (tanggal, detail) VALUES ('$tanggal', '$detail')");
    header("Location: laporan.php");
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['idlaporan'];
    $tanggal = $_POST['tanggal'];
    $detail = $_POST['detail'];
    mysqli_query($conn, "UPDATE laporan SET tanggal='$tanggal', detail='$detail' WHERE idlaporan=$id");
    header("Location: laporan.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM laporan WHERE idlaporan = $id");
    header("Location: laporan.php");
    exit;
}

$edit_mode = false;
$edit_data = null;

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM laporan WHERE idlaporan = $id");
    $edit_data = mysqli_fetch_assoc($res);
}

$laporan = mysqli_query($conn, "SELECT * FROM laporan ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background-color: #f4f6f9;
            color: #333;
        }

        .sidebar {
            width: 240px;
            background-color: #343a40;
            color: white;
            padding: 30px 20px;
            min-height: 100vh;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .main {
            flex: 1;
            padding: 40px;
        }

        .card {
            background-color: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            max-width: 900px;
        }

        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }

        .aksi a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        .aksi a.hapus {
            color: #dc3545;
        }

        .aksi a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><?= $role_cap ?> Panel</h2>
        <a href="dashboard_<?= $role ?>.php">Dashboard</a>
        
        <?php if ($role === 'waiter') : ?>
            <a href="entri_barang.php">Entri Barang</a>
            <a href="entri_order.php">Entri Order</a>
        <?php elseif ($role === 'kasir') : ?>
            <a href="entri_transaksi.php">Entri Transaksi</a>
        <?php endif; ?>

        <a class="active" href="laporan.php">Manajemen Laporan</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">
        <div class="card">
            <h2><?= $edit_mode ? 'Edit Laporan' : 'Tambah Laporan' ?></h2>
            <form method="POST">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="idlaporan" value="<?= $edit_data['idlaporan'] ?>">
                <?php endif; ?>
                <div style="margin-bottom: 20px;">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" required value="<?= $edit_mode ? $edit_data['tanggal'] : '' ?>">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="detail">Detail Laporan</label>
                    <textarea name="detail" id="detail" rows="5" required><?= $edit_mode ? htmlspecialchars($edit_data['detail']) : '' ?></textarea>
                </div>
                <button type="submit" name="<?= $edit_mode ? 'edit' : 'tambah' ?>">
                    <?= $edit_mode ? 'Update Laporan' : 'Simpan Laporan' ?>
                </button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Laporan</h2>
            <table>
                <tr>
                    <th>Tanggal</th>
                    <th>Detail</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($laporan)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['detail'])) ?></td>
                    <td class="aksi">
                        <a href="?edit=<?= $row['idlaporan'] ?>">Edit</a>
                        <a href="?hapus=<?= $row['idlaporan'] ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
