<?php

include "koneksi.php";

$error = "";

$name = "";
$nisn = "";
$ttl = "";
$gender = "";
$email = "";
$address = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $nisn = trim($_POST["nisn"] ?? "");
    $ttl = trim($_POST["ttl"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");

    if (
        $name === "" ||
        $nisn === "" ||
        $ttl === "" ||
        $gender === "" ||
        $email === "" ||
        $address === ""
    ) {

        $error = "Semua field wajib diisi.";

    } elseif (!preg_match('/^[0-9]{10}$/', $nisn)) {

        $error = "NISN harus terdiri dari tepat 10 angka.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users
            (name, nisn, ttl, gender, email, address)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $name,
            $nisn,
            $ttl,
            $gender,
            $email,
            $address
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit;

        } else {

            $error = "Gagal menyimpan data: " . mysqli_error($conn);

        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Siswa | ASTS Luxury Admin</title>

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
    --black: #050505;
    --dark: #0c0c0e;
    --dark-card: #111113;
    --text: #f5f5f5;
    --muted: #89898f;
    --border: rgba(212,175,55,0.18);
}

body {
    font-family: "Segoe UI", Arial, sans-serif;
    min-height: 100vh;
    color: var(--text);
    background:
        radial-gradient(
            circle at 10% 10%,
            rgba(212,175,55,0.10),
            transparent 28%
        ),
        radial-gradient(
            circle at 90% 90%,
            rgba(212,175,55,0.08),
            transparent 30%
        ),
        #050505;
    overflow-x: hidden;
}

/* =========================================
   ANIMATED BACKGROUND
========================================= */

.background {
    position: fixed;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    animation: floatOrb 10s ease-in-out infinite;
}

.orb.one {
    width: 350px;
    height: 350px;
    background: #d4af37;
    top: -170px;
    left: 15%;
    opacity: 0.10;
}

.orb.two {
    width: 300px;
    height: 300px;
    background: #8d6914;
    right: -130px;
    bottom: -80px;
    opacity: 0.12;
    animation-delay: -4s;
}

.orb.three {
    width: 180px;
    height: 180px;
    background: #f5d77a;
    top: 45%;
    right: 30%;
    opacity: 0.04;
    animation-delay: -7s;
}

@keyframes floatOrb {

    0%, 100% {
        transform: translate(0, 0) scale(1);
    }

    50% {
        transform: translate(40px, -35px) scale(1.08);
    }

}

.star {
    position: absolute;
    color: var(--gold-light);
    opacity: 0.25;
    animation: sparkle 4s ease-in-out infinite;
}

.star.one {
    top: 18%;
    left: 40%;
}

.star.two {
    top: 75%;
    left: 12%;
    animation-delay: -2s;
}

.star.three {
    top: 28%;
    right: 10%;
    animation-delay: -1s;
}

@keyframes sparkle {

    0%, 100% {
        opacity: 0.1;
        transform: scale(0.8) rotate(0deg);
    }

    50% {
        opacity: 0.8;
        transform: scale(1.3) rotate(180deg);
    }

}

/* =========================================
   APP
========================================= */

.app {
    position: relative;
    z-index: 1;
    min-height: 100vh;
}

/* =========================================
   SIDEBAR
========================================= */

.sidebar {
    width: 270px;
    position: fixed;
    inset: 0 auto 0 0;
    padding: 28px 18px;
    background: rgba(9,9,10,0.93);
    border-right: 1px solid var(--border);
    backdrop-filter: blur(20px);
    z-index: 10;
    animation: sidebarIn 0.7s ease;
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

/* BRAND */

.brand {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 0 10px 28px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.logo {
    width: 48px;
    height: 48px;
    border-radius: 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #f6da78,
            #a77b18
        );

    color: #080808;
    font-size: 23px;
    font-weight: 900;

    box-shadow:
        0 0 20px rgba(212,175,55,0.25),
        inset 0 1px rgba(255,255,255,0.6);

    animation: logoGlow 3s ease-in-out infinite;
}

@keyframes logoGlow {

    0%, 100% {
        box-shadow:
            0 0 20px rgba(212,175,55,0.20);
    }

    50% {
        box-shadow:
            0 0 38px rgba(212,175,55,0.45);
    }

}

.brand h2 {
    font-size: 17px;
    letter-spacing: 1px;
}

.brand span {
    display: block;
    margin-top: 2px;
    color: var(--gold-light);
    font-size: 10px;
    letter-spacing: 2px;
}

/* MENU */

.menu-title {
    margin: 30px 12px 12px;
    color: #606066;
    font-size: 10px;
    letter-spacing: 2px;
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

    transition: all 0.3s ease;
    overflow: hidden;
}

.nav a::before {
    content: "";

    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            90deg,
            rgba(212,175,55,0.12),
            transparent
        );

    opacity: 0;
    transition: 0.3s;
}

.nav a:hover {
    color: white;
    transform: translateX(5px);
}

.nav a:hover::before {
    opacity: 1;
}

.nav a.active {
    color: var(--gold-light);

    background:
        linear-gradient(
            90deg,
            rgba(212,175,55,0.12),
            rgba(212,175,55,0.03)
        );

    border: 1px solid rgba(212,175,55,0.18);

    box-shadow:
        inset 3px 0 var(--gold);
}

.nav-icon {
    width: 23px;
    text-align: center;
    font-size: 18px;
}

/* STATUS */

.status {
    position: absolute;
    left: 18px;
    right: 18px;
    bottom: 25px;

    padding: 15px;

    border-radius: 14px;

    background: rgba(212,175,55,0.04);
    border: 1px solid rgba(212,175,55,0.14);
}

.status-row {
    display: flex;
    align-items: center;

    color: #aaa;
    font-size: 12px;
}

.online {
    width: 8px;
    height: 8px;

    margin-right: 8px;

    border-radius: 50%;

    background: #69e6ad;

    box-shadow: 0 0 12px #69e6ad;

    animation: pulse 2s infinite;
}

@keyframes pulse {

    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: .4;
        transform: scale(.8);
    }

}

/* =========================================
   MAIN
========================================= */

.main {
    margin-left: 270px;
    width: calc(100% - 270px);
    padding: 34px;
}

.header {
    margin-bottom: 25px;
    animation: fadeUp .7s ease;
}

.header small {
    color: var(--gold-light);
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.header h1 {
    margin-top: 7px;
    font-size: 32px;
}

.header p {
    margin-top: 7px;
    color: var(--muted);
    font-size: 14px;
}

/* =========================================
   FORM CARD
========================================= */

.form-card {
    max-width: 1000px;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,0.055),
            rgba(255,255,255,0.015)
        );

    border: 1px solid rgba(255,255,255,0.08);

    border-radius: 24px;

    overflow: hidden;

    backdrop-filter: blur(22px);

    box-shadow:
        0 30px 90px rgba(0,0,0,.35);

    animation:
        fadeUp .8s ease .1s backwards;
}

