<?php

session_start();

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$total = 0;
$laki = 0;
$perempuan = 0;
$terbaru = [];

/* =========================
   DATA STATISTIK
========================= */

$queryTotal = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

if ($queryTotal) {
    $row = mysqli_fetch_assoc($queryTotal);
    $total = (int) $row['total'];
}

$queryLaki = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE gender = 'MALE'"
);

if ($queryLaki) {
    $row = mysqli_fetch_assoc($queryLaki);
    $laki = (int) $row['total'];
}

$queryPerempuan = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE gender = 'FEMALE'"
);

if ($queryPerempuan) {
    $row = mysqli_fetch_assoc($queryPerempuan);
    $perempuan = (int) $row['total'];
}

/* =========================
   SISWA TERBARU
========================= */

$queryTerbaru = mysqli_query(
    $conn,
    "SELECT * FROM users ORDER BY id DESC LIMIT 5"
);

if ($queryTerbaru) {
    while ($row = mysqli_fetch_assoc($queryTerbaru)) {
        $terbaru[] = $row;
    }
}

/* =========================
   ADMIN LOGIN
========================= */

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | ASTS Luxury Admin</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold: #d4af37;
            --gold-light: #f5d77a;
            --gold-dark: #9b7618;
            --black: #070707;
            --dark: #0d0d0f;
            --dark2: #131315;
            --text: #f5f5f5;
            --muted: #8e8e94;
            --border: rgba(212,175,55,.18);
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;

            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(212,175,55,.10),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 90% 90%,
                    rgba(212,175,55,.08),
                    transparent 30%
                ),
                #070707;

            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
           BACKGROUND
        ========================= */

        .background {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            animation: floating 11s ease-in-out infinite;
        }

        .orb1 {
            width: 330px;
            height: 330px;
            background: #d4af37;
            top: -150px;
            left: 15%;
            opacity: .12;
        }

        .orb2 {
            width: 280px;
            height: 280px;
            background: #8b6914;
            right: -100px;
            bottom: 5%;
            opacity: .13;
            animation-delay: -4s;
        }

        .orb3 {
            width: 160px;
            height: 160px;
            background: #f5d77a;
            left: 45%;
            top: 45%;
            opacity: .05;
            animation-delay: -7s;
        }

        @keyframes floating {

            0%, 100% {
                transform: translate(0,0) scale(1);
            }

            50% {
                transform: translate(40px,-35px) scale(1.08);
            }

        }

        /* =========================
           LAYOUT
        ========================= */

        .app {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 270px;
            position: fixed;
            inset: 0 auto 0 0;

            background: rgba(10,10,11,.92);

            border-right: 1px solid var(--border);

            backdrop-filter: blur(20px);

            padding: 28px 18px;

            animation: sidebarIn .7s ease;

            z-index: 10;
        }

        @keyframes sidebarIn {

            from {
                opacity: 0;
                transform: translateX(-35px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }

        }

        /* =========================
           BRAND
        ========================= */

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;

            padding: 0 10px 28px;

            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .brand-icon {
            width: 47px;
            height: 47px;

            border-radius: 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #f5d77a,
                    #a77c18
                );

            color: #080808;

            font-size: 23px;
            font-weight: 900;

            box-shadow:
                0 0 25px rgba(212,175,55,.25),
                inset 0 1px rgba(255,255,255,.5);

            animation: logoGlow 3s infinite;
        }

        @keyframes logoGlow {

            0%,100% {
                box-shadow:
                    0 0 20px rgba(212,175,55,.2);
            }

            50% {
                box-shadow:
                    0 0 35px rgba(212,175,55,.45);
            }

        }

        .brand-text h2 {
            font-size: 17px;
            letter-spacing: 1px;
        }

        .brand-text span {
            color: var(--gold-light);
            font-size: 10px;
            letter-spacing: 2px;
        }

        /* =========================
           MENU
        ========================= */

        .menu-title {
            color: #5f5f64;
            font-size: 10px;
            letter-spacing: 2px;
            margin: 30px 12px 12px;
            text-transform: uppercase;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .nav a {
            position: relative;

            display: flex;
            align-items: center;
            gap: 13px;

            padding: 13px 14px;

            border-radius: 12px;

            color: #929298;

            text-decoration: none;

            font-size: 14px;

            transition: .3s;

            overflow: hidden;
        }

        .nav a:hover {
            color: #fff;

            transform: translateX(5px);

            background: rgba(212,175,55,.05);
        }

        .nav a.active {
            color: var(--gold-light);

            background: rgba(212,175,55,.10);

            border: 1px solid rgba(212,175,55,.15);
        }

        .nav-icon {
            width: 23px;
            text-align: center;
            font-size: 18px;
        }

        /* =========================
           ADMIN PROFILE
        ========================= */

        .admin-box {
            position: absolute;

            left: 18px;
            right: 18px;
            bottom: 100px;

            padding: 14px;

            border-radius: 14px;

            background: rgba(255,255,255,.025);

            border: 1px solid rgba(255,255,255,.07);

            display: flex;
            align-items: center;

            gap: 10px;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #f5d77a,
                    #a77c18
                );

            color: #080808;

            font-size: 14px;
            font-weight: 800;
        }

        .admin-info {
            min-width: 0;
            flex: 1;
        }

        .admin-info strong {
            display: block;

            font-size: 12px;

            color: #eee;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-info span {
            display: block;

            color: #666;

            font-size: 10px;

            margin-top: 3px;
        }

        /* =========================
           STATUS
        ========================= */

        .status {
            position: absolute;

            left: 18px;
            right: 18px;
            bottom: 25px;

            padding: 12px 15px;

            border-radius: 14px;

            border: 1px solid rgba(212,175,55,.14);

            background: rgba(212,175,55,.04);
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 8px;

            font-size: 12px;

            color: #aaa;
        }

        .online {
            width: 8px;
            height: 8px;

            border-radius: 50%;

            background: #69e6ad;

            box-shadow: 0 0 12px #69e6ad;

            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .45;
                transform: scale(.8);
            }

        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 270px;

            width: calc(100% - 270px);

            padding: 34px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 35px;

            animation: fadeUp .7s ease;
        }

        .header small {
            color: var(--gold-light);

            font-size: 11px;

            letter-spacing: 2px;

            text-transform: uppercase;
        }

        .header h1 {
            font-size: 34px;
            margin-top: 7px;
        }

        .header p {
            color: var(--muted);

            font-size: 14px;

            margin-top: 7px;
        }

        .date-badge {
            padding: 11px 16px;

            border-radius: 30px;

            border: 1px solid var(--border);

            color: var(--gold-light);

            background: rgba(212,175,55,.05);

            font-size: 12px;
        }

        /* =========================
           STATISTICS
        ========================= */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(3,1fr);

            gap: 18px;

            margin-bottom: 25px;
        }

        .stat {
            position: relative;

            overflow: hidden;

            padding: 23px;

            border-radius: 20px;

            border:
                1px solid rgba(255,255,255,.07);

            background:
                linear-gradient(
                    145deg,
                    rgba(255,255,255,.055),
                    rgba(255,255,255,.015)
                );

            backdrop-filter: blur(15px);

            animation:
                fadeUp .7s ease backwards;

            transition: .35s;
        }

        .stat:nth-child(2) {
            animation-delay: .1s;
        }

        .stat:nth-child(3) {
            animation-delay: .2s;
        }

        .stat:hover {
            transform: translateY(-6px);

            border-color:
                rgba(212,175,55,.3);

            box-shadow:
                0 20px 50px rgba(0,0,0,.3);
        }

        .stat::after {
            content: "";

            position: absolute;

            width: 110px;
            height: 110px;

            right: -40px;
            top: -40px;

            border-radius: 50%;

            background:
                rgba(212,175,55,.12);

            filter: blur(30px);
        }

        .stat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-label {
            color: #999;
            font-size: 13px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            color: var(--gold-light);

            background:
                rgba(212,175,55,.10);

            font-size: 18px;
        }

        .stat-number {
            font-size: 32px;

            font-weight: 700;

            margin-top: 15px;
        }

        /* =========================
           CONTENT
        ========================= */

        .grid {
            display: grid;

            grid-template-columns:
                1.5fr 1fr;

            gap: 20px;
        }

        .card {
            background:
                rgba(13,13,15,.82);

            border:
                1px solid rgba(255,255,255,.07);

            border-radius: 22px;

            backdrop-filter: blur(20px);

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0,0,0,.2);

            animation:
                fadeUp .8s ease .25s backwards;
        }

        .card-header {
            padding: 22px 24px;

            border-bottom:
                1px solid rgba(255,255,255,.06);

            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .card-header h2 {
            font-size: 17px;
        }

        .card-header span {
            color: var(--gold-light);
            font-size: 11px;
        }

        /* =========================
           STUDENT
        ========================= */

        .student {
            display: flex;

            align-items: center;

            gap: 13px;

            padding: 15px 24px;

            border-bottom:
                1px solid rgba(255,255,255,.045);

            transition: .3s;
        }

        .student:hover {
            background:
                rgba(212,175,55,.045);

            padding-left: 29px;
        }

        .avatar {
            width: 40px;
            height: 40px;

            border-radius: 12px;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    rgba(212,175,55,.2),
                    rgba(212,175,55,.05)
                );

            color: var(--gold-light);

            font-weight: 700;
        }

        .student-info {
            flex: 1;
        }

        .student-name {
            font-size: 13px;

            color: #eee;

            font-weight: 600;
        }

        .student-email {
            color: #666;

            font-size: 11px;

            margin-top: 4px;
        }

        .student-gender {
            color: #888;

            font-size: 10px;
        }

        /* =========================
           QUICK ACTION
        ========================= */

        .quick {
            padding: 24px;

            display: grid;

            gap: 12px;
        }

        .quick-link {
            display: flex;

            align-items: center;

            gap: 13px;

            padding: 16px;

            border-radius: 14px;

            text-decoration: none;

            color: #ddd;

            border:
                1px solid rgba(255,255,255,.07);

            background:
                rgba(255,255,255,.025);

            transition: .3s;
        }

        .quick-link:hover {
            transform: translateX(5px);

            border-color:
                rgba(212,175,55,.3);

            background:
                rgba(212,175,55,.06);
        }

        .quick-icon {
            width: 39px;
            height: 39px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background:
                rgba(212,175,55,.1);

            color: var(--gold-light);
        }

        .quick-text strong {
            display: block;

            font-size: 13px;
        }

        .quick-text span {
            display: block;

            color: #666;

            font-size: 10px;

            margin-top: 4px;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {
            padding: 45px;

            text-align: center;

            color: #666;

            font-size: 13px;
        }

        /* =========================
           ANIMATION
        ========================= */

        @keyframes fadeUp {

            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;

                width:
                    calc(100% - 220px);
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 760px) {

            .app {
                display: block;
            }

            .sidebar {
                position: relative;

                width: 100%;

                min-height: auto;

                border-right: none;

                border-bottom:
                    1px solid var(--border);
            }

            .admin-box,
            .status {
                position: static;

                margin-top: 15px;
            }

            .main {
                margin-left: 0;

                width: 100%;

                padding: 20px;
            }

            .date-badge {
                display: none;
            }

            .header h1 {
                font-size: 27px;
            }

        }

    </style>

</head>

<body>

<div class="background">

    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

</div>

<div class="app">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-icon">
                A
            </div>

            <div class="brand-text">

                <h2>ASTS ADMIN</h2>

                <span>LUXURY SYSTEM</span>

            </div>

        </div>

        <div class="menu-title">
            Main Menu
        </div>

        <nav class="nav">

            <a
                href="dashboard.php"
                class="active"
            >
                <span class="nav-icon">⌂</span>
                Dashboard
            </a>

            <a href="index.php">
                <span class="nav-icon">▦</span>
                Data Siswa
            </a>

            <a href="tambah.php">
                <span class="nav-icon">＋</span>
                Tambah Data
            </a>

            <a href="logout.php">
                <span class="nav-icon">↪</span>
                Logout
            </a>

        </nav>

        <!-- ADMIN LOGIN -->

        <div class="admin-box">

            <div class="admin-avatar">
                <?= strtoupper(substr($adminUsername, 0, 1)) ?>
            </div>

            <div class="admin-info">

                <strong>
                    <?= htmlspecialchars($adminUsername) ?>
                </strong>

                <span>
                    Administrator
                </span>

            </div>

        </div>

        <!-- STATUS -->

        <div class="status">

            <div class="status-row">

                <span class="online"></span>

                Sistem Online

            </div>

            <div
                style="
                    color:#555;
                    font-size:10px;
                    margin-top:7px;
                "
            >
                ASTS Student Management
            </div>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="main">

        <header class="header">

            <div>

                <small>
                    Overview
                </small>

                <h1>
                    Selamat Datang, <?= htmlspecialchars($adminUsername) ?> 👋
                </h1>

                <p>
                    Pantau dan kelola data siswa ASTS dengan mudah.
                </p>

            </div>

            <div class="date-badge">
                ✦ ASTS Luxury Admin
            </div>

        </header>


        <!-- STATISTICS -->

        <section class="stats">

            <div class="stat">

                <div class="stat-head">

                    <div class="stat-label">
                        Total Siswa
                    </div>

                    <div class="stat-icon">
                        ♙
                    </div>

                </div>

                <div
                    class="stat-number"
                    data-target="<?= $total ?>"
                >
                    0
                </div>

            </div>


            <div class="stat">

                <div class="stat-head">

                    <div class="stat-label">
                        Siswa Laki-laki
                    </div>

                    <div class="stat-icon">
                        ♂
                    </div>

                </div>

                <div
                    class="stat-number"
                    data-target="<?= $laki ?>"
                >
                    0
                </div>

            </div>


            <div class="stat">

                <div class="stat-head">

                    <div class="stat-label">
                        Siswa Perempuan
                    </div>

                    <div class="stat-icon">
                        ♀
                    </div>

                </div>

                <div
                    class="stat-number"
                    data-target="<?= $perempuan ?>"
                >
                    0
                </div>

            </div>

        </section>


        <!-- CONTENT -->

        <section class="grid">

            <!-- SISWA TERBARU -->

            <div class="card">

                <div class="card-header">

                    <div>

                        <h2>
                            Siswa Terbaru
                        </h2>

                        <span>
                            5 data terakhir
                        </span>

                    </div>

                    <span>
                        ✦ LIVE DATA
                    </span>

                </div>


                <?php if (count($terbaru) > 0): ?>

                    <?php foreach ($terbaru as $index => $siswa): ?>

                        <?php

                        $nama = $siswa['name'];

                        $inisial = strtoupper(
                            substr($nama, 0, 1)
                        );

                        ?>

                        <div
                            class="student"
                            style="
                                animation-delay:
                                <?= $index * 0.08 ?>s;
                            "
                        >

                            <div class="avatar">

                                <?= htmlspecialchars($inisial) ?>

                            </div>

                            <div class="student-info">

                                <div class="student-name">

                                    <?= htmlspecialchars(
                                        $siswa['name']
                                    ) ?>

                                </div>

                                <div class="student-email">

                                    <?= htmlspecialchars(
                                        $siswa['email']
                                    ) ?>

                                </div>

                            </div>

                            <div class="student-gender">

                                <?= $siswa['gender'] === 'MALE'
                                    ? 'LAKI-LAKI'
                                    : 'PEREMPUAN'
                                ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="empty">

                        Belum ada data siswa.

                    </div>

                <?php endif; ?>

            </div>


            <!-- QUICK ACTION -->

            <div class="card">

                <div class="card-header">

                    <div>

                        <h2>
                            Akses Cepat
                        </h2>

                        <span>
                            Kelola sistem
                        </span>

                    </div>

                </div>


                <div class="quick">

                    <a
                        href="index.php"
                        class="quick-link"
                    >

                        <div class="quick-icon">
                            ▦
                        </div>

                        <div class="quick-text">

                            <strong>
                                Lihat Data Siswa
                            </strong>

                            <span>
                                Kelola semua data siswa
                            </span>

                        </div>

                    </a>


                    <a
                        href="tambah.php"
                        class="quick-link"
                    >

                        <div class="quick-icon">
                            ＋
                        </div>

                        <div class="quick-text">

                            <strong>
                                Tambah Siswa
                            </strong>

                            <span>
                                Tambahkan data siswa baru
                            </span>

                        </div>

                    </a>


                    <a
                        href="index.php"
                        class="quick-link"
                    >

                        <div class="quick-icon">
                            ⌕
                        </div>

                        <div class="quick-text">

                            <strong>
                                Cari Siswa
                            </strong>

                            <span>
                                Temukan data dengan cepat
                            </span>

                        </div>

                    </a>


                </div>

            </div>

        </section>

    </main>

</div>


<script>

    /* =========================
       ANIMASI ANGKA
    ========================= */

    const numbers =
        document.querySelectorAll(".stat-number");

    numbers.forEach(element => {

        const target =
            Number(element.dataset.target);

        let current = 0;

        const duration = 900;

        const start = performance.now();

        function update(time) {

            const progress =
                Math.min(
                    (time - start) / duration,
                    1
                );

            const ease =
                1 - Math.pow(
                    1 - progress,
                    3
                );

            current =
                Math.floor(
                    target * ease
                );

            element.textContent = current;

            if (progress < 1) {

                requestAnimationFrame(update);

            } else {

                element.textContent = target;

            }

        }

        requestAnimationFrame(update);

    });

</script>

</body>

</html>