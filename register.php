<?php

session_start();

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - ASTS</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #0b0b0b;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 30px;
        }

        .container {
            width: 100%;
            max-width: 700px;
            background: #151515;
            border: 1px solid #c9a227;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 0 30px rgba(201, 162, 39, 0.15);
        }

        h1 {
            text-align: center;
            color: #d4af37;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #aaa;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
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
            border: 1px solid #444;
            border-radius: 10px;
            background: #222;
            color: white;
            font-size: 15px;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #d4af37;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #d4af37;
            color: #111;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #f0c94d;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #aaa;
        }

        .login-link a {
            color: #d4af37;
            text-decoration: none;
            font-weight: bold;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .password-info {
            color: #888;
            font-size: 12px;
            margin-top: 6px;
        }

        .username-info {
            color: #888;
            font-size: 12px;
            margin-top: 6px;
        }

        @media (max-width: 600px) {

            .row {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 25px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>ASTS</h1>

    <p class="subtitle">
        Pendaftaran Akun Siswa
    </p>

    <form action="proses_register.php" method="POST">

        <!-- USERNAME -->

        <div class="form-group">

            <label>Username</label>

            <input
                type="text"
                name="username"
                placeholder="Masukkan username"
                minlength="4"
                maxlength="50"
                pattern="[A-Za-z0-9_]+"
                required
            >

            <div class="username-info">
                Username minimal 4 karakter dan hanya boleh menggunakan
                huruf, angka, serta underscore (_).
            </div>

        </div>


        <!-- NAMA -->

        <div class="form-group">

            <label>Nama Lengkap</label>

            <input
                type="text"
                name="name"
                placeholder="Masukkan nama lengkap"
                required
            >

        </div>


        <!-- NISN & NO HP -->

        <div class="row">

            <div class="form-group">

                <label>NISN</label>

                <input
                    type="text"
                    name="nisn"
                    placeholder="10 digit NISN"
                    maxlength="10"
                    pattern="[0-9]{10}"
                    required
                >

            </div>


            <div class="form-group">

                <label>Nomor HP / WhatsApp</label>

                <input
                    type="text"
                    name="no_hp"
                    placeholder="08xxxxxxxxxx"
                    maxlength="15"
                    pattern="[0-9]{10,15}"
                    required
                >

            </div>

        </div>


        <!-- TTL -->

        <div class="form-group">

            <label>Tempat, Tanggal Lahir</label>

            <input
                type="text"
                name="ttl"
                placeholder="Contoh: Sumenep, 12 Januari 2008"
                required
            >

        </div>


        <!-- JENIS KELAMIN -->

        <div class="form-group">

            <label>Jenis Kelamin</label>

            <select name="gender" required>

                <option value="">
                    -- Pilih Jenis Kelamin --
                </option>

                <option value="Laki-laki">
                    Laki-laki
                </option>

                <option value="Perempuan">
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
                placeholder="contoh@gmail.com"
                required
            >

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                minlength="6"
                required
            >

            <div class="password-info">
                Password minimal 6 karakter.
            </div>

        </div>


        <!-- KONFIRMASI PASSWORD -->

        <div class="form-group">

            <label>Konfirmasi Password</label>

            <input
                type="password"
                name="password_confirmation"
                placeholder="Masukkan ulang password"
                minlength="6"
                required
            >

        </div>


        <!-- ALAMAT -->

        <div class="form-group">

            <label>Alamat</label>

            <textarea
                name="address"
                placeholder="Masukkan alamat lengkap"
                required
            ></textarea>

        </div>


        <!-- BUTTON -->

        <button type="submit" class="btn">

            Daftar Sekarang

        </button>

    </form>


    <div class="login-link">

        Sudah punya akun?

        <a href="login.php">
            Login di sini
        </a>

    </div>

</div>

</body>

</html>