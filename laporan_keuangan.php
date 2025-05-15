<?php
// Koneksi ke Database
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "keuangan_masjid";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Periksa Koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Data Dummy untuk Testing (Ganti dengan data dinamis dari database atau session)
$username = "Admin";
$total_pemasukan = 10000000; // Total Pemasukan
$total_pengeluaran = 4000000; // Total Pengeluaran
$saldo = $total_pemasukan - $total_pengeluaran; // Sisa Saldo
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Kas Masjid</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 200px;
            height: 100%;
            position: fixed;
            background:rgb(8, 8, 8);
            color: white;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
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
            color: white;
            padding: 10px;
            display: block;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #27ae60;
            color: white;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .financial-summary {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            flex: 1;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .card p {
            font-size: 22px;
            font-weight: bold;
        }

        .card p.total-pemasukan {
            color: #27ae60;
        }

        .card p.total-pengeluaran {
            color: #e74c3c;
        }

        .card p.saldo {
            color: #3498db;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background:rgb(1, 1, 2);
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
            text-align: center;
            font-size: 14px;
            font-weight: black;
            color: white;
        }

        .status.pemasukan {
            background-color: #27ae60;
        }

        .status.pengeluaran {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Laporan</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <p></p>
        </header>

        <section class="financial-summary">
            <div class="card">
                <h3>Total Pemasukan</h3>
                <p class="total-pemasukan">Rp <?php echo number_format($total_pemasukan, 2, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h3>Total Pengeluaran</h3>
                <p class="total-pengeluaran">Rp <?php echo number_format($total_pengeluaran, 2, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h3>Sisa Saldo</h3>
                <p class="saldo">Rp <?php echo number_format($saldo, 2, ',', '.'); ?></p>
            </div>
        </section>

        <section class="transaction-list">
            <h2>Daftar Transaksi</h2>
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
                    $sql = "SELECT * FROM transaksi ORDER BY tanggal DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_class = $row["jumlah"] > 0 ? "pemasukan" : "pengeluaran";
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["tanggal"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["deskripsi"]) . "</td>";
                            echo "<td>Rp " . number_format($row["jumlah"], 2, ',', '.') . "</td>";
                            echo "<td><span class='status $status_class'>" . ($row["jumlah"] > 0 ? "Pemasukan" : "Pengeluaran") . "</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada transaksi</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <form action="generate_pdf.php" method="POST">
        <button type="submit" class="download-pdf-btn">Unduh Laporan PDF</button>
    </form>

        </section>
    </main>

    <footer>
    </footer>
</body>
</html>
