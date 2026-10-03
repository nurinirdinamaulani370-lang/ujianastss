<?php

session_start();
include "koneksi.php";

if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

$query = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id = ? LIMIT 1"
);

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

if (!$result || mysqli_num_rows($result) !== 1) {
    die("Data pengguna tidak ditemukan.");
}

$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Saya - ASTS</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0b0b0b;
            color: white;
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            color: #d4af37;
            margin-bottom: 8px;
        }

        .header p {
            color: #999;
        }

        .card {
            background: #151515;
            border: 1px solid #c9a227;
            border-radius: 18px;
            padding: 30px;
        }

        .profile-top {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #d4af37;
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 35px;
            font-weight: bold;
        }

        .profile-top h2 {
            margin-top: 15px;
        }

        .profile-top p {
            color: #999;
            margin-top: 5px;
        }

        .data {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 15px;
            border-bottom: 1px solid #333;
            padding: 14px 0;
        }

        .label {
            color: #d4af37;
            font-weight: bold;
        }

        .value {
            color: #ddd;
            word-break: break-word;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            text-decoration: none;
            padding: 13px 20px;
            border-radius: 9px;
            font-weight: bold;
        }

        .btn-edit {
            background: #d4af37;
            color: #111;
        }

        .btn-back {
            border: 1px solid #555;
            color: white;
        }

        @media (max-width: 600px) {

            body {
                padding: 15px;
            }

            .data {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>👤 Profil Saya</h1>

        <p>Informasi akun siswa ASTS</p>

    </div>


    <div class="card">

        <div class="profile-top">

            <div class="avatar">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>

            <h2>
                <?= htmlspecialchars($user['name']) ?>
            </h2>

            <p>
                @<?= htmlspecialchars($user['username']) ?>
            </p>

        </div>


        <div class="data">

            <div class="label">Username</div>

            <div class="value">
                <?= htmlspecialchars($user['username']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">Nama Lengkap</div>

            <div class="value">
                <?= htmlspecialchars($user['name']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">NISN</div>

            <div class="value">
                <?= htmlspecialchars($user['nisn']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">TTL</div>

            <div class="value">
                <?= htmlspecialchars($user['ttl']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">Jenis Kelamin</div>

            <div class="value">
                <?= htmlspecialchars($user['gender']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">Email</div>

            <div class="value">
                <?= htmlspecialchars($user['email']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">Nomor HP</div>

            <div class="value">
                <?= htmlspecialchars($user['no_hp']) ?>
            </div>

        </div>


        <div class="data">

            <div class="label">Alamat</div>

            <div class="value">
                <?= nl2br(htmlspecialchars($user['address'])) ?>
            </div>

        </div>


        <div class="buttons">

            <a
                href="edit_profil.php"
                class="btn btn-edit"
            >
                ✏️ Edit Profil
            </a>

            <a
                href="dashboard_siswa.php"
                class="btn btn-back"
            >
                ← Kembali
            </a>

        </div>

    </div>

</div>

</body>

</html>