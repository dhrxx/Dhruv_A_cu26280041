<?php
// Build an image upload feature for product photos with validation of file type, size, and unique file naming in PHP.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';

final class ProductUploadException extends RuntimeException
{
}

function product_image_upload(string $field = 'photo'): ?string
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        throw new ProductUploadException('The image upload did not complete successfully.');
    }
    if ($file['size'] < 1 || $file['size'] > 2 * 1024 * 1024) {
        throw new ProductUploadException('Product images must be no larger than 2 MB.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime]) || @getimagesize($file['tmp_name']) === false) {
        throw new ProductUploadException('Upload a valid JPEG, PNG, or WebP image.');
    }
    $directory = __DIR__ . '/uploads';
    if (!is_dir($directory) && !mkdir($directory, 0750) && !is_dir($directory)) {
        throw new ProductUploadException('Could not prepare the product image directory.');
    }
    $filename = bin2hex(random_bytes(20)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
        throw new ProductUploadException('Could not save the uploaded product image.');
    }
    return 'uploads/' . $filename;
}
