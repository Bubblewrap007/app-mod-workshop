<?php
function gcs_get_token() {
    $opts = ['http' => ['header' => 'Metadata-Flavor: Google']];
    $response = @file_get_contents(
        'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token',
        false,
        stream_context_create($opts)
    );
    if (!$response) return null;
    $data = json_decode($response, true);
    return isset($data['access_token']) ? $data['access_token'] : null;
}

function gcs_upload($bucket, $objectName, $filePath, $contentType) {
    $token = gcs_get_token();
    if (!$token) return false;

    $url = 'https://storage.googleapis.com/upload/storage/v1/b/'
        . urlencode($bucket) . '/o?uploadType=media&name=' . urlencode($objectName);

    $fh = fopen($filePath, 'r');
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_PUT           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER    => [
            'Authorization: Bearer ' . $token,
            'Content-Type: ' . $contentType,
            'Content-Length: ' . filesize($filePath),
        ],
        CURLOPT_INFILE        => $fh,
        CURLOPT_INFILESIZE    => filesize($filePath),
    ]);
    $result = curl_exec($ch);
    $code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fh);

    return $code === 200;
}

function gcs_public_url($bucket, $objectName) {
    return 'https://storage.googleapis.com/' . $bucket . '/' . ltrim($objectName, '/');
}
