<?php

session_start();

include "koneksi.php";
include "fonnte.php";

$error = "";
$success = "";


// =============================
// PESAN SETELAH REGISTER
// =============================

if (isset($_GET['register']) && $_GET['register'] === 'success') {
    $success = "Pendaftaran berhasil! Silakan login menggunakan username dan password.";
}


// =============================
// PROSES LOGIN
// =============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    // Validasi kosong
    if ($username === '' || $password === '') {

        $error = "Username dan password wajib diisi.";

    } else {


        // =============================
        // LOGIN ADMIN
        // =============================

        if ($username === "admin" && $password === "admin123") {

            $_SESSION['admin_login'] = true;
            $_SESSION['admin_username'] = $username;

            header("Location: dashboard.php");
            exit;
        }


        // =============================
        // LOGIN SISWA
        // =============================

        $query = mysqli_prepare(
            $conn,
            "SELECT * FROM users WHERE username = ? LIMIT 1"
        );


        if (!$query) {

            $error = "Terjadi kesalahan pada database.";

        } else {

            mysqli_stmt_bind_param(
                $query,
                "s",
                $username
            );

            mysqli_stmt_execute($query);

            $result = mysqli_stmt_get_result($query);


            // Username ditemukan
            if ($result && mysqli_num_rows($result) === 1) {

                $user = mysqli_fetch_assoc($result);


                // Cek password
                if (
                    isset($user['password']) &&
                    password_verify($password, $user['password'])
                ) {

                    // =============================
                    // SIMPAN SESSION SISWA
                    // =============================

                    $_SESSION['user_login'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_username'] = $user['username'];
                    $_SESSION['user_email'] = $user['email'];


                    // =============================
                    // MASUK DASHBOARD SISWA
                    // =============================

                    header("Location: dashboard_siswa.php");
                    $messageLogin = "🔐 *LOGIN ASTS BERHASIL*

Halo $user[name],

Anda berhasil login ke sistem ASTS.

Username: {$user['username']}

Jika ini bukan aktivitas Anda, segera hubungi admin.";

kirimWhatsApp($user['no_hp'], $messageLogin);
                    exit;

                } else {

                    $error = "Password salah.";

                }

            } else {

                $error = "Username tidak ditemukan.";

            }

            mysqli_stmt_close($query);
        }
    }
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - ASTS</title>


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

            padding: 20px;

        }


        .container {

            width: 100%;

            max-width: 430px;

            background: #151515;

            border: 1px solid #c9a227;

            border-radius: 20px;

            padding: 35px;

            box-shadow:
                0 0 30px rgba(201, 162, 39, 0.15);

        }


        .logo {

            text-align: center;

            margin-bottom: 25px;

        }


        .logo h1 {

            color: #d4af37;

            font-size: 36px;

            margin-bottom: 8px;

        }


        .logo p {

            color: #999;

            font-size: 14px;

        }


        .message {

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 18px;

            font-size: 14px;

            text-align: center;

        }


        .success {

            background: #12351f;

            border: 1px solid #2e8b57;

            color: #7ff0a5;

        }


        .error {

            background: #3a1515;

            border: 1px solid #8b3030;

            color: #ff8d8d;

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


        input {

            width: 100%;

            padding: 14px;

            border: 1px solid #444;

            border-radius: 10px;

            background: #222;

            color: white;

            font-size: 15px;

            outline: none;

        }


        input:focus {

            border-color: #d4af37;

        }


        .password-box {

            position: relative;

        }


        .password-box input {

            padding-right: 55px;

        }


        .toggle-password {

            position: absolute;

            right: 15px;

            top: 50%;

            transform: translateY(-50%);

            background: none;

            border: none;

            color: #d4af37;

            cursor: pointer;

            font-size: 13px;

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

            margin-top: 5px;

        }


        .btn:hover {

            background: #f0c94d;

        }


        .register {

            text-align: center;

            margin-top: 20px;

            color: #999;

        }


        .register a {

            color: #d4af37;

            text-decoration: none;

            font-weight: bold;

        }


        .admin-info {

            margin-top: 25px;

            padding: 15px;

            border: 1px solid #333;

            border-radius: 10px;

            background: #101010;

        }


        .admin-info h3 {

            color: #d4af37;

            font-size: 15px;

            margin-bottom: 10px;

        }


        .admin-info p {

            color: #aaa;

            font-size: 13px;

            margin: 5px 0;

        }


        .admin-info strong {

            color: white;

        }


        .student-info {

            text-align: center;

            color: #777;

            font-size: 12px;

            margin-top: 20px;

            line-height: 1.6;

        }


        @media (max-width: 500px) {

            .container {

                padding: 25px;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <div class="logo">

        <h1>ASTS</h1>

        <p>Sistem Informasi Data Siswa</p>

    </div>


    <!-- PESAN BERHASIL -->

    <?php if ($success !== ""): ?>

        <div class="message success">

            <?= htmlspecialchars($success) ?>

        </div>

    <?php endif; ?>


    <!-- PESAN ERROR -->

    <?php if ($error !== ""): ?>

        <div class="message error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <!-- FORM LOGIN -->

    <form method="POST" action="">


        <div class="form-group">

            <label>Username</label>

            <input
                type="text"
                name="username"
                placeholder="Masukkan username"
                autocomplete="username"
                required
            >

        </div>


        <div class="form-group">

            <label>Password</label>


            <div class="password-box">

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >


                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword()"
                >
                    Lihat
                </button>

            </div>

        </div>


        <button
            type="submit"
            class="btn"
        >
            Login
        </button>


    </form>


    <!-- REGISTER -->

    <div class="register">

        Belum punya akun?

        <a href="register.php">
            Daftar di sini
        </a>

    </div>


    <!-- INFO ADMIN -->

    <div class="admin-info">

        <h3>Login Administrator</h3>

        <p>
            Username:
            <strong>admin</strong>
        </p>

        <p>
            Password:
            <strong>admin123</strong>
        </p>

    </div>


    <!-- INFO SISWA -->

    <div class="student-info">

        Siswa login menggunakan
        <strong>username dan password</strong>
        yang dibuat saat pendaftaran.

    </div>


</div>


<script>

function togglePassword() {

    const password =
        document.getElementById("password");

    const button =
        document.querySelector(".toggle-password");


    if (password.type === "password") {

        password.type = "text";

        button.textContent = "Sembunyikan";

    } else {

        password.type = "password";

        button.textContent = "Lihat";

    }

}

</script>


</body>

</html>