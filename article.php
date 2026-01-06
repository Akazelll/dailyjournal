<div class="container">
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Article
    </button>

    <div class="row">
        <div class="table-responsive" id="article_data">
        </div>

        <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Article</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body pt-3 px-4 pb-4">

                            <div class="mb-4 position-relative">
                                <input type="text" class="form-control form-control-lg border-0 border-bottom rounded-0 fw-bold fs-3 text-dark ps-0"
                                    name="judul" id="judul" placeholder="Tulis Judul di sini..." style="box-shadow: none;" required>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-8">
                                    <textarea class="form-control bg-light border-0 rounded-3 p-3" id="isi" name="isi"
                                        placeholder="Mulai menulis cerita Anda (minimal 10 karakter untuk generate gambar AI)..." rows="12" style="resize: none; font-size: 1rem;" required></textarea>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-0 bg-light rounded-4 h-100">
                                        <div class="card-body p-3 d-flex flex-column">

                                            <div class="mb-3">
                                                <label for="formatGambar" class="form-label fw-bold small text-muted text-uppercase mb-1">
                                                    <i class="bi bi-file-earmark-image me-1"></i>Format Output AI
                                                </label>
                                                <select class="form-select form-select-sm border-0 shadow-sm" id="formatGambar">
                                                    <option value="jpg" selected>JPG (Default - Ringan)</option>
                                                    <option value="png">PNG (Kualitas Tinggi)</option>
                                                    <option value="gif">GIF (Animasi/Standar)</option>
                                                </select>
                                            </div>

                                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Visual</label>

                                            <div id="aiImagePreviewContainer" class="flex-grow-1 rounded-3 bg-white border border-dashed d-flex align-items-center justify-content-center overflow-hidden position-relative mb-3" style="min-height: 140px;">
                                                <div class="text-center text-muted opacity-50" id="placeholderIcon">
                                                    <i class="bi bi-card-image fs-1"></i>
                                                    <div style="font-size: 0.7rem;">Preview</div>
                                                </div>
                                                <img src="" id="aiImagePreview" class="img-fluid w-100 h-100 object-fit-cover d-none position-absolute" alt="Preview">
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-dark btn-sm rounded-3 btn-ai-action" data-action="generate_image">
                                                    <i class="bi bi-stars text-warning me-1"></i> Generate Image
                                                </button>

                                                <div class="position-relative">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 w-100" onclick="document.getElementById('fileGambarManual').click()">
                                                        <i class="bi bi-upload me-1"></i> Upload Manual
                                                    </button>
                                                    <input type="file" class="d-none" name="gambar" id="fileGambarManual">
                                                </div>
                                            </div>

                                            <input type="hidden" name="gambar_ai_base64" id="gambarAiBase64">

                                            <div class="mt-2 text-center">
                                                <small class="text-muted" style="font-size: 0.65rem;">*Pilih salah satu metode</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 px-4 pb-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="simpan" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        load_data();

        // Handler tombol AI (Generate Image)
        $('.btn-ai-action').click(function() {
            var actionType = $(this).data('action');
            var isi = $('#isi').val(); // Mengambil prompt dari isi artikel
            var format = $('#formatGambar').val(); // Mengambil format pilihan user

            // Validasi input isi
            if (isi.length < 10) {
                alert("Mohon isi konten artikel minimal 10 karakter untuk generate gambar!");
                return;
            }

            var btn = $(this);
            var originalText = btn.html();

            // UI Loading
            btn.html('<span class="spinner-border spinner-border-sm"></span> Loading...');
            btn.prop('disabled', true);
            $('#placeholderIcon').hide();
            $('#aiImagePreviewContainer').append('<div id="loadingText" class="text-center text-muted small">Sedang melukis...</div>');
            $('#aiImagePreview').addClass('d-none');

            // Request AJAX
            $.ajax({
                url: "ajax_ai.php",
                method: "POST",
                dataType: "json",
                data: {
                    action: actionType,
                    isi: isi, // Kirim prompt
                    format: format // Kirim format (jpg/png/gif)
                },
                success: function(response) {
                    $('#loadingText').remove();

                    if (actionType === 'generate_image') {
                        if (response.success) {
                            // Tampilkan hasil gambar
                            $('#aiImagePreview').attr('src', response.image_base64).removeClass('d-none');

                            // Masukkan data base64 ke hidden input untuk disubmit
                            $('#gambarAiBase64').val(response.image_base64);

                            // Reset input file manual agar tidak double upload
                            $('#fileGambarManual').val('');
                            $('#placeholderIcon').hide();
                        } else {
                            alert("Error Gambar: " + response.error);
                            $('#placeholderIcon').show();
                        }
                    }

                    btn.html(originalText);
                    btn.prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    $('#loadingText').remove();
                    console.error(xhr.responseText);
                    alert("Gagal menghubungi server AI.");

                    btn.html(originalText);
                    btn.prop('disabled', false);
                    $('#placeholderIcon').show();
                }
            });
        });

        // Handler Upload Manual (Preview saat user pilih file dari komputer)
        $('#fileGambarManual').change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#aiImagePreview').attr('src', e.target.result).removeClass('d-none');
                    $('#placeholderIcon').hide();
                    $('#loadingText').remove();
                }
                reader.readAsDataURL(this.files[0]);

                // Reset hidden input AI agar yang dipakai adalah file manual
                $('#gambarAiBase64').val('');
            }
        });

        // Fungsi Load Data Tabel
        function load_data(hlm) {
            $.ajax({
                url: "article_data.php",
                method: "POST",
                data: {
                    hlm: hlm
                },
                success: function(data) {
                    $('#article_data').html(data);
                }
            })
        }

        // Handler Pagination
        $(document).on('click', '.halaman', function() {
            var hlm = $(this).attr("id");
            load_data(hlm);
        });
    });
