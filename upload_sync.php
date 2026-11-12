<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

if (!isset($_FILES['sync_file']) || $_FILES['sync_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Gagal menerima file unggahan.']);
    exit;
}

$targetFile = __DIR__ . '/FILE SYNC.xlsx';
$timestamp = date('Y-m-d_H-i-s');
$backupInfo = '';

// Jika file FILE SYNC.xlsx sudah ada, rename file lama dengan ujung tgl_jam-menit-detik
if (file_exists($targetFile)) {
    $backupFile = __DIR__ . '/FILE SYNC_' . $timestamp . '.xlsx';
    if (rename($targetFile, $backupFile)) {
        $backupInfo = ' File lama diarsipkan menjadi: FILE SYNC_' . $timestamp . '.xlsx.';
    }
}

// Simpan file baru sebagai FILE SYNC.xlsx
if (move_uploaded_file($_FILES['sync_file']['tmp_name'], $targetFile)) {
    echo json_encode([
        'success' => true,
        'message' => 'File baru berhasil disimpan sebagai FILE SYNC.xlsx!' . $backupInfo
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan file baru ke direktori server. Periksa izin folder (CHMOD).'
    ]);
}
?>