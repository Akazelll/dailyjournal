<?php
session_start();

include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal | Admin</title>
    <link rel="icon" href="img/logo.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top bg-danger-subtle">
        <div class="container">
            <a class="navbar-brand" href=".">My Daily Journal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=article">Article</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="content" class="p-5">
        <div class="container">
            <?php
            // Whitelist allowed admin pages to prevent LFI/RFI
            $allowed_pages = array('dashboard', 'article');
            $page = isset($_GET['page']) ? preg_replace('/[^a-z0-9_\-]/i', '', $_GET['page']) : '';

            if ($page !== '' && in_array($page, $allowed_pages)) {
            ?>
                <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle"><?= htmlspecialchars(ucfirst($page), ENT_QUOTES, 'UTF-8') ?></h4>
            <?php
                include($page . ".php");
            } else {
            ?>
                <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">Dashboard</h4>
            <?php
                include("dashboard.php");
            }
            ?>
        </div>
    </section>

    <footer class="text-center p-5 bg-danger-subtle">
        <div id="iconlink" class="mb-3 text-center">
            <a href="https://instagram.com/adamxraga" target="_blank"><i class="bi bi-instagram h3 p-2 text-dark"></i></a>
            <a href="https://www.linkedin.com/in/adamxraga/" target="_blank"><i class="bi bi-linkedin h3 p-2 text-dark"></i></a>
        </div>
        <p class="">&copy; 2026 My Daily Journal - Kelompok 5</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>