</script>

<?php
include_once "upload_foto.php";

// Logic Simpan Data
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';

    $nama_gambar_manual = $_FILES['gambar']['name'];
    $gambar_ai_base64 = $_POST['gambar_ai_base64'] ?? '';

    // Prioritas 1: User upload file manual
    if ($nama_gambar_manual != '') {
        $cek_upload = upload_foto($_FILES["gambar"]);
        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
        } else {
            echo "<script>alert('" . $cek_upload['message'] . "'); document.location='admin.php?page=article';</script>";
            die;
        }
    }
    // Prioritas 2: User pakai gambar AI
    elseif (!empty($gambar_ai_base64)) {
        // Format Base64: "data:image/png;base64,....."
        $image_parts = explode(";base64,", $gambar_ai_base64);

        if (count($image_parts) === 2) {
            // Ambil ekstensi dari header mime type (misal: image/png -> png)
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            // Buat nama file unik: ai_timestamp_random.png
            $nama_file_ai = 'ai_' . time() . '_' . uniqid() . '.' . $image_type;

            // Simpan file ke folder img/
            if (file_put_contents('img/' . $nama_file_ai, $image_base64)) {
                $gambar = $nama_file_ai;
            }
        }
    }

    // Cek apakah mode Edit (Update) atau Tambah (Insert)
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Jika user tidak ganti gambar, pakai gambar lama
        if ($gambar == '') {
            $gambar = $_POST['gambar_lama'];
        } else {
            // Jika ganti gambar, hapus gambar lama (opsional, biar hemat storage)
            if (file_exists("img/" . $_POST['gambar_lama']) && $_POST['gambar_lama'] != '') {
                unlink("img/" . $_POST['gambar_lama']);
            }
        }

        $stmt = $conn->prepare("UPDATE article SET judul=?, isi=?, gambar=?, tanggal=?, username=? WHERE id=?");
        $stmt->bind_param("sssssi", $judul, $isi, $gambar, $tanggal, $username, $id);
        $simpan = $stmt->execute();
    } else {
        // Insert Baru
        $stmt = $conn->prepare("INSERT INTO article (judul,isi,gambar,tanggal,username) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
        $simpan = $stmt->execute();
    }

    if ($simpan) {
        echo "<script>alert('Simpan data sukses'); document.location='admin.php?page=article';</script>";
    } else {
        echo "<script>alert('Simpan data gagal'); document.location='admin.php?page=article';</script>";
    }

    $stmt->close();
    $conn->close();
}

// Logic Hapus Data
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        if (file_exists("img/" . $gambar)) {
            unlink("img/" . $gambar);
        }
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE id =?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>alert('Hapus data sukses'); document.location='admin.php?page=article';</script>";
    } else {
        echo "<script>alert('Hapus data gagal'); document.location='admin.php?page=article';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>