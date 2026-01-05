<?php
// memulai / melanjutkan session
session_start();

// koneksi ke database
include "koneksi.php";

                //check jika sudah ada user yang login arahkan ke halaman admin
                if (isset($_SESSION['username'])) {
                    header("location:admin.php");
                }

// variabel untuk menampung pesan error
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'] ?? '';
    $password = $_POST['pass'] ?? '';

    // validasi sederhana
    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi.";
    } else {
        // enkripsi password dengan md5 (sesuaikan dengan yang ada di DB)
        $passwordHash = md5($password);

        // prepared statement
        $stmt = $conn->prepare(
            "SELECT username 
             FROM users 
             WHERE username = ? AND password = ?"
        );

        // binding parameter
        $stmt->bind_param("ss", $username, $passwordHash);

        // eksekusi
        $stmt->execute();

        // ambil hasil
        $hasil = $stmt->get_result();
        $row   = $hasil->fetch_assoc();

        if (!empty($row)) {
            // login sukses
            $_SESSION['username'] = $row['username'];
            header("Location: admin.php");
            exit;
        } else {
            // login gagal
            $error = "Username atau password salah.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | My Daily Journal</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        crossorigin="anonymous" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="icon" href="img/favicon.ico" />
</head>

<body class="bg-danger-subtle">

    <?php
    $username = "admin";
    $password = "123456";

    $user = '';
    $pass = '';
    $cardClass = '';
    $statusText = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['user'] ?? '';
        $pass = $_POST['pass'] ?? '';

        if ($user == $username && $pass == $password) {
            $cardClass  = "text-bg-success";
            $statusText = "Username dan password Benar";
        } else {
            $cardClass  = "text-bg-warning";
            $statusText = "Username dan password Salah";
        }
    }
    ?>

    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                <div class="card border-0 shadow rounded-5">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="bi bi-person-circle h1 display-4"></i>
                            <p class="mb-0 fw-semibold">My Daily Journal</p>
                            <small class="text-muted">Silakan login terlebih dahulu</small>
                            <hr />
                        </div>

                        <form action="" method="post">
                            <input
                                type="text"
                                name="user"
                                class="form-control my-3 py-2 rounded-4"
                                placeholder="Username" />

                            <input
                                type="password"
                                name="pass"
                                class="form-control my-3 py-2 rounded-4"
                                placeholder="Password" />

                            <div class="text-center my-3 d-grid">
                                <button class="btn btn-danger rounded-4" type="submit">
                                    Login
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <div class="card <?= $cardClass ?> border-0 shadow mt-3 mx-auto" style="max-width: 420px;">
                        <div class="card-body text-center rounded-4">
                            <p class="mb-1">user : <?= $user ?></p>
                            <p class="mb-3">pass : <?= $pass ?></p>
                            <p class="fw-semibold mb-0"><?= $statusText ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>

</html>