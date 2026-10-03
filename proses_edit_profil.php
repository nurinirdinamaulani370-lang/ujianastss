<?php

session_start();
include "koneksi.php";
include "fonnte.php";

if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

$name = trim($_POST['name'] ?? '');
$nisn = trim($_POST['nisn'] ?? '');
$ttl = trim($_POST['ttl'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$email = trim($_POST['email'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$address = trim($_POST['address'] ?? '');


// Validasi
if (
    $name === '' ||
    $nisn === '' ||
    $ttl === '' ||
    $gender === '' ||
    $email === '' ||
    $no_hp === '' ||
    $address === ''
) {
    die("Semua data wajib diisi.");
}


// Validasi NISN
if (!preg_match('/^[0-9]{10}$/', $nisn)) {
    die("NISN harus terdiri dari 10 digit angka.");
}


// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}


// Validasi nomor HP
if (!preg_match('/^[0-9]{10,15}$/', $no_hp)) {
    die("Nomor HP harus berupa angka 10-15 digit.");
}


// Ambil data lama
$oldQuery = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id = ? LIMIT 1"
);

mysqli_stmt_bind_param($oldQuery, "i", $id);
mysqli_stmt_execute($oldQuery);

$oldResult = mysqli_stmt_get_result($oldQuery);

if (!$oldResult || mysqli_num_rows($oldResult) !== 1) {
    die("Data pengguna tidak ditemukan.");
}

$old = mysqli_fetch_assoc($oldResult);

mysqli_stmt_close($oldQuery);


// Cek NISN milik pengguna lain
$cekNisn = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE nisn = ? AND id != ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $cekNisn,
    "si",
    $nisn,
    $id
);

mysqli_stmt_execute($cekNisn);
mysqli_stmt_store_result($cekNisn);

if (mysqli_stmt_num_rows($cekNisn) > 0) {
    mysqli_stmt_close($cekNisn);
    die("NISN sudah digunakan oleh akun lain.");
}

mysqli_stmt_close($cekNisn);


// Cek email milik pengguna lain
$cekEmail = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $cekEmail,
    "si",
    $email,
    $id
);

mysqli_stmt_execute($cekEmail);
mysqli_stmt_store_result($cekEmail);

if (mysqli_stmt_num_rows($cekEmail) > 0) {
    mysqli_stmt_close($cekEmail);
    die("Email sudah digunakan oleh akun lain.");
}

mysqli_stmt_close($cekEmail);


// Update data
$query = mysqli_prepare(
    $conn,
    "UPDATE users
     SET name = ?,
         nisn = ?,
         ttl = ?,
         gender = ?,
         email = ?,
         no_hp = ?,
         address = ?
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $query,
    "sssssssi",
    $name,
    $nisn,
    $ttl,
    $gender,
    $email,
    $no_hp,
    $address,
    $id
);


if (mysqli_stmt_execute($query)) {
$perubahan = [];

if ($old['name'] !== $name) {
    $perubahan[] = "Nama: {$old['name']} → $name";
}

if ($old['nisn'] !== $nisn) {
    $perubahan[] = "NISN: {$old['nisn']} → $nisn";
}

if ($old['ttl'] !== $ttl) {
    $perubahan[] = "TTL: {$old['ttl']} → $ttl";
}

if ($old['gender'] !== $gender) {
    $perubahan[] = "Jenis Kelamin: {$old['gender']} → $gender";
}

if ($old['email'] !== $email) {
    $perubahan[] = "Email: {$old['email']} → $email";
}

if ($old['no_hp'] !== $no_hp) {
    $perubahan[] = "Nomor HP: {$old['no_hp']} → $no_hp";
}

if ($old['address'] !== $address) {
    $perubahan[] = "Alamat berubah";
}

if (!empty($perubahan)) {

    $detailPerubahan = implode("\n", $perubahan);

    $messageEdit = "✏️ *PROFIL BERHASIL DIUBAH*

Halo $name,

Data profil Anda berhasil diubah.

Perubahan:
$detailPerubahan

Jika perubahan ini bukan dilakukan oleh Anda, segera hubungi admin.";

    // Kirim ke nomor baru jika nomor HP ikut diubah
    kirimWhatsApp($no_hp, $messageEdit);
}
    mysqli_stmt_close($query);

    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;

    header("Location: profil.php?update=success");
    exit;

} else {

    $error = mysqli_error($conn);

    mysqli_stmt_close($query);

    die("Gagal mengubah profil: " . $error);
}