<?php

session_start();
require_once "koneksi.php";

// ===============================
// CEK LOGIN SISWA
// ===============================

if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true) {
    header("Location: login.php");
    exit;
}


// ===============================
// AMBIL ID USER
// ===============================

$user_id = $_SESSION['user_id'];

$query = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id = ? LIMIT 1"
);

mysqli_stmt_bind_param(
    $query,
    "i",
    $user_id
);

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

if (!$result || mysqli_num_rows($result) !== 1) {

    session_destroy();

    header("Location: login.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Siswa - ASTS</title>

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
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #151515;
            border-right: 1px solid #333;
            padding: 25px 18px;
        }

        .logo {
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 1px solid #333;
            margin-bottom: 25px;
        }

        .logo h2 {
            color: #d4af37;
            font-size: 27px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #777;
            font-size: 12px;
        }

        .menu-title {
            color: #666;
            font-size: 11px;
            font-weight: bold;
            margin: 20px 12px 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #aaa;
            padding: 13px 15px;
            margin-bottom: 7px;
            border-radius: 9px;
            transition: 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            background: rgba(212, 175, 55, 0.12);
            color: #d4af37;
        }

        .menu a.logout {
            color: #e57373;
        }

        .menu a.logout:hover {
            background: rgba(220, 70, 70, 0.1);
        }


        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 250px;
            padding: 35px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 28px;
            color: #fff;
        }

        .topbar h1 span {
            color: #d4af37;
        }

        .welcome {
            color: #888;
            margin-top: 7px;
        }

        .user-badge {
            padding: 10px 16px;
            border: 1px solid #444;
            border-radius: 30px;
            color: #d4af37;
            background: #151515;
        }


        /* =========================
           WELCOME CARD
        ========================= */

        .welcome-card {
            background:
                linear-gradient(
                    135deg,
                    #191919,
                    #101010
                );

            border: 1px solid #3b331d;
            border-radius: 18px;
            padding: 28px;
            margin-bottom: 25px;
        }

        .welcome-card h2 {
            color: #d4af37;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #aaa;
            line-height: 1.6;
        }


        /* =========================
           STAT CARDS
        ========================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #151515;
            border: 1px solid #333;
            border-radius: 15px;
            padding: 22px;
        }

        .stat-card .label {
            color: #777;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-card .value {
            color: #d4af37;
            font-size: 20px;
            font-weight: bold;
            word-break: break-word;
        }


        /* =========================
           PROFILE
        ========================= */

        .profile-card {
            background: #151515;
            border: 1px solid #333;
            border-radius: 18px;
            padding: 28px;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .profile-header h2 {
            color: #fff;
        }

        .edit-button {
            display: inline-block;
            padding: 10px 16px;
            background: #d4af37;
            color: #111;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
        }

        .edit-button:hover {
            background: #f0c94d;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .profile-item {
            background: #202020;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            padding: 17px;
        }

        .profile-item.full {
            grid-column: 1 / -1;
        }

        .profile-item .label {
            display: block;
            color: #777;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .profile-item .value {
            color: #eee;
            font-size: 15px;
            word-break: break-word;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 210px;
            }

            .main {
                margin-left: 210px;
                padding: 25px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 700px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #333;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .topbar {
                display: block;
            }

            .user-badge {
                display: inline-block;
                margin-top: 15px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .profile-item.full {
                grid-column: auto;
            }

        }

    </style>

</head>

<body>


<!-- =========================
     SIDEBAR
========================= -->

<aside class="sidebar">

    <div class="logo">

        <h2>ASTS</h2>

        <p>Portal Siswa</p>

    </div>


    <div class="menu-title">
        Menu Utama
    </div>


    <div class="menu">

        <a
            href="dashboard_siswa.php"
            class="active"
        >
            🏠 Dashboard
        </a>

        <a href="profil.php">
            👤 Profil Saya
        </a>

        
    </div>


    <div class="menu-title">
        Akun
    </div>


    <div class="menu">

        <a
            href="logout.php"
            class="logout"
            onclick="return confirm('Apakah kamu yakin ingin logout?')"
        >
            🚪 Logout
        </a>

    </div>

</aside>


<!-- =========================
     MAIN CONTENT
========================= -->

<main class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>

            <h1>
                Dashboard <span>Siswa</span>
            </h1>

            <p class="welcome">
                Selamat datang di sistem ASTS.
            </p>

        </div>


        <div class="user-badge">

            <?= htmlspecialchars($user['name']) ?>

        </div>

    </div>


    <!-- WELCOME -->

    <div class="welcome-card">

        <h2>
            Selamat Datang, <?= htmlspecialchars($user['name']) ?> 👋
        </h2>

        <p>
            Kamu berhasil login ke akun siswa.
            Di halaman ini kamu dapat melihat data profil
            dan mengelola informasi akunmu.
        </p>

    </div>


    <!-- STAT -->

    <div class="stats">

        <div class="stat-card">

            <div class="label">
                Nama Siswa
            </div>

            <div class="value">
                <?= htmlspecialchars($user['name']) ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="label">
                NISN
            </div>

            <div class="value">
                <?= htmlspecialchars($user['nisn']) ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="label">
                Status Akun
            </div>

            <div class="value">
                AKTIF
            </div>

        </div>

    </div>


    <!-- PROFILE -->

    <div class="profile-card">

        <div class="profile-header">

            <h2>
                Informasi Profil
            </h2>

            <a
                href="edit_profil.php"
                class="edit-button"
            >
                ✏️ Edit Profil
            </a>

        </div>


        <div class="profile-grid">


            <div class="profile-item">

                <span class="label">
                    Nama Lengkap
                </span>

                <div class="value">
                    <?= htmlspecialchars($user['name']) ?>
                </div>

            </div>


            <div class="profile-item">

                <span class="label">
                    NISN
                </span>

                <div class="value">
                    <?= htmlspecialchars($user['nisn']) ?>
                </div>

            </div>


            <div class="profile-item">

                <span class="label">
                    Tempat, Tanggal Lahir
                </span>

                <div class="value">
                    <?= htmlspecialchars($user['ttl']) ?>
                </div>

            </div>


            <div class="profile-item">

                <span class="label">
                    Jenis Kelamin
                </span>

                <div class="value">
                    <?= htmlspecialchars($user['gender']) ?>
                </div>

            </div>


            <div class="profile-item">

                <span class="label">
                    Email
                </span>

                <div class="value">
                    <?= htmlspecialchars($user['email']) ?>
                </div>

            </div>


            <div class="profile-item">

                <span class="label">
                    Nomor WhatsApp
                </span>

                <div class="value">
                    <?= htmlspecialchars($user['no_hp']) ?>
                </div>

            </div>


            <div class="profile-item full">

                <span class="label">
                    Alamat
                </span>

                <div class="value">
                    <?= nl2br(htmlspecialchars($user['address'])) ?>
                </div>

            </div>

        </div>

    </div>

</main>


</body>

</html>