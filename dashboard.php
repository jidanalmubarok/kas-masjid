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

// Ambil data ringkasan keuangan
$sql_pemasukan = "SELECT SUM(jumlah) AS total_pemasukan FROM transaksi WHERE jumlah > 0";
$sql_pengeluaran = "SELECT SUM(jumlah) AS total_pengeluaran FROM transaksi WHERE jumlah < 0";

$result_pemasukan = $conn->query($sql_pemasukan)->fetch_assoc();
$result_pengeluaran = $conn->query($sql_pengeluaran)->fetch_assoc();

$total_pemasukan = $result_pemasukan['total_pemasukan'] ?: 0;
$total_pengeluaran = abs($result_pengeluaran['total_pengeluaran']) ?: 0;
$saldo = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Keuangan Masjid</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Tambahkan Gaya CSS */
        body {
            font-family: 'Arial', sans-serif;
            margin: 100;
            padding: 50;
            background-color:rgb(239, 225, 225);
        }
        .sidebar {
            width: 200px;
            height: 100%;
            position: fixed;
            background:rgb(8, 0, 0);
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
            color:rgb(174, 176, 179);
        }
        .header p {
            font-size: 16px;
            color: #7f8c8d;
        }
        .financial-summary {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            flex: 1;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h3 {
            font-size: 20px;
            color:rgb(2, 11, 21);
        }
        .card p {
            font-size: 24px;
            color: #27ae60;
        }
        .transaction-list {
            margin-top: 30px;
        }
        .transaction-list h2 {
            font-size: 22px;
            color:rgb(242, 247, 251);
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #ecf0f1;
            color:rgb(221, 224, 226);
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status {
            padding: 5px 10px;
            border-radius: 12px;
            color: white;
        }
        .status.pemasukan {
            background: #27ae60;
        }
        .status.pengeluaran {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Keuangan Masjid</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="transactions.php">Transaksi</a></li>
            <li><a href="laporan_keuangan.php">Laporan Keuangan</a></li>
            <li><a href="data donasi.php">data donasi</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="header">
        </header>

        <section class="financial-summary">
            <div class="card">
                <h3>Total Pemasukan</h3>
                <p>Rp <?php echo number_format($total_pemasukan, 2, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h3>Total Pengeluaran</h3>
                <p>Rp <?php echo number_format($total_pengeluaran, 2, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h3>Sisa Saldo</h3>
                <p>Rp <?php echo number_format($saldo, 2, ',', '.'); ?></p>
            </div>
        </section>

        <section class="transaction-list">
            <h2>Transaksi Terbaru</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_transaksi = "SELECT tanggal, deskripsi, jumlah, 
                                      IF(jumlah > 0, 'Pemasukan', 'Pengeluaran') AS status 
                                      FROM transaksi ORDER BY tanggal DESC LIMIT 5";
                    $result_transaksi = $conn->query($sql_transaksi);

                    if ($result_transaksi->num_rows > 0) {
                        while ($row = $result_transaksi->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['tanggal'] . "</td>";
                            echo "<td>" . $row['deskripsi'] . "</td>";
                            echo "<td>Rp " . number_format($row['jumlah'], 2, ',', '.') . "</td>";
                            echo "<td><span class='status " . strtolower($row['status']) . "'>" . $row['status'] . "</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada transaksi</td></tr>";
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
