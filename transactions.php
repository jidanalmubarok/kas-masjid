<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Koneksi database
$conn = new mysqli('localhost', 'root', '', 'keuangan_masjid');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses tambah transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah = $_POST['jumlah'];
    $status = $jumlah > 0 ? "Pemasukan" : "Pengeluaran";

    $sql = "INSERT INTO transaksi (tanggal, deskripsi, jumlah, status) VALUES ('$tanggal', '$deskripsi', '$jumlah', '$status')";
    if ($conn->query($sql) === TRUE) {
        $message = "Transaksi berhasil ditambahkan!";
    } else {
        $message = "Gagal menambahkan transaksi: " . $conn->error;
    }
}

// Proses hapus transaksi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM transaksi WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Transaksi berhasil dihapus!";
    } else {
        $message = "Gagal menghapus transaksi: " . $conn->error;
    }
}

// Query transaksi
$sql = "SELECT * FROM transaksi ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Sistem Keuangan Masjid</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .sidebar {
            width: 200px;
            height: 100%;
            position: fixed;
            background: rgb(18, 18, 18);
            color: white;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            text-align: center;
            margin: 10px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #bdc3c7;
            font-size: 18px;
            display: block;
            transition: 0.3s;
        }
        .sidebar ul li a:hover {
            color: #ecf0f1;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        h1 {
            font-size: 28px;
            color: rgb(237, 240, 244);
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container input, .form-container button {
            width: calc(100% - 20px);
            margin: 10px 10px 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container button {
            background: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background:rgb(12, 12, 13);
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .status {
            padding: 5px 10px;
            border-radius: 12px;
            color: white;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .status.pemasukan {
            background-color: #27ae60;
        }
        .status.pengeluaran {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Transaksi</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <h1>Tambah Transaksi</h1>
        <div class="form-container">
            <form action="" method="POST">
                <input type="date" name="tanggal" required>
                <input type="text" name="deskripsi" placeholder="Deskripsi" required>
                <input type="number" name="jumlah" placeholder="Jumlah (gunakan tanda negatif untuk pengeluaran)" required>
                <button type="submit">Simpan</button>
            </form>
            <?php if (isset($message)) echo "<p>$message</p>"; ?>
        </div>

        <h1>Daftar Transaksi</h1>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status_class = $row["jumlah"] > 0 ? "pemasukan" : "pengeluaran";
                        $status_label = $row["jumlah"] > 0 ? "Pemasukan" : "Pengeluaran";
                        echo "<tr>";
                        echo "<td>" . $row["tanggal"] . "</td>";
                        echo "<td>" . $row["deskripsi"] . "</td>";
                        echo "<td>Rp " . number_format($row["jumlah"], 2, ',', '.') . "</td>";
                        echo "<td><span class='status $status_class'>" . $status_label . "</span></td>";
                        echo "<td><a href='?hapus=" . $row["id"] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus transaksi ini?\")'>Hapus</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada transaksi</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>

<?php
$conn->close();
?>
