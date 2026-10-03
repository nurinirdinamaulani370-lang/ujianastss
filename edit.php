<?php

include "koneksi.php";

$error = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

/* =========================
   AMBIL DATA SISWA
========================= */
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: index.php");
    exit;
}

/* =========================
   PROSES UPDATE
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $nisn = trim($_POST['nisn'] ?? '');
    $ttl = trim($_POST['ttl'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    /* Validasi */
    if (
        $name === "" ||
        $nisn === "" ||
        $ttl === "" ||
        $gender === "" ||
        $email === "" ||
        $address === ""
    ) {
        $error = "Semua data wajib diisi.";
    } elseif (!preg_match('/^[0-9]{10}$/', $nisn)) {
        $error = "NISN harus terdiri dari tepat 10 angka.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users 
             SET name = ?, nisn = ?, ttl = ?, gender = ?, email = ?, address = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $name,
            $nisn,
            $ttl,
            $gender,
            $email,
            $address,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal memperbarui data.";
        }

        mysqli_stmt_close($stmt);
    }

    /* Supaya input tetap muncul setelah error */
    $data['name'] = $name;
    $data['nisn'] = $nisn;
    $data['ttl'] = $ttl;
    $data['gender'] = $gender;
    $data['email'] = $email;
    $data['address'] = $address;
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Data Siswa - Ujian ASTS</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.10), transparent 30%),
                radial-gradient(circle at 90% 80%, rgba(255, 200, 70, 0.08), transparent 30%),
                #050505;
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
           BACKGROUND ANIMATION
        ========================= */

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
            opacity: .18;
            animation: floatOrb 12s infinite alternate ease-in-out;
        }

        .orb.one {
            width: 300px;
            height: 300px;
            background: #d4af37;
            top: -100px;
            left: 15%;
        }

        .orb.two {
            width: 260px;
            height: 260px;
            background: #a87900;
            right: 5%;
            bottom: 10%;
            animation-delay: 2s;
        }

        .orb.three {
            width: 180px;
            height: 180px;
            background: #f5d76e;
            left: 35%;
            bottom: -80px;
            animation-delay: 4s;
        }

        @keyframes floatOrb {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(50px, -35px) scale(1.15);
            }
        }

        /* =========================
           LAYOUT
        ========================= */

        .layout {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 260px;
            background: rgba(10, 10, 10, .88);
            border-right: 1px solid rgba(212, 175, 55, .18);
            backdrop-filter: blur(20px);
            padding: 30px 20px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 5px 10px 35px;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: linear-gradient(135deg, #f5d76e, #b8860b);
            color: #080808;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            font-weight: 900;
            box-shadow: 0 0 25px rgba(212, 175, 55, .25);
            animation: logoPulse 3s infinite;
        }

        @keyframes logoPulse {
            0%, 100% {
                box-shadow: 0 0 15px rgba(212, 175, 55, .2);
            }

            50% {
                box-shadow: 0 0 30px rgba(212, 175, 55, .5);
            }
        }

        .logo-text h2 {
            font-size: 19px;
            letter-spacing: 1px;
        }

        .logo-text p {
            color: #9a9a9a;
            font-size: 11px;
            margin-top: 3px;
        }

        .menu-title {
            color: #777;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 0 12px 12px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            text-decoration: none;
            color: #aaa;
            padding: 14px 15px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            transition: .3s;
            border: 1px solid transparent;
        }

        .menu a:hover {
            color: #fff;
            background: rgba(212, 175, 55, .08);
            border-color: rgba(212, 175, 55, .15);
            transform: translateX(5px);
        }

        .menu a.active {
            color: #050505;
            background: linear-gradient(135deg, #f5d76e, #b8860b);
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(212, 175, 55, .18);
        }

        .menu-icon {
            width: 25px;
            text-align: center;
            font-size: 17px;
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 45px;
        }

        .top {
            animation: slideDown .7s ease;
            margin-bottom: 30px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .eyebrow {
            color: #d4af37;
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .top h1 {
            font-size: 35px;
            font-weight: 700;
        }

        .top p {
            color: #888;
            margin-top: 8px;
            font-size: 14px;
        }

        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            max-width: 900px;
            background: rgba(16, 16, 16, .82);
            border: 1px solid rgba(212, 175, 55, .20);
            border-radius: 25px;
            padding: 32px;
            backdrop-filter: blur(22px);
            box-shadow:
                0 25px 70px rgba(0, 0, 0, .45),
                inset 0 1px 0 rgba(255, 255, 255, .03);
            animation: cardAppear .8s ease;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(35px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 22px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
        }

        .card-header h2 {
            font-size: 20px;
        }

        .card-header p {
            color: #777;
            font-size: 13px;
            margin-top: 5px;
        }

        .badge {
            background: rgba(212, 175, 55, .10);
            color: #e4c65a;
            border: 1px solid rgba(212, 175, 55, .25);
            padding: 8px 13px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* =========================
           ERROR
        ========================= */

        .error {
            background: rgba(220, 50, 50, .10);
            border: 1px solid rgba(255, 80, 80, .25);
            color: #ff8c8c;
            padding: 14px 17px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 13px;
            animation: shake .4s ease;
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-6px);
            }

            75% {
                transform: translateX(6px);
            }
        }

        /* =========================
           GRID
        ========================= */

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field {
            animation: fieldAppear .6s ease backwards;
        }

        .field:nth-child(1) {
            animation-delay: .05s;
        }

        .field:nth-child(2) {
            animation-delay: .10s;
        }

        .field:nth-child(3) {
            animation-delay: .15s;
        }

        .field:nth-child(4) {
            animation-delay: .20s;
        }

        .field:nth-child(5) {
            animation-delay: .25s;
        }

        .field:nth-child(6) {
            animation-delay: .30s;
        }

        @keyframes fieldAppear {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            display: block;
            color: #cfcfcf;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .required {
            color: #d4af37;
        }

        input,
        select,
        textarea {
            width: 100%;
            background: rgba(255, 255, 255, .035);
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 13px;
            color: #fff;
            padding: 14px 16px;
            font-size: 14px;
            outline: none;
            transition: .3s;
            font-family: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #d4af37;
            background: rgba(212, 175, 55, .045);
            box-shadow:
                0 0 0 3px rgba(212, 175, 55, .08),
                0 0 25px rgba(212, 175, 55, .08);
            transform: translateY(-1px);
        }

        input::placeholder,
        textarea::placeholder {
            color: #555;
        }

        select {
            cursor: pointer;
        }

        select option {
            background: #111;
            color: #fff;
        }

        textarea {
            min-height: 115px;
            resize: vertical;
        }

        .nisn-wrapper {
            position: relative;
        }

        .counter {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            font-size: 11px;
            pointer-events: none;
        }

        .helper {
            color: #666;
            font-size: 11px;
            margin-top: 7px;
        }

        /* =========================
           BUTTONS
        ========================= */

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, .07);
        }

        .btn {
            border: none;
            border-radius: 13px;
            padding: 13px 22px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
        }

        .btn-cancel {
            color: #aaa;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .btn-cancel:hover {
            color: #fff;
            background: rgba(255, 255, 255, .09);
            transform: translateY(-2px);
        }

        .btn-save {
            color: #080808;
            background: linear-gradient(135deg, #f5d76e, #b8860b);
            box-shadow: 0 8px 25px rgba(212, 175, 55, .15);
            min-width: 170px;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(212, 175, 55, .28);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        .spinner {
            width: 15px;
            height: 15px;
            border: 2px solid rgba(0, 0, 0, .25);
            border-top-color: #000;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* =========================
           INFO
        ========================= */

        .info {
            max-width: 900px;
            margin-top: 18px;
            padding: 15px 18px;
            border-radius: 13px;
            background: rgba(212, 175, 55, .05);
            border: 1px solid rgba(212, 175, 55, .12);
            color: #888;
            font-size: 12px;
        }

        .info strong {
            color: #d4af37;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {
            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
                padding: 30px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .field.full {
                grid-column: auto;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 18px;
            }

            .layout {
                display: block;
            }

            .logo {
                padding-bottom: 20px;
            }

            .menu {
                flex-direction: row;
                overflow-x: auto;
            }

            .menu-title {
                display: none;
            }

            .menu a {
                white-space: nowrap;
            }

            .main {
                margin-left: 0;
                width: 100%;
                padding: 22px;
            }

            .top h1 {
                font-size: 28px;
            }

            .form-card {
                padding: 22px;
                border-radius: 20px;
            }

            .card-header {
                align-items: flex-start;
                gap: 15px;
            }

            .badge {
                white-space: nowrap;
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
    </div>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="logo">
                <div class="logo-icon">A</div>

                <div class="logo-text">
                    <h2>ASTS</h2>
                    <p>Student Management</p>
                </div>
            </div>

            <div class="menu-title">
                Menu Utama
            </div>

            <nav class="menu">

                <a href="dashboard.php">
                    <span class="menu-icon">⌂</span>
                    Dashboard
                </a>

                <a href="index.php">
                    <span class="menu-icon">▣</span>
                    Data Siswa
                </a>

                <a href="tambah.php">
                    <span class="menu-icon">＋</span>
                    Tambah Data
                </a>

                <a href="edit.php?id=<?= $id ?>" class="active">
                    <span class="menu-icon">✎</span>
                    Edit Data
                </a>

            </nav>

        </aside>

        <!-- MAIN -->
        <main class="main">

            <div class="top">
                <div class="eyebrow">✦ Student Management</div>

                <h1>Edit Data Siswa</h1>

                <p>
                    Perbarui informasi siswa dengan data yang terbaru.
                </p>
            </div>

            <div class="form-card">

                <div class="card-header">

                    <div>
                        <h2>Informasi Siswa</h2>
                        <p>Edit data siswa yang dipilih.</p>
                    </div>

                    <div class="badge">
                        ✦ EDIT DATA
                    </div>

                </div>

                <?php if ($error): ?>

                    <div class="error">
                        ⚠ <?= htmlspecialchars($error) ?>
                    </div>

                <?php endif; ?>

                <form method="POST" id="editForm">

                    <div class="form-grid">

                        <!-- NAMA -->
                        <div class="field">
                            <label>
                                Nama Lengkap <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="<?= htmlspecialchars($data['name']) ?>"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                        </div>

                        <!-- NISN -->
                        <div class="field">

                            <label>
                                NISN <span class="required">*</span>
                            </label>

                            <div class="nisn-wrapper">

                                <input
                                    type="text"
                                    name="nisn"
                                    id="nisn"
                                    value="<?= htmlspecialchars($data['nisn']) ?>"
                                    placeholder="Masukkan 10 angka"
                                    minlength="10"
                                    maxlength="10"
                                    pattern="[0-9]{10}"
                                    inputmode="numeric"
                                    title="NISN harus terdiri dari tepat 10 angka"
                                    required
                                >

                                <span class="counter" id="counter">
                                    <?= strlen($data['nisn']) ?>/10
                                </span>

                            </div>

                            <div class="helper">
                                NISN harus terdiri dari tepat 10 angka.
                            </div>

                        </div>

                        <!-- TTL -->
                        <div class="field">

                            <label>
                                Tempat, Tanggal Lahir <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="ttl"
                                value="<?= htmlspecialchars($data['ttl']) ?>"
                                placeholder="Contoh: Sumenep, 15 Mei 2009"
                                required
                            >

                        </div>

                        <!-- GENDER -->
                        <div class="field">

                            <label>
                                Jenis Kelamin <span class="required">*</span>
                            </label>

                            <select name="gender" required>

                                <option value="">
                                    Pilih jenis kelamin
                                </option>

                                <option
                                    value="MALE"
                                    <?= $data['gender'] === 'MALE' ? 'selected' : '' ?>
                                >
                                    Laki-laki
                                </option>

                                <option
                                    value="FEMALE"
                                    <?= $data['gender'] === 'FEMALE' ? 'selected' : '' ?>
                                >
                                    Perempuan
                                </option>

                            </select>

                        </div>

                        <!-- EMAIL -->
                        <div class="field">

                            <label>
                                Email <span class="required">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($data['email']) ?>"
                                placeholder="contoh@gmail.com"
                                required
                            >

                        </div>

                        <!-- ALAMAT -->
                        <div class="field full">

                            <label>
                                Alamat <span class="required">*</span>
                            </label>

                            <textarea
                                name="address"
                                placeholder="Masukkan alamat lengkap"
                                required
                            ><?= htmlspecialchars($data['address']) ?></textarea>

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="actions">

                        <a href="index.php" class="btn btn-cancel">
                            ← Batal
                        </a>

                        <button
                            type="submit"
                            class="btn btn-save"
                            id="saveButton"
                        >

                            <span id="saveText">
                                ✦ Simpan Perubahan
                            </span>

                            <span
                                class="spinner"
                                id="spinner"
                            ></span>

                        </button>

                    </div>

                </form>

            </div>

            <div class="info">
                <strong>ⓘ Catatan:</strong>
                Pastikan semua informasi siswa sudah benar sebelum menyimpan perubahan.
                NISN wajib berupa tepat 10 angka.
            </div>

        </main>

    </div>

    <script>

        /* =========================
           NISN COUNTER
        ========================= */

        const nisn = document.getElementById("nisn");
        const counter = document.getElementById("counter");

        nisn.addEventListener("input", function () {

            this.value = this.value.replace(/\D/g, "").slice(0, 10);

            counter.textContent = this.value.length + "/10";

        });


        /* =========================
           BUTTON LOADING
        ========================= */

        const form = document.getElementById("editForm");
        const saveButton = document.getElementById("saveButton");
        const saveText = document.getElementById("saveText");
        const spinner = document.getElementById("spinner");

        form.addEventListener("submit", function (event) {

            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;

            saveText.textContent = "Menyimpan...";

            spinner.style.display = "inline-block";

        });

    </script>

</body>

</html> 