<?php
include "ai_image.php";

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'generate_image') {
    if (isset($_POST['judul'])) {
        $judul = $_POST['judul'];
        echo generateImageAI($judul);
    } else {
        echo json_encode(["success" => false, "error" => "Judul tidak dikirim untuk gambar"]);
    }
} else {
    echo json_encode(["error" => "Action tidak dikenal"]);
}
