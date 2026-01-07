<?php
include_once "koneksi.php";

function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            $value = trim($value, '"\'');

            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

loadEnv(__DIR__ . '/.env');

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

function generateImageAI($prompt, $targetFormat = 'webp')
{
    $allowedFormats = [
        'webp' => 'image/webp',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif'
    ];

    $targetFormat = strtolower($targetFormat);

    if (!array_key_exists($targetFormat, $allowedFormats)) {
        return json_encode([
            "success" => false,
            "error" => "Format '$targetFormat' tidak didukung. Gunakan WEBP, JPG, atau PNG."
        ]);
    }

    $targetMime = $allowedFormats[$targetFormat];

    $hfToken = getenv('HF_TOKEN') ?: ($_ENV['HF_TOKEN'] ?? ($_SERVER['HF_TOKEN'] ?? ''));

    if (empty($hfToken)) {
        return json_encode([
            "success" => false,
            "error" => "Konfigurasi HF_TOKEN tidak ditemukan di .env atau environment server."
        ]);
    }

    $modelId = 'black-forest-labs/FLUX.1-schnell';
    $apiURL = "https://router.huggingface.co/hf-inference/models/$modelId";

    $englishPrompt = translateToEnglish($prompt);
    $enhancedPrompt = "A professional, high-quality cinematic photo of: {$englishPrompt}. Photorealistic, 8k, highly detailed.";

    $data = ["inputs" => $enhancedPrompt];

    $ch = curl_init($apiURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $hfToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        return json_encode(["success" => false, "error" => "Koneksi gagal: $curlError"]);
    }

    if ($httpCode == 200) {
        $image = @imagecreatefromstring($result);

        if (!$image) {
            return json_encode(["success" => false, "error" => "Data dari AI bukan gambar valid."]);
        }

        ob_start();
        switch ($targetFormat) {
            case 'webp':
                if (function_exists('imagewebp')) {
                    imagewebp($image, null, 80);
                } else {
                    imagejpeg($image, null, 90);
                }
                break;
            case 'png':
                imagepng($image);
                break;
            case 'gif':
                imagegif($image);
                break;
            case 'jpg':
            case 'jpeg':
            default:
                imagejpeg($image, null, 90);
                break;
        }
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);

        $base64Image = base64_encode($imageData);
        return json_encode([
            "success" => true,
            "format_used" => $targetFormat,
            "image_base64" => "data:$targetMime;base64,$base64Image"
        ]);
    } else {
        $errorResp = json_decode($result, true);
        $pesanError = $errorResp['error'] ?? "Gagal menghubungi AI (HTTP $httpCode).";

        if (strpos(strtolower($pesanError), 'loading') !== false) {
            $pesanError = "Model sedang 'Warming Up'. Tunggu sekitar 30 detik lalu coba lagi.";
        }

        return json_encode(["success" => false, "error" => $pesanError]);
    }
}
