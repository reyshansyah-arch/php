<?php
/**
 * 1. PENGATURAN CORS (Origin)
 * Menghilangkan error "Failed to load resource" di konsol browser.
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Respon cepat untuk preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * 2. KONFIGURASI DATABASE
 * Di Railway, sangat disarankan menggunakan variabel environment.
 * Jika 'MYSQL_URL' tersedia, kita akan menggunakannya karena lebih akurat.
 */
$host = getenv('MYSQLHOST') ?: "mysql.railway.internal";
$user = getenv('MYSQLUSER') ?: "root";
$pass = getenv('MYSQLPASSWORD') ?: "WgBoSspfgeoGkqpLUnZpXbQMUiullkUZ";
$db   = getenv('MYSQLDATABASE') ?: "railway";
$port = getenv('MYSQLPORT') ?: 3306;

/**
 * 3. KONEKSI DENGAN PENANGANAN ERROR
 */
// Gunakan try-catch agar error 'Access Denied' tidak membuat halaman blank
mysqli_report(MYSQLI_REPORT_OFF); 
$koneksi = @mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    header('Content-Type: application/json');
    // Jika koneksi gagal, berikan info origin agar browser tidak blokir pesan errornya
    echo json_encode([
        "status" => "error",
        "message" => "Gagal terhubung ke database. Silakan cek Username/Password di Dashboard Railway.",
        "debug_error" => mysqli_connect_error()
    ]);
    exit;
}

// Jika berhasil
mysqli_set_charset($koneksi, "utf8");

// Testing Output
header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "message" => "CORS Aktif & Koneksi Berhasil!"
]);
