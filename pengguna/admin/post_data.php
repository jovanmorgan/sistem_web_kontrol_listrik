<?php
header('Content-Type: application/json');

// Ambil data POST
$tanggal = $_POST['tanggal'] ?? null;
$tegangan = $_POST['tegangan'] ?? null;
$arus = $_POST['arus'] ?? null;
$daya = $_POST['daya'] ?? null;
$energi = $_POST['energi'] ?? null;
$biaya = $_POST['biaya'] ?? null;

// Buat array data
$data = array(
    'tanggal' => $tanggal,
    'tegangan' => $tegangan,
    'arus' => $arus,
    'daya' => $daya,
    'energi' => $energi,
    'biaya' => $biaya
);

// Nama file JSON
$filename = "data.json";

// Tulis data ke file JSON jika data valid
if (is_numeric($tegangan) && $tegangan != 0 && is_numeric($arus) && $arus != 0) {
    $file = fopen($filename, "w");
    fwrite($file, json_encode($data));
    fclose($file);

    // Kirimkan data ke database
    include('../../keamanan/koneksi.php');

    // Periksa koneksi
    if ($koneksi) {
        $waktu = date("Y-m-d H:i:s");

        // Insert data ke tabel
        $query = "INSERT INTO sistem_listrik (tanggal, tegangan, arus, daya, energi, biaya) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('ddddds', $tanggal, $tegangan, $arus, $daya, $energi, $biaya);

        if ($stmt->execute()) {

            echo json_encode(array('status' => 'success', 'message' => 'Data Sudah Dikirim ke database .'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to insert data into database.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Database connection failed.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid data: Data TIdak Terkirim ke database.'));
}