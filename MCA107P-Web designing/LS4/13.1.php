<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $message = 'Upload failed.';
    } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        $message = 'Image must be 2 MB or smaller.';
    } else {
        $tmp = $_FILES['image']['tmp_name'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif'];
        if (!isset($allowed[$mime])) {
            $message = 'Only JPG, PNG, and GIF files are allowed.';
        } else {
            $dir = __DIR__ . '/uploads';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
            if (move_uploaded_file($tmp, $dir . '/' . $filename)) {
                $message = 'Uploaded: uploads/' . $filename;
            } else {
                $message = 'Unable to save the image.';
            }
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Image Upload</title>
<style>body{font-family:Arial;margin:2rem}form{padding:1rem;border:1px solid #ccc;max-width:450px}</style>
</head><body><h1>Upload an Image</h1>
<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<form method="post" enctype="multipart/form-data">
<input type="file" name="image" accept="image/jpeg,image/png,image/gif" required>
<p>Maximum size: 2 MB. Allowed: JPG, PNG, GIF.</p><button>Upload</button></form></body></html>
