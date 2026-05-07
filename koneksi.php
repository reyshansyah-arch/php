<?php
/** * 1. PENGATURAN CORS (ORIGIN)
 * Bagian ini memperbaiki error "Unsafe attempt to load URL"
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Langsung hentikan jika request berupa OPTIONS (Preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

/** * 2. DEBUGGING (OPSIONAL)
 * Aktifkan ini untuk melihat pesan error asli jika masih muncul angka 500
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/** * 3. KONEKSI DATABASE RAILWAY
 */
$host = getenv('MYSQLHOST') ?: "mysql.railway.internal";
$user = getenv('MYSQLUSER') ?: "root";
$pass = getenv('MYSQLPASSWORD') ?: "LZstNChgUtUIzpLIpzjVtJVusCMZscPX";
$db   = getenv('MYSQLDATABASE') ?: "railway";
$port = getenv('MYSQLPORT') ?: 3306;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

// Periksa Koneksi
if (!$koneksi) {
    header('Content-Type: application/json');
    http_response_code(500);
    die(json_encode([
        "status" => "error",
        "message" => "Gagal terhubung ke database: " . mysqli_connect_error()
    ]));
}

// Set charset agar data teks aman
mysqli_set_charset($koneksi, "utf8");

/**
 * 4. TEST OUTPUT (Untuk memastikan 500 hilang)
 */
header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "message" => "CORS Berhasil diatur dan Database Terhubung!"
]);
