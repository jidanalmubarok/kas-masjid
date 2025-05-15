<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'keuangan_masjid');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah donasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_donation'])) {
    $tanggal = $_POST['tanggal'];
    $nama_donatur = $_POST['nama_donatur'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    $sql_tambah = "INSERT INTO donasi (tanggal, nama_donatur, jumlah, keterangan) 
                   VALUES ('$tanggal', '$nama_donatur', '$jumlah', '$keterangan')";
    if ($conn->query($sql_tambah)) {
        $message = "Donasi berhasil ditambahkan.";
    } else {
        $message = "Gagal menambahkan donasi: " . $conn->error;
    }
}

// Hapus donasi
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_hapus = "DELETE FROM donasi WHERE id = '$delete_id'";
    if ($conn->query($sql_hapus)) {
        $message = "Donasi berhasil dihapus.";
    } else {
        $message = "Gagal menghapus donasi: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Masuk - Keuangan Masjid</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(239, 225, 225);
        }
        .sidebar {
            width: 200px;
            height: 100%;
            position: fixed;
            background: rgb(10, 1, 1);
            color: white;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #ecf0f1;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
            text-align: center;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #bdc3c7;
            font-size: 18px;
            transition: 0.3s;
        }
        .sidebar ul li a:hover {
            color: #ecf0f1;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: rgb(232, 234, 238);
        }
        .donation-form {
            margin-top: 20px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(245, 243, 243, 0.1);
        }
        .donation-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .donation-form input, .donation-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            
        }
        .donation-form button {
            padding: 10px 20px;
            background: rgb(47, 155, 43);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .donation-form button:hover {
            background: #3a8d37;
        }
        .donation-list {
            margin-top: 20px;
        }
        .donation-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background:rgb(235, 242, 235);
        }
        .donation-list th, .donation-list td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .donation-list th {
            background:rgb(6, 6, 6);
            color:rgb(249, 249, 255);
        }
        .donation-list tr:nth-child(even) {
            background:rgb(241, 241, 241);
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Keuangan Masjid</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="header">
            <h1>Donasi Masuk</h1>
            <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
        </header>

        <!-- Form Tambah Donasi -->
        <section class="donation-form">
            <h2>Tambah Donasi</h2>
            <form method="POST" action="">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" required>
                <label for="nama_donatur">Nama Donatur</label>
                <input type="text" id="nama_donatur" name="nama_donatur" required>
                <label for="jumlah">Jumlah</label>
                <input type="number" id="jumlah" name="jumlah" required>
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4"></textarea>
                <button type="submit" name="add_donation">Tambah Donasi</button>
            </form>
        </section>

        <!-- Daftar Donasi -->
        <section class="donation-list">
            <h2>Daftar Donasi Masuk</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Donatur</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_donasi = "SELECT tanggal, nama_donatur, jumlah, keterangan FROM donasi ORDER BY tanggal DESC";
                    $result_donasi = $conn->query($sql_donasi);

                    if ($result_donasi->num_rows > 0) {
                        while ($row = $result_donasi->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['tanggal'] . "</td>";
                            echo "<td>" . $row['nama_donatur'] . "</td>";
                            echo "<td>Rp " . number_format($row['jumlah'], 2, ',', '.') . "</td>";
                            echo "<td>" . $row['keterangan'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada donasi</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <footer>
        </footer>
    </div>
</body>
</html>
<?php $conn->close(); ?>