/* CARD HEADER */

.form-header {
    position: relative;

    padding: 25px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    border-bottom:
        1px solid rgba(255,255,255,.07);
}

.form-header::after {
    content: "";

    position: absolute;

    left: 0;
    bottom: -1px;

    width: 130px;
    height: 1px;

    background:
        linear-gradient(
            90deg,
            var(--gold),
            transparent
        );
}

.form-title {
    display: flex;
    align-items: center;
    gap: 14px;
}

.form-icon {
    width: 44px;
    height: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: var(--gold-light);

    background: rgba(212,175,55,.09);

    border: 1px solid rgba(212,175,55,.12);

    font-size: 20px;
}

.form-header h2 {
    font-size: 18px;
}

.form-header p {
    margin-top: 4px;
    color: #666;
    font-size: 11px;
}

.badge {
    padding: 8px 13px;

    border-radius: 30px;

    color: var(--gold-light);

    background: rgba(212,175,55,.06);

    border: 1px solid var(--border);

    font-size: 10px;
    letter-spacing: 1px;
}

/* FORM BODY */

.form-body {
    padding: 30px;
}

.error {
    display: flex;
    align-items: center;
    gap: 9px;

    padding: 14px 16px;

    margin-bottom: 22px;

    border-radius: 12px;

    color: #ffabab;

    background: rgba(255,70,70,.07);

    border: 1px solid rgba(255,70,70,.17);

    font-size: 13px;

    animation: shake .4s ease;
}

@keyframes shake {

    0%,100% {
        transform: translateX(0);
    }

    25% {
        transform: translateX(-5px);
    }

    75% {
        transform: translateX(5px);
    }

}

/* GRID */

.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 22px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group {
    animation:
        fadeUp .6s ease backwards;
}

.form-group:nth-child(1) {
    animation-delay: .05s;
}

.form-group:nth-child(2) {
    animation-delay: .10s;
}

.form-group:nth-child(3) {
    animation-delay: .15s;
}

.form-group:nth-child(4) {
    animation-delay: .20s;
}

.form-group:nth-child(5) {
    animation-delay: .25s;
}

.form-group:nth-child(6) {
    animation-delay: .30s;
}

label {
    display: flex;
    align-items: center;
    gap: 6px;

    margin-bottom: 8px;

    color: #aaa;

    font-size: 12px;
    font-weight: 500;
}

