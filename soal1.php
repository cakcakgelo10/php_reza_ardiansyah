<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal 1 - Web Programmer Screening</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>

        /* Dasar Halaman */
        body {
            font-family: 'Poppins', sans-serif; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            margin: 0;
            color: #333;
        }

        /* Kontainer Utama */
        .container {
            background: #ffffff;
            padding: 2.5em;
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 600px;
            transition: all 0.3s ease;
        }

        /* Judul */
        h1, h2 {
            color: #4c1d95; 
            margin-bottom: 1em;
        }

        /* Grup Form */
        .form-group {
            margin-bottom: 1.5em;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: 500; 
            color: #555;
        }

        /* Input Fields (Textbox & Number) */
        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 0.8em;
            border: 1px solid #ccc;
            border-radius: 8px; 
            box-sizing: border-box;
            font-size: 1em;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input[type="number"]:focus, input[type="text"]:focus {
            border-color: #6d28d9; 
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.1); 
            outline: none;
        }

        /* Tombol Submit */
        input[type="submit"] {
            background: linear-gradient(90deg, #8b5cf6, #6d28d9); 
            color: white;
            padding: 0.8em 1.5em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 500;
            letter-spacing: 0.5px;
            width: 100%;
            margin-top: 1em;
            transition: all 0.3s ease;
        }
        input[type="submit"]:hover {
            transform: translateY(-3px); 
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Tampilan Hasil */
        .result {
            text-align: left;
            margin-top: 2em;
        }
        .result p {
            background-color: #f8f9fa;
            border-left: 4px solid #8b5cf6; 
            padding: 0.75em;
            margin: 0.5em 0;
            border-radius: 4px;
        }
        
        /* Layout Grid untuk Form Dinamis */
        .grid-container {
            display: grid;
            gap: 1em;
            margin-bottom: 1.5em;
        }

        /* Link "Kembali" */
        a {
            color: #6d28d9;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        a:hover {
            text-decoration: underline;
            color: #4c1d95;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Generator Form Dinamis</h1>

    <?php
    // LOGIKA UTAMA 

    // Kondisi 3: Menampilkan hasil akhir
    if (isset($_POST['submit_data'])) {
        echo "<h2>Hasil Input:</h2>";
        echo "<div class='result'>";
        
        for ($i = 1; $i <= $_POST['jumlah_baris']; $i++) {
            for ($j = 1; $j <= $_POST['jumlah_kolom']; $j++) {
                $fieldName = "data[{$i}][{$j}]";
                $value = isset($_POST['data'][$i][$j]) ? htmlspecialchars($_POST['data'][$i][$j]) : '';
                echo "<p>{$i}.{$j} : {$value}</p>";
            }
        }
        echo "</div>";
        echo '<br><a href="soal1.php">Kembali ke Awal</a>';

    // Kondisi 2: Menampilkan form input dinamis
    } elseif (isset($_POST['submit_dimensi'])) {
        $jumlah_baris = isset($_POST['jumlah_baris']) ? (int)$_POST['jumlah_baris'] : 0;
        $jumlah_kolom = isset($_POST['jumlah_kolom']) ? (int)$_POST['jumlah_kolom'] : 0;

        if ($jumlah_baris > 0 && $jumlah_kolom > 0) {
    ?>
            <h2>Input Data</h2>
            <form action="soal1.php" method="post">
                <input type="hidden" name="jumlah_baris" value="<?php echo $jumlah_baris; ?>">
                <input type="hidden" name="jumlah_kolom" value="<?php echo $jumlah_kolom; ?>">

                <div class="grid-container" style="grid-template-columns: repeat(<?php echo $jumlah_kolom; ?>, 1fr);">
                    <?php
                    for ($i = 1; $i <= $jumlah_baris; $i++) {
                        for ($j = 1; $j <= $jumlah_kolom; $j++) {
                    ?>
                            <div class="form-group">
                                <label for="data_<?php echo $i; ?>_<?php echo $j; ?>"><?php echo "{$i}.{$j}:"; ?></label>
                                <input type="text" id="data_<?php echo $i; ?>_<?php echo $j; ?>" name="data[<?php echo $i; ?>][<?php echo $j; ?>]" required>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                
                <input type="submit" name="submit_data" value="Submit Data">
            </form>
    <?php
        } else {
            echo "<p style='color: red;'>Jumlah baris dan kolom harus lebih dari 0.</p>";
            tampilkanFormDimensi();
        }

    // Kondisi 1: Tampilan awal
    } else {
        tampilkanFormDimensi();
    }

    /**
     * Fungsi untuk menampilkan form awal (jumlah baris & kolom).
     */
    function tampilkanFormDimensi() {
    ?>
        <h2>Tentukan Dimensi Form</h2>
        <form action="soal1.php" method="post">
            <div class="form-group">
                <label for="jumlah_baris">Inputkan Jumlah Baris:</label>
                <input type="number" id="jumlah_baris" name="jumlah_baris" placeholder="Contoh: 1" required>
            </div>
            <div class="form-group">
                <label for="jumlah_kolom">Inputkan Jumlah Kolom:</label>
                <input type="number" id="jumlah_kolom" name="jumlah_kolom" placeholder="Contoh: 3" required>
            </div>
            <input type="submit" name="submit_dimensi" value="Submit">
        </form>
    <?php
    }
    ?>

</div>

</body>
</html>
