<?php
include_once "koneksi.php";

function translateToEnglish($text)
{
    $text = substr($text, 0, 1000);

    $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=en&dt=t&q=" . urlencode($text);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

    $result = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($result, true);
    if (isset($json[0][0][0])) {
        return $json[0][0][0];
    }
    return $text;
}

function generateImageAI($prompt, $targetFormat = 'jpg')
{
    // 1. Definisi Format yang Diizinkan (Sesuai request dosen)
    // Format array: 'ekstensi_user' => 'mime_type_output'
    $allowedFormats = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif'
    ];

    // 2. Validasi Format (Syarat Dosen)
    // Jika format user tidak ada di kunci array $allowedFormats
    if (!array_key_exists(strtolower($targetFormat), $allowedFormats)) {
        return json_encode([
            "success" => false,
            "error" => "Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF."
        ]);
    }

    $targetMime = $allowedFormats[strtolower($targetFormat)];

    // 3. Request ke AI (Stable Diffusion XL)
    // Kita tetap minta Stable Diffusion generate gambar (biasanya dia kasih JPEG)
    $hfToken = getenv('HF_TOKEN');
    $modelId = 'stabilityai/stable-diffusion-xl-base-1.0';
    $apiURL = "https://router.huggingface.co/hf-inference/models/$modelId";

    $englishPrompt = translateToEnglish($prompt);
    $enhancedPrompt = "Ultra-realistic Unreal Engine 5 cinematic render of {$englishPrompt}, 8k resolution, photorealistic.";

    $headers = [
        "Authorization: Bearer $hfToken",
        "Content-Type: application/json"
    ];

    $data = ["inputs" => $enhancedPrompt];

    $ch = curl_init($apiURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        // 4. Proses Konversi Gambar (Magic happens here)

        // Baca string gambar dari API (apapun format aslinya)
        $image = imagecreatefromstring($result);

        if (!$image) {
            return json_encode(["success" => false, "error" => "Gagal memproses gambar dari AI."]);
        }

        // Mulai buffer untuk menangkap hasil konversi
        ob_start();

        // Konversi sesuai target format user
        switch (strtolower($targetFormat)) {
            case 'png':
                imagepng($image); // Convert ke PNG
                break;
            case 'gif':
                imagegif($image); // Convert ke GIF
                break;
            case 'jpg':
            case 'jpeg':
            default:
                imagejpeg($image, null, 90); // Convert ke JPG (Quality 90)
                break;
        }

        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);

        // 5. Kirim Hasil
        $base64Image = base64_encode($imageData);
        return json_encode([
            "success" => true,
            "format_request" => $targetFormat,
            "image_base64" => "data:$targetMime;base64,$base64Image"
        ]);
    } else {
        $errorResp = json_decode($result, true);
        $pesanError = $errorResp['error'] ?? "Gagal (HTTP $httpCode).";
        if (strpos($pesanError, 'loading') !== false) {
            $pesanError = "Model sedang 'Warming Up'. Tunggu 30 detik.";
        }
        return json_encode(["success" => false, "error" => $pesanError]);
    }
}
