<?php
/**
 * 1. HEADER CORS
 * Harus berada di paling atas agar browser memberikan izin akses.
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Hentikan eksekusi jika browser hanya mengirim 'preflight' (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

/**
 * 2. KONFIGURASI DATABASE
 * Mengambil variabel dari Environment Railway atau default ke internal.
 */
$host = getenv('MYSQLHOST') ?: "mysql.railway.internal";
$user = getenv('MYSQLUSER') ?: "root";
$pass = getenv('MYSQLPASSWORD') ?: "LZstNChgUtUIzpLIpzjVtJVusCMZscPX";
$db   = getenv('MYSQLDATABASE') ?: "railway";
$port = getenv('MYSQLPORT') ?: 3306;

/**
 * 3. MEMBUAT KONEKSI
 */
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

/**
 * 4. CEK KONEKSI
 */
if (!$koneksi) {
    // Mengirim respons error dalam format JSON
    header('Content-Type: application/json');
    http_response_code(500); // Memberitahu browser ini adalah error server
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal: " . mysqli_connect_error()
    ]);
    exit;
}

// Set karakter ke UTF-8 agar support simbol/emoticon jika ada
mysqli_set_charset($koneksi, "utf8");

// --- KODE PHP KAMU (QUERY DLL) DIMULAI DI SINI ---
