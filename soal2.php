<?php

// File konfigurasi database
require_once "db_config.php";

// Ambil kata kunci pencarian dari URL, jika ada
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Query SQL dasar untuk menghitung jumlah orang per hobi
$sql = "
    SELECT 
        hobi, 
        COUNT(DISTINCT person_id) AS jumlah_person
    FROM 
        hobi
";

// Jika ada kata kunci pencarian, tambahkan kondisi WHERE
if (!empty($search_keyword)) {
    // Prepared statement untuk keamanan
    $sql .= " WHERE hobi LIKE ?";
}

$sql .= " 
    GROUP BY 
        hobi
    ORDER BY 
        jumlah_person DESC, hobi ASC
";

$stmt = $mysqli->prepare($sql);

if ($stmt) {
    // Jika ada pencarian, bind parameternya
    if (!empty($search_keyword)) {
        $search_param = "%" . $search_keyword . "%";
        $stmt->bind_param("s", $search_param);
    }
    
    // Eksekusi query
    $stmt->execute();
    
    // Ambil hasilnya
    $result = $stmt->get_result();
} else {
    // Jika query gagal disiapkan, tampilkan error
    echo "Error: " . $mysqli->error;
    $result = false; // Set result menjadi false
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal 2 - Laporan Hobi</title>
    <!-- Menggunakan font dan beberapa style dari Soal 1 untuk konsistensi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; margin: 0; padding: 2em; color: #333; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 2.5em; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        h1 { color: #4c1d95; text-align: center; }

        .search-form { 
            margin-bottom: 2em; 
            text-align: center; 
            display: flex; /* Menggunakan flexbox untuk alignment */
            justify-content: center;
            align-items: center;
            gap: 0.5em; /* Memberi jarak antar elemen */
        }
        .search-form input[type="text"] { padding: 0.8em; font-size: 1em; border: 1px solid #ccc; border-radius: 8px; width: 60%; }
        
        /* Style umum untuk semua tombol di form */
        .btn {
            padding: 0.8em 1.5em;
            font-size: 1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none; /* Untuk tag <a> */
            display: inline-block; /* Untuk tag <a> */
            transition: all 0.2s ease;
        }
        .btn-primary { background: #6d28d9; color: white; }
        .btn-secondary { background: #e5e7eb; color: #374151; border: 1px solid #d1d5db; }
        .btn:hover { transform: translateY(-2px); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 1.5em; }
        th, td { padding: 1em; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: 700; color: #4c1d95; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .no-data { text-align: center; color: #777; padding: 2em; }
    </style>
</head>
<body>

<div class="container">
    <h1>Laporan Hobi</h1>

    <!-- Form Pencarian -->
    <div class="search-form">
        <form action="soal2.php" method="get" style="display: contents;">
            <input type="text" name="search" placeholder="Cari berdasarkan hobi..." value="<?php echo htmlspecialchars($search_keyword); ?>">
            <input type="submit" value="Cari" class="btn btn-primary">
            
            <?php
            // Tampilkan tombol "Tampilkan Semua" HANYA jika ada kata kunci pencarian yang aktif
            if (!empty($search_keyword)) {
                echo '<a href="soal2.php" class="btn btn-secondary">Tampilkan Semua</a>';
            }
            ?>
            
        </form>
    </div>

    <!-- Tabel Hasil -->
    <table>
        <thead>
            <tr>
                <th>Hobi</th>
                <th>Jumlah Person</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['hobi']); ?></td>
                        <td><?php echo htmlspecialchars($row['jumlah_person']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="no-data">
                        <?php echo empty($search_keyword) ? "Tidak ada data hobi." : "Hobi tidak ditemukan."; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php
// Tutup statement dan koneksi database
if (isset($stmt)) {
    $stmt->close();
}
$mysqli->close();
?>

</body>
</html>