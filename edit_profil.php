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

    <title>Edit Profil - ASTS</title>

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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #ddd;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px 15px;
            background: #222;
            color: white;
            border: 1px solid #444;
            border-radius: 9px;
            outline: none;
            font-size: 15px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #d4af37;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .readonly {
            background: #171717;
            color: #777;
            cursor: not-allowed;
        }

        .info {
            color: #888;
            font-size: 12px;
            margin-top: 6px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border-radius: 9px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-save {
            border: none;
            background: #d4af37;
            color: #111;
        }

        .btn-save:hover {
            background: #f0c94d;
        }

        .btn-back {
            border: 1px solid #555;
            background: transparent;
            color: white;
        }

        .btn-back:hover {
            background: #222;
        }

        @media (max-width: 600px) {

            body {
                padding: 15px;
            }

            .card {
                padding: 20px;
            }

            .buttons {
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>✏️ Edit Profil</h1>

        <p>Ubah informasi akun kamu</p>

    </div>


    <div class="card">

        <form
            action="proses_edit_profil.php"
            method="POST"
        >

            <!-- USERNAME -->

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    value="<?= htmlspecialchars($user['username']) ?>"
                    class="readonly"
                    readonly
                >

                <div class="info">
                    Username digunakan untuk login dan tidak dapat diubah.
                </div>

            </div>


            <!-- NAMA -->

            <div class="form-group">

                <label>Nama Lengkap</label>

                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    required
                >

            </div>


            <!-- NISN -->

            <div class="form-group">

                <label>NISN</label>

                <input
                    type="text"
                    name="nisn"
                    value="<?= htmlspecialchars($user['nisn']) ?>"
                    maxlength="10"
                    pattern="[0-9]{10}"
                    required
                >

            </div>


            <!-- TTL -->

            <div class="form-group">

                <label>Tempat, Tanggal Lahir</label>

                <input
                    type="text"
                    name="ttl"
                    value="<?= htmlspecialchars($user['ttl']) ?>"
                    required
                >

            </div>


            <!-- JENIS KELAMIN -->

            <div class="form-group">

                <label>Jenis Kelamin</label>

                <select name="gender" required>

                    <option value="Laki-laki"
                        <?= $user['gender'] === 'Laki-laki' ? 'selected' : '' ?>>
                        Laki-laki
                    </option>

                    <option value="Perempuan"
                        <?= $user['gender'] === 'Perempuan' ? 'selected' : '' ?>>
                        Perempuan
                    </option>

                </select>

            </div>


            <!-- EMAIL -->

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required
                >

            </div>


            <!-- NO HP -->

            <div class="form-group">

                <label>Nomor HP / WhatsApp</label>

                <input
                    type="text"
                    name="no_hp"
                    value="<?= htmlspecialchars($user['no_hp']) ?>"
                    maxlength="15"
                    pattern="[0-9]{10,15}"
                    required
                >

            </div>


            <!-- ALAMAT -->

            <div class="form-group">

                <label>Alamat</label>

                <textarea
                    name="address"
                    required
                ><?= htmlspecialchars($user['address']) ?></textarea>

            </div>


            <!-- BUTTON -->

            <div class="buttons">

                <a
                    href="profil.php"
                    class="btn btn-back"
                >
                    ← Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    💾 Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>