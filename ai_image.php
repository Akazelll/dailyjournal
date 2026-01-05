<?php
function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
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

            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }
}
loadEnv(__DIR__ . '/.env');

function translateToEnglish($text)
{
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

function generateImageAI($prompt)
{
    $hfToken = getenv('HF_TOKEN');

    $modelId = 'stabilityai/stable-diffusion-xl-base-1.0';

    $apiURL = "https://router.huggingface.co/hf-inference/models/$modelId";

    $englishPrompt = translateToEnglish($prompt);

    $enhancedPrompt = "Ultra-realistic Unreal Engine 5 cinematic render of {$englishPrompt}. 
    AAA game cinematic quality, photoreal PBR shading with correct roughness/metalness workflow, realistic subsurface scattering (skin/leaves), anisotropic highlights (hair/metal), micro-normal details, displacement-level surface depth. 
    Ray-traced reflections and refractions, Lumen global illumination, accurate bounce light, soft penumbra shadows, contact shadows, ambient occlusion, physically correct exposure and white balance. 
    Volumetric fog and god rays, subtle atmospheric perspective, realistic particles (fine dust / mist), natural depth cues, no flat lighting. 
    Cinematic composition (rule of thirds), sharp subject separation, shallow depth of field, clean bokeh, no chromatic mess. 
    Virtual production camera: full-frame sensor, 35mm prime lens, f/2.8, ISO 200, 1/120s, realistic perspective, correct scale, no stretching, no warped geometry. 
    Filmic ACES tonemapping, gentle bloom, subtle lens distortion, light film grain, high dynamic range, professional color grading, true-to-life color accuracy. 
    Ultra-detailed 8k render, clean edges, no aliasing, no artifacts, no text, no watermark, no cartoon/anime, no painterly style, no low-poly, no CGI toy look, no plastic materials, no over-sharpening, no oversmoothing.";


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
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($httpCode == 200 && strpos($contentType, 'image') !== false) {
        $base64Image = base64_encode($result);
        return json_encode([
            "success" => true,
            "translated_prompt" => $englishPrompt,
            "image_base64" => "data:$contentType;base64,$base64Image"
        ]);
    } else {
        $errorResp = json_decode($result, true);
        $pesanError = $errorResp['error'] ?? "Gagal (HTTP $httpCode). Respon: " . substr($result, 0, 150);

        if (strpos($pesanError, 'loading') !== false) {
            $pesanError = "Model sedang 'Warming Up' (Menyiapkan mesin). Tunggu 30 detik, lalu klik Generate lagi.";
        }

        return json_encode(["success" => false, "error" => $pesanError]);
    }
}
