<?php
include "koneksi.php";
include "ai_image.php";

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'generate_image') {
    if (isset($_POST['isi'])) {
        $isiArtikel = $_POST['isi'];
        $format = $_POST['format'] ?? 'jpg';

        $prompt = substr($isiArtikel, 0, 300);

        // Panggil fungsi dengan parameter format
        echo generateImageAI($prompt, $format);
    } else {
        echo json_encode(["success" => false, "error" => "Isi artikel tidak dikirim"]);
    }
} else {
    echo json_encode(["error" => "Action tidak dikenal"]);
}
