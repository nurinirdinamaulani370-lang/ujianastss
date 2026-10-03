<?php

session_start();

include "koneksi.php";
include "fonnte.php";

// =============================
// AMBIL DATA DARI FORM
// =============================

$username = trim($_POST['username'] ?? '');
$name = trim($_POST['name'] ?? '');
$nisn = trim($_POST['nisn'] ?? '');
$ttl = trim($_POST['ttl'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirmation = $_POST['password_confirmation'] ?? '';
$no_hp = trim($_POST['no_hp'] ?? '');
$address = trim($_POST['address'] ?? '');


// =============================
// VALIDASI DATA WAJIB
// =============================

if (
    $username === '' ||
    $name === '' ||
    $nisn === '' ||
    $ttl === '' ||
    $gender === '' ||
    $email === '' ||
    $password === '' ||
    $password_confirmation === '' ||
    $no_hp === '' ||
    $address === ''
) {
    die("Semua data wajib diisi.");
}


// =============================
// VALIDASI USERNAME
// =============================

if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $username)) {
    die("Username harus 4-50 karakter dan hanya boleh menggunakan huruf, angka, atau underscore.");
}


// =============================
// VALIDASI NISN
// =============================

if (!preg_match('/^[0-9]{10}$/', $nisn)) {
    die("NISN harus terdiri dari 10 digit angka.");
}


// =============================
// VALIDASI EMAIL
// =============================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}


// =============================
// VALIDASI PASSWORD
// =============================

if (strlen($password) < 6) {
    die("Password minimal 6 karakter.");
}

if ($password !== $password_confirmation) {
    die("Konfirmasi password tidak sama dengan password.");
}


// =============================
// VALIDASI NOMOR HP
// =============================

if (!preg_match('/^[0-9]{10,15}$/', $no_hp)) {
    die("Nomor HP harus berupa angka 10-15 digit.");
}


// =============================
// CEK USERNAME
// =============================

$cekUsername = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE username = ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $cekUsername,
    "s",
    $username
);

mysqli_stmt_execute($cekUsername);
mysqli_stmt_store_result($cekUsername);

if (mysqli_stmt_num_rows($cekUsername) > 0) {

    mysqli_stmt_close($cekUsername);

    die("Username sudah digunakan. Silakan pilih username lain.");
}

mysqli_stmt_close($cekUsername);


// =============================
// CEK NISN
// =============================

$cekNisn = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE nisn = ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $cekNisn,
    "s",
    $nisn
);

mysqli_stmt_execute($cekNisn);
mysqli_stmt_store_result($cekNisn);

if (mysqli_stmt_num_rows($cekNisn) > 0) {

    mysqli_stmt_close($cekNisn);

    die("NISN sudah terdaftar.");
}

mysqli_stmt_close($cekNisn);


// =============================
// CEK EMAIL
// =============================

$cekEmail = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE email = ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $cekEmail,
    "s",
    $email
);

mysqli_stmt_execute($cekEmail);
mysqli_stmt_store_result($cekEmail);

if (mysqli_stmt_num_rows($cekEmail) > 0) {

    mysqli_stmt_close($cekEmail);

    die("Email sudah terdaftar.");
}

mysqli_stmt_close($cekEmail);


// =============================
// HASH PASSWORD
// =============================

$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


// =============================
// SIMPAN DATA KE DATABASE
// =============================

$query = mysqli_prepare(
    $conn,
    "INSERT INTO users
    (username, name, nisn, ttl, gender, email, password, no_hp, address)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$query) {
    die("Query database gagal: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $query,
    "sssssssss",
    $username,
    $name,
    $nisn,
    $ttl,
    $gender,
    $email,
    $password_hash,
    $no_hp,
    $address
);


// =============================
// EKSEKUSI PENDAFTARAN
// =============================

if (mysqli_stmt_execute($query)) {
    $messageAdmin = "🔔 *PENDAFTARAN AKUN BARU ASTS*

Username: $username
Nama: $name
NISN: $nisn
Email: $email
No. HP: $no_hp

Akun siswa berhasil dibuat.";

kirimWhatsApp($ADMIN_WA, $messageAdmin);

    mysqli_stmt_close($query);

    header("Location: login.php?register=success");
    exit;

} else {

    $error = mysqli_error($conn);

    mysqli_stmt_close($query);

    die("Gagal mendaftarkan akun: " . $error);
}