label::before {
    content: "";

    width: 3px;
    height: 12px;

    border-radius: 5px;

    background: var(--gold);
}

/* INPUT */

input,
select,
textarea {
    width: 100%;

    padding: 14px 15px;

    color: #eee;

    background:
        rgba(255,255,255,.035);

    border:
        1px solid rgba(255,255,255,.09);

    border-radius: 12px;

    outline: none;

    font-family: inherit;
    font-size: 13px;

    transition:
        border .3s ease,
        box-shadow .3s ease,
        transform .3s ease,
        background .3s ease;
}

input:hover,
select:hover,
textarea:hover {
    border-color:
        rgba(212,175,55,.25);
}

input:focus,
select:focus,
textarea:focus {
    border-color:
        rgba(212,175,55,.65);

    background:
        rgba(212,175,55,.035);

    box-shadow:
        0 0 0 3px
        rgba(212,175,55,.06),
        0 0 25px
        rgba(212,175,55,.06);

    transform:
        translateY(-1px);
}

input::placeholder,
textarea::placeholder {
    color: #555;
}

select {
    cursor: pointer;
}

select option {
    background: #151515;
    color: #fff;
}

textarea {
    min-height: 120px;
    resize: vertical;
}

.helper {
    margin-top: 7px;

    color: #555;

    font-size: 10px;

    display: flex;
    justify-content: space-between;
}

#nisnCount {
    color: var(--gold-light);
}

/* ACTION */

.actions {
    display: flex;
    justify-content: flex-end;
    gap: 11px;

    margin-top: 28px;
    padding-top: 24px;

    border-top:
        1px solid rgba(255,255,255,.06);
}

.btn {
    min-width: 145px;

    padding: 13px 20px;

    border-radius: 11px;

    font-family: inherit;

    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    text-align: center;

    cursor: pointer;

    transition: all .3s ease;
}

/* CANCEL */

.btn-cancel {
    color: #aaa;

    background:
        rgba(255,255,255,.035);

    border:
        1px solid rgba(255,255,255,.09);
}

.btn-cancel:hover {
    color: #fff;

    background:
        rgba(255,255,255,.07);

    transform:
        translateY(-2px);
}

/* SAVE */

.btn-save {
    color: #101010;

    border: none;

    background:
        linear-gradient(
            135deg,
            #f6d978,
            #a97d18
        );

    box-shadow:
        0 8px 25px
        rgba(212,175,55,.15);
}

.btn-save:hover {
    transform:
        translateY(-3px);

    box-shadow:
        0 14px 35px
        rgba(212,175,55,.30);
}

.btn-save:active {
    transform:
        translateY(0)
        scale(.98);
}

.btn-save.loading {
    opacity: .7;
    pointer-events: none;
}

/* SPINNER */

.spinner {
    display: inline-block;

    width: 13px;
    height: 13px;

    margin-right: 7px;

    border:
        2px solid rgba(0,0,0,.25);

    border-top-color:
        #111;

    border-radius: 50%;

    animation:
        spin .7s linear infinite;

    vertical-align: -2px;
}

@keyframes spin {

    to {
        transform: rotate(360deg);
    }

}

/* =========================================
   ANIMATION
========================================= */

@keyframes fadeUp {

    from {
        opacity: 0;
        transform:
            translateY(22px);
    }

    to {
        opacity: 1;
        transform:
            translateY(0);
    }

}

/* =========================================
   RESPONSIVE
========================================= */

@media(max-width:850px) {

    .sidebar {
        width: 220px;
    }

    .main {
        margin-left: 220px;
        width: calc(100% - 220px);
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }

}

@media(max-width:700px) {

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

    .status {
        position: static;

        margin-top: 25px;
    }

    .main {
        margin-left: 0;

        width: 100%;

        padding: 20px;
    }

    .header h1 {
        font-size: 27px;
    }

    .form-header {
        align-items: flex-start;

        gap: 15px;
    }

    .badge {
        display: none;
    }

    .form-body {
        padding: 20px;
    }

    .actions {
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
    }

}

</style>

</head>

<body>

<div class="background">

    <div class="orb one"></div>
    <div class="orb two"></div>
    <div class="orb three"></div>

    <div class="star one">✦</div>
    <div class="star two">✧</div>
    <div class="star three">✦</div>

</div>


