<?php

session_start();

if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

/* =========================
   DATA STATISTIK
========================= */

$total = 0;
$laki = 0;
$perempuan = 0;

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
    "SELECT COUNT(*) AS total
     FROM users
     WHERE gender = 'Laki-laki'"
);

if ($queryLaki) {
    $row = mysqli_fetch_assoc($queryLaki);
    $laki = (int) $row['total'];
}

$queryPerempuan = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE gender = 'Perempuan'"
);

if ($queryPerempuan) {
    $row = mysqli_fetch_assoc($queryPerempuan);
    $perempuan = (int) $row['total'];
}

/* =========================
   DATA SISWA
========================= */

$querySiswa = mysqli_query(
    $conn,
    "SELECT *
     FROM users
     ORDER BY id DESC"
);

$siswa = [];

if ($querySiswa) {
    while ($row = mysqli_fetch_assoc($querySiswa)) {
        $siswa[] = $row;
    }
}

/* =========================
   ADMIN
========================= */

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Data Siswa | ASTS Luxury Admin</title>

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

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

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

            animation:
                floating
                11s
                ease-in-out
                infinite;
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

            0%,
            100% {
                transform:
                    translate(0,0)
                    scale(1);
            }

            50% {
                transform:
                    translate(40px,-35px)
                    scale(1.08);
            }

        }

        /* =========================
           APP
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

            background:
                rgba(10,10,11,.94);

            border-right:
                1px solid var(--border);

            backdrop-filter: blur(20px);

            padding: 28px 18px;

            z-index: 10;
        }

        /* =========================
           BRAND
        ========================= */

        .brand {

            display: flex;

            align-items: center;

            gap: 13px;

            padding:
                0 10px 28px;

            border-bottom:
                1px solid
                rgba(255,255,255,.06);
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
                0 0 25px
                rgba(212,175,55,.25);
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

            margin:
                30px 12px 12px;

            text-transform: uppercase;
        }

        .nav {

            display: flex;

            flex-direction: column;

            gap: 7px;
        }

        .nav a {

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 13px 14px;

            border-radius: 12px;

            color: #929298;

            text-decoration: none;

            font-size: 14px;

            transition: .3s;
        }

        .nav a:hover {

            color: #fff;

            transform:
                translateX(5px);

            background:
                rgba(212,175,55,.05);
        }

        .nav a.active {

            color: var(--gold-light);

            background:
                rgba(212,175,55,.10);

            border:
                1px solid
                rgba(212,175,55,.15);
        }

        .nav-icon {

            width: 23px;

            text-align: center;

            font-size: 18px;
        }

        /* =========================
           ADMIN
        ========================= */

        .admin-box {

            position: absolute;

            left: 18px;
            right: 18px;

            bottom: 100px;

            padding: 14px;

            border-radius: 14px;

            background:
                rgba(255,255,255,.025);

            border:
                1px solid
                rgba(255,255,255,.07);

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

            border:
                1px solid
                rgba(212,175,55,.14);

            background:
                rgba(212,175,55,.04);
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

            box-shadow:
                0 0 12px
                #69e6ad;
        }

        /* =========================
           MAIN
        ========================= */

        .main {

            margin-left: 270px;

            width:
                calc(100% - 270px);

            padding: 34px;
        }

        /* =========================
           HEADER
        ========================= */

        .header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 30px;
        }

        .header small {

            color:
                var(--gold-light);

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

            border:
                1px solid
                var(--border);

            color:
                var(--gold-light);

            background:
                rgba(212,175,55,.05);

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
                1px solid
                rgba(255,255,255,.07);

            background:
                linear-gradient(
                    145deg,
                    rgba(255,255,255,.055),
                    rgba(255,255,255,.015)
                );

            backdrop-filter: blur(15px);

            transition: .35s;
        }

        .stat:hover {

            transform:
                translateY(-6px);

            border-color:
                rgba(212,175,55,.3);

            box-shadow:
                0 20px 50px
                rgba(0,0,0,.3);
        }

        .stat-head {

            display: flex;

            align-items: center;

            justify-content:
                space-between;
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

            color:
                var(--gold-light);

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
           DATA CARD
        ========================= */

        .card {

            background:
                rgba(13,13,15,.88);

            border:
                1px solid
                rgba(255,255,255,.07);

            border-radius: 22px;

            backdrop-filter: blur(20px);

            overflow: hidden;

            box-shadow:
                0 25px 70px
                rgba(0,0,0,.2);
        }

        .card-header {

            padding: 22px 24px;

            border-bottom:
                1px solid
                rgba(255,255,255,.06);

            display: flex;

            align-items: center;

            justify-content:
                space-between;
        }

        .card-header h2 {

            font-size: 18px;
        }

        .card-header span {

            color:
                var(--gold-light);

            font-size: 11px;
        }

        /* =========================
           BUTTON TAMBAH
        ========================= */

        .btn-add {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 11px 16px;

            border-radius: 10px;

            text-decoration: none;

            color: #080808;

            background:
                linear-gradient(
                    135deg,
                    #f5d77a,
                    #b88a1b
                );

            font-weight: 700;

            font-size: 12px;

            transition: .3s;
        }

        .btn-add:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 8px 25px
                rgba(212,175,55,.25);
        }

        /* =========================
           TABLE
        ========================= */

        .table-wrapper {

            width: 100%;

            overflow-x: auto;

            padding: 5px 20px 20px;
        }

        table {

            width: 100%;

            border-collapse:
                collapse;

            min-width: 950px;
        }

        th {

            padding: 16px 12px;

            text-align: left;

            color:
                var(--gold-light);

            font-size: 11px;

            letter-spacing: 1px;

            text-transform:
                uppercase;

            border-bottom:
                1px solid
                rgba(212,175,55,.18);
        }

        td {

            padding: 15px 12px;

            color: #d5d5d5;

            font-size: 13px;

            border-bottom:
                1px solid
                rgba(255,255,255,.045);
        }

        tbody tr {

            transition: .25s;
        }

        tbody tr:hover {

            background:
                rgba(212,175,55,.045);
        }

        .number {

            color: #777;

            width: 50px;
        }

        .name-cell {

            display: flex;

            align-items: center;

            gap: 10px;
        }

        .avatar {

            width: 38px;
            height: 38px;

            flex-shrink: 0;

            border-radius: 11px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    rgba(212,175,55,.22),
                    rgba(212,175,55,.05)
                );

            color:
                var(--gold-light);

            font-weight: 700;
        }

        .name-text strong {

            display: block;

            color: #eee;

            font-size: 13px;
        }

        .name-text span {

            display: block;

            color: #666;

            font-size: 10px;

            margin-top: 3px;
        }

        .gender {

            display: inline-block;

            padding: 5px 9px;

            border-radius: 20px;

            background:
                rgba(212,175,55,.08);

            color:
                var(--gold-light);

            font-size: 10px;
        }

        .action {

            display: flex;

            gap: 7px;
        }

        .btn-edit,
        .btn-delete {

            padding: 7px 10px;

            border-radius: 7px;

            text-decoration: none;

            font-size: 11px;

            font-weight: 600;

            transition: .2s;
        }

        .btn-edit {

            color: #080808;

            background:
                var(--gold-light);
        }

        .btn-delete {

            color: #fff;

            background:
                rgba(220,38,38,.75);
        }

        .btn-edit:hover,
        .btn-delete:hover {

            transform:
                translateY(-2px);
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {

            text-align: center;

            padding: 60px 20px;

            color: #666;

            font-size: 14px;
        }

        .empty-icon {

            font-size: 42px;

            color:
                rgba(212,175,55,.35);

            margin-bottom: 12px;
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

                padding: 25px;
            }

            .stats {

                grid-template-columns:
                    1fr;
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
                    1px solid
                    var(--border);
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

            .header {

                display: block;
            }

            .date-badge {

                display: none;
            }

            .header h1 {

                font-size: 27px;
            }

            .card-header {

                gap: 15px;

                align-items: flex-start;
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

                <span>
                    LUXURY SYSTEM
                </span>

            </div>

        </div>


        <div class="menu-title">
            Main Menu
        </div>


        <nav class="nav">

            <a href="dashboard.php">

                <span class="nav-icon">
                    ⌂
                </span>

                Dashboard

            </a>


            <a
                href="index.php"
                class="active"
            >

                <span class="nav-icon">
                    ▦
                </span>

                Data Siswa

            </a>


            <a href="tambah.php">

                <span class="nav-icon">
                    ＋
                </span>

                Tambah Data

            </a>


            <a href="logout.php">

                <span class="nav-icon">
                    ↪
                </span>

                Logout

            </a>

        </nav>


        <!-- ADMIN -->

        <div class="admin-box">

            <div class="admin-avatar">

                <?= strtoupper(
                    substr($adminUsername, 0, 1)
                ) ?>

            </div>

            <div class="admin-info">

                <strong>

                    <?= htmlspecialchars(
                        $adminUsername
                    ) ?>

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

        <!-- HEADER -->

        <header class="header">

            <div>

                <small>
                    Student Management
                </small>

                <h1>
                    Data Siswa
                </h1>

                <p>
                    Kelola seluruh data siswa ASTS
                    dengan mudah.
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


        <!-- DATA SISWA -->

        <section class="card">

            <div class="card-header">

                <div>

                    <h2>
                        Semua Data Siswa
                    </h2>

                    <span>
                        <?= $total ?> siswa terdaftar
                    </span>

                </div>


                <a
                    href="tambah.php"
                    class="btn-add"
                >

                    ＋ Tambah Siswa

                </a>

            </div>


            <div class="table-wrapper">

                <?php if (count($siswa) > 0): ?>

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    No
                                </th>

                                <th>
                                    Nama Siswa
                                </th>

                                <th>
                                    NISN
                                </th>

                                <th>
                                    TTL
                                </th>

                                <th>
                                    Jenis Kelamin
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Alamat
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php

                        $no = 1;

                        foreach ($siswa as $row):

                            $nama =
                                $row['name'];

                            $inisial =
                                strtoupper(
                                    substr(
                                        $nama,
                                        0,
                                        1
                                    )
                                );

                        ?>

                            <tr>

                                <td class="number">

                                    <?= $no++ ?>

                                </td>


                                <td>

                                    <div class="name-cell">

                                        <div class="avatar">

                                            <?= htmlspecialchars(
                                                $inisial
                                            ) ?>

                                        </div>

                                        <div class="name-text">

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['name']
                                                ) ?>

                                            </strong>

                                            <span>
                                                Siswa ASTS
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $row['nisn']
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $row['ttl']
                                    ) ?>

                                </td>


                                <td>

                                    <span class="gender">

                                        <?= htmlspecialchars(
                                            $row['gender']
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $row['email']
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $row['address']
                                    ) ?>

                                </td>


                                <td>

                                    <div class="action">

                                        <a
                                            href="edit.php?id=<?= $row['id'] ?>"
                                            class="btn-edit"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="hapus.php?id=<?= $row['id'] ?>"
                                            class="btn-delete"
                                            onclick="return confirm('Yakin ingin menghapus data siswa ini?')"
                                        >
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php else: ?>

                    <div class="empty">

                        <div class="empty-icon">
                            ♙
                        </div>

                        Belum ada data siswa.

                        <br>

                        Silakan tambahkan
                        data siswa baru.

                    </div>

                <?php endif; ?>

            </div>

        </section>

    </main>

</div>


<script>

    /* =========================
       ANIMASI ANGKA
    ========================= */

    const numbers =
        document.querySelectorAll(
            ".stat-number"
        );

    numbers.forEach(element => {

        const target =
            Number(
                element.dataset.target
            );

        const duration = 900;

        const start =
            performance.now();

        function update(time) {

            const progress =
                Math.min(
                    (time - start) /
                    duration,
                    1
                );

            const ease =
                1 -
                Math.pow(
                    1 - progress,
                    3
                );

            const current =
                Math.floor(
                    target * ease
                );

            element.textContent =
                current;

            if (progress < 1) {

                requestAnimationFrame(
                    update
                );

            } else {

                element.textContent =
                    target;

            }

        }

        requestAnimationFrame(update);

    });

</script>

</body>

</html>