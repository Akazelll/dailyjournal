<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Daily Journal</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link rel="icon" href="img/favicon.ico" />
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container">
      <a class="navbar-brand" href="#">My Daily Journal</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
          <li class="nav-item">
            <a class="nav-link" href="#hero">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#article">Article</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#gallery">Gallery</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#schedule">Schedule</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#profile">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php" target="_blank">Login</a>
          </li>
          <div class="row">
            <li class="nav-item d-flex align-items-center">
              <button id="darkBtn" class="btn btn-dark me-2">
                <i class="bi bi-moon-fill"></i>
              </button>
              <button id="lightBtn" class="btn btn-danger">
                <i class="bi bi-sun-fill"></i>
              </button>
            </li>
          </div>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->

  <section
    id="hero"
    class="text-center p-5 bg-danger-subtle text-dark text-sm-start">
    <div class="container">
      <div class="d-sm-flex flex-sm-row-reverse align-items-center">
        <img src="img/hero.jpg" class="img-fluid" width="300" />
        <div>
          <h1 class="fw-bold display-4">
            Create Memories, Save Memories, Everyday
          </h1>
          <h4 class="lead display-6">
            Mencatat semua kegiatan sehari-hari yang ada tanpa terkecuali
          </h4>
          <h6>
            <span id="tanggal"></span>
            <span id="jam"></span>
          </h6>
        </div>
      </div>
    </div>
  </section>

  <!-- Article -->

  <section id="article" class="text-center p-5">
    <div class="container">
      <h1 class="fw-bold display-4 pb-3">Article</h1>
      <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
        <?php
        $sql = "SELECT * FROM article ORDER BY tanggal DESC";
        $hasil = $conn->query($sql);

        while ($row = $hasil->fetch_assoc()) {
        ?>
          <div class="col">
            <div class="card h-100">
              <img src="img/<?= $row["gambar"] ?>" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title"><?= $row["judul"] ?></h5>
                <p class="card-text">
                  <?= $row["isi"] ?>
                </p>
              </div>
              <div class="card-footer">
                <small class="text-body-secondary">
                  <?= $row["tanggal"] ?>
                </small>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>
  <!-- article end -->

  <!-- Gallery -->
  <section id="gallery" class="text-center p-5 bg-danger-subtle text-dark">
    <h1 class="fw-bold display-4 pb-3">Gallery</h1>
    <div
      id="carouselExampleAutoplaying"
      class="carousel slide"
      data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img
            src="img/card1.jpg"
            class="d-block w-100 img-fluid object-fit-cover"
            alt="..." />
        </div>
        <div class="carousel-item">
          <img
            src="img/card2.jpg"
            class="d-block w-100 img-fluid object-fit-cover"
            alt="..." />
        </div>
        <div class="carousel-item">
          <img
            src="img/card3.jpg"
            class="d-block w-100 img-fluid object-fit-cover"
            alt="..." />
        </div>
        <div class="carousel-item">
          <img
            src="img/card4.jpg"
            class="d-block w-100 img-fluid object-fit-cover"
            alt="..." />
        </div>
        <div class="carousel-item">
          <img
            src="img/card5.jpg"
            class="d-block w-100 img-fluid object-fit-cover"
            alt="..." />
        </div>
      </div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </section>
  <!-- Schedule -->

  <section id="schedule" class="text-center p-5">
    <h1 class="fw-bold display-4 pb-3">Jadwal Kuliah & Kegiatan Mahasiswa</h1>

    <div class="container">
      <div
        class="row row-cols-1 row-cols-md-4 g-4 justify-content-center align-items-stretch">
        <!-- Senin -->
        <div class="col d-flex">
          <div class="card border-primary bg-white w-100 h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0">Senin</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                <strong>09.30 - 12.00:</strong><br />Probabilitas dan Statistik<br /><br />
                <strong>15.30 - 18.00:</strong><br />Logika Informatika
              </p>
            </div>
          </div>
        </div>

        <!-- Selasa -->
        <div class="col d-flex">
          <div class="card border-success bg-white w-100 h-100">
            <div class="card-header bg-success text-white">
              <h5 class="card-title mb-0">Selasa</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                <strong>10.20 - 12.00:</strong><br />Praktikum Basis Data<br /><br />
                <strong>12.30 - 14.10:</strong><br />Pemrograman Berbasis Web
              </p>
            </div>
          </div>
        </div>

        <!-- Rabu -->
        <div class="col d-flex">
          <div class="card border-danger bg-white w-100 h-100">
            <div class="card-header bg-danger text-white">
              <h5 class="card-title mb-0">Rabu</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                <strong>09.30 - 12.00:</strong><br />Rekayasa Perangkat Lunak<br /><br />
                <strong>12.30 - 15.00:</strong><br />Kriptografi
              </p>
            </div>
          </div>
        </div>

        <!-- Kamis -->
        <div class="col d-flex">
          <div class="card border-warning bg-white w-100 h-100">
            <div class="card-header bg-warning text-white">
              <h5 class="card-title mb-0">Kamis</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                <strong>10.20 - 12.00:</strong><br />Teori Basis Data<br /><br />
                <strong>12.30 - 15.00:</strong><br />Sistem Operasi
              </p>
            </div>
          </div>
        </div>

        <!-- Jumat -->
        <div class="col d-flex">
          <div class="card border-info bg-white w-100 h-100">
            <div class="card-header bg-info text-white">
              <h5 class="card-title mb-0">Jumat</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                <strong>12.30 - 15.00:</strong><br />Penambangan Data
              </p>
            </div>
          </div>
        </div>

        <!-- Sabtu -->
        <div class="col d-flex">
          <div class="card border-secondary bg-white w-100 h-100">
            <div class="card-header bg-secondary text-white">
              <h5 class="card-title mb-0">Sabtu</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                Tidak ada jadwal kuliah atau kegiatan
              </p>
            </div>
          </div>
        </div>

        <!-- Minggu -->
        <div class="col d-flex">
          <div class="card border-dark bg-white w-100 h-100">
            <div class="card-header bg-dark text-white">
              <h5 class="card-title mb-0">Minggu</h5>
            </div>
            <div
              class="card-body d-flex flex-column justify-content-center py-5">
              <p class="card-text mb-0">
                Tidak ada jadwal kuliah atau kegiatan
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Profile -->

  <section
    id="profile"
    class="text-center p-5 bg-danger-subtle text-dark text-sm-start">
    <div class="container py-5">
      <h1 class="fw-bold display-4 mb-4 text-center">Profil Mahasiswa</h1>
      <div
        class="d-sm-flex flex-sm-row align-items-center justify-content-center gap-5">
        <!-- Foto Profil -->
        <div class="text-center mb-4 mb-sm-0">
          <img
            src="img/profile.jpg"
            class="rounded-circle border border-3 border-dark shadow"
            alt="Profile Picture"
            width="200"
            height="200"
            style="object-fit: cover" />
        </div>

        <!-- Teks Profil -->
        <div>
          <h3 class="fw-bold mb-4">Adam Raga</h3>

          <table
            class="mx-auto text-start table-borderless"
            style="max-width: 420px">
            <tbody>
              <tr>
                <td class="fw-semibold" style="width: 160px">NIM</td>
                <td style="width: 10px">:</td>
                <td>A11.2024.15598</td>
              </tr>
              <tr>
                <td class="fw-semibold">Program Studi</td>
                <td>:</td>
                <td>Teknik Informatika</td>
              </tr>
              <tr>
                <td class="fw-semibold">Fakultas</td>
                <td>:</td>
                <td>Ilmu Komputer</td>
              </tr>
              <tr>
                <td class="fw-semibold">Universitas</td>
                <td>:</td>
                <td>Universitas Dian Nuswantoro</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <footer class="text-center p-5">
    <div id="iconlink" class="mb-3 text-center">
      <a href="https://instagram.com/adamxraga" target="_blank"><i class="bi bi-instagram h3 p-2 text-dark"></i></a>
      <a href="https://www.linkedin.com/in/adamxraga/" target="_blank"><i class="bi bi-linkedin h3 p-2 text-dark"></i></a>
    </div>
    <p class="">&copy; 2026 My Daily Journal - Kelompok 5</p>
  </footer>
</body>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
  crossorigin="anonymous"></script>
<script type="text/javascript">
  // Fungsi untuk menampilkan tanggal dan jam

  window.setTimeout("tampilkanwaktu()", 1000);

  function tampilkanwaktu() {
    const waktu = new Date();
    const bulan = waktu.getMonth() + 1;
    setTimeout("tampilkanwaktu()", 1000);
    document.getElementById("tanggal").innerHTML =
      waktu.getDate() + "/" + bulan + "/" + waktu.getFullYear();
    document.getElementById("jam").innerHTML =
      waktu.getHours() + ":" + waktu.getMinutes() + ":" + waktu.getSeconds();
  }

  // Fungsi untuk Dark Mode

  const darkBtn = document.getElementById("darkBtn");
  const lightBtn = document.getElementById("lightBtn");

  darkBtn.addEventListener("click", function() {
    document.body.className = "bg-dark text-white";

    document.getElementById("article").className =
      "text-center p-5 bg-dark text-white";

    document.querySelectorAll(".card").forEach((card) => {
      card.classList.remove("bg-white", "text-dark");
      card.classList.add("bg-secondary", "text-white");
    });
    document.querySelectorAll(".card-footer").forEach((footer) => {
      footer.classList.remove("bg-light", "text-dark");
      footer.classList.add("bg-secondary", "text-light");
    });

    document.getElementById("hero").className =
      "text-center p-5 bg-secondary text-light text-sm-start";

    document.getElementById("gallery").className =
      "text-center p-5 bg-secondary text-light";

    document.getElementById("schedule").className =
      "text-center p-5 bg-dark text-white";

    document.getElementById("profile").className =
      "text-center p-5 bg-secondary text-light text-sm-start";

    document.querySelector(".bi-instagram").className =
      "bi bi-instagram h3 p-2 text-white";
    document.querySelector(".bi-linkedin").className =
      "bi bi-linkedin h3 p-2 text-white";
  });

  // Fungsi untuk Light Mode

  lightBtn.addEventListener("click", function() {
    document.body.className = "bg-light text-dark";

    document.getElementById("article").className =
      "text-center p-5 bg-light text-dark";

    document.querySelectorAll(".card").forEach((card) => {
      card.classList.remove("bg-secondary", "text-white");
      card.classList.add("bg-white", "text-dark");
    });

    document.querySelectorAll(".card-footer").forEach((footer) => {
      footer.classList.remove("bg-secondary", "text-light");
      footer.classList.add("bg-light", "text-dark");
    });

    document.getElementById("hero").className =
      "text-center p-5 bg-danger-subtle text-dark text-sm-start";

    document.getElementById("gallery").className =
      "text-center p-5 bg-danger-subtle text-dark";

    document.getElementById("schedule").className =
      "text-center p-5 bg-light text-dark";

    document.getElementById("profile").className =
      "text-center p-5 bg-danger-subtle text-dark text-sm-start";

    document.querySelector(".bi-instagram").className =
      "bi bi-instagram h3 p-2 text-dark";
    document.querySelector(".bi-linkedin").className =
      "bi bi-linkedin h3 p-2 text-dark";
  });
</script>

</html>