<div class="app">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            <div class="logo">
                A
            </div>

            <div>
                <h2>ASTS ADMIN</h2>
                <span>LUXURY SYSTEM</span>
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


            <a href="index.php">

                <span class="nav-icon">
                    ▦
                </span>

                Data Siswa

            </a>


            <a
                href="tambah.php"
                class="active"
            >

                <span class="nav-icon">
                    ＋
                </span>

                Tambah Data

            </a>

        </nav>


        <div class="status">

            <div class="status-row">

                <span class="online"></span>

                Sistem Online

            </div>

            <div style="
                color:#555;
                font-size:10px;
                margin-top:7px;
            ">
                ASTS Student Management
            </div>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="main">

        <div class="header">

            <small>
                Student Management
            </small>

            <h1>
                Tambah Siswa
            </h1>

            <p>
                Tambahkan data siswa baru ke dalam sistem.
            </p>

        </div>


        <!-- FORM -->

        <section class="form-card">

            <div class="form-header">

                <div class="form-title">

                    <div class="form-icon">
                        ＋
                    </div>

                    <div>

                        <h2>
                            Informasi Siswa
                        </h2>

                        <p>
                            Lengkapi informasi siswa dengan benar.
                        </p>

                    </div>

                </div>


                <div class="badge">
                    ✦ DATA BARU
                </div>

            </div>


            <div class="form-body">

                <?php if ($error): ?>

                    <div class="error">

                        <span>⚠</span>

                        <?= htmlspecialchars($error) ?>

                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    id="formSiswa"
                >

                    <div class="form-grid">


                        <!-- NAMA -->

                        <div class="form-group">

                            <label>
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="<?= htmlspecialchars($name) ?>"
                                placeholder="Masukkan nama lengkap"
                                autocomplete="name"
                                required
                            >

                        </div>


                        <!-- NISN -->

                        <div class="form-group">

                            <label>
                                NISN
                            </label>

                            <input
                                type="text"
                                name="nisn"
                                id="nisn"
                                value="<?= htmlspecialchars($nisn) ?>"
                                placeholder="Masukkan 10 angka"
                                minlength="10"
                                maxlength="10"
                                pattern="[0-9]{10}"
                                inputmode="numeric"
                                title="NISN harus terdiri dari tepat 10 angka"
                                required
                            >

                            <div class="helper">

                                <span>
                                    Harus tepat 10 angka.
                                </span>

                                <span id="nisnCount">
                                    0/10
                                </span>

                            </div>

                        </div>


                        <!-- TTL -->

                        <div class="form-group">

                            <label>
                                Tempat, Tanggal Lahir
                            </label>

                            <input
                                type="text"
                                name="ttl"
                                value="<?= htmlspecialchars($ttl) ?>"
                                placeholder="Contoh: Sumenep, 15 Mei 2009"
                                required
                            >

                        </div>


                        <!-- GENDER -->

                        <div class="form-group">

                            <label>
                                Jenis Kelamin
                            </label>

                            <select
                                name="gender"
                                required
                            >

                                <option value="">
                                    Pilih jenis kelamin
                                </option>

                                <option
                                    value="MALE"
                                    <?= $gender === "MALE" ? "selected" : "" ?>
                                >
                                    Laki-laki
                                </option>

                                <option
                                    value="FEMALE"
                                    <?= $gender === "FEMALE" ? "selected" : "" ?>
                                >
                                    Perempuan
                                </option>

                            </select>

                        </div>


                        <!-- EMAIL -->

                        <div class="form-group full">

                            <label>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($email) ?>"
                                placeholder="contoh@email.com"
                                autocomplete="email"
                                required
                            >

                        </div>


                        <!-- ALAMAT -->

                        <div class="form-group full">

                            <label>
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                placeholder="Masukkan alamat lengkap siswa"
                                required
                            ><?= htmlspecialchars($address) ?></textarea>

                        </div>


                    </div>


                    <!-- BUTTON -->

                    <div class="actions">

                        <a
                            href="index.php"
                            class="btn btn-cancel"
                        >
                            ← Kembali
                        </a>


                        <button
                            type="submit"
                            class="btn btn-save"
                            id="saveButton"
                        >
                            ✦ Simpan Data
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>


<script>

/* =========================================
   NISN COUNTER
========================================= */

const nisn =
    document.getElementById("nisn");

const nisnCount =
    document.getElementById("nisnCount");


function updateNisnCounter() {

    if (!nisn) return;

    nisnCount.textContent =
        nisn.value.length + "/10";

}


nisn.addEventListener(
    "input",
    function() {

        this.value =
            this.value
                .replace(/\D/g, "")
                .slice(0, 10);

        updateNisnCounter();

    }
);


updateNisnCounter();


/* =========================================
   SUBMIT ANIMATION
========================================= */

document
    .getElementById("formSiswa")
    .addEventListener(
        "submit",
        function(event) {

            if (!this.checkValidity()) {
                return;
            }

            const button =
                document.getElementById("saveButton");

            button.classList.add("loading");

            button.innerHTML =
                '<span class="spinner"></span> Menyimpan...';

        }
    );

</script>

</body>

</html>