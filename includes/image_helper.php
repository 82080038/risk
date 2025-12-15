<?php
/**
 * Image Helper Functions
 * Risk Assessment Objek Wisata
 * 
 * Helper functions untuk image processing menggunakan GD
 */

/**
 * Check if GD extension is available
 */
function isGDAvailable() {
    return extension_loaded('gd') && function_exists('imagecreate');
}

/**
 * Validate image file
 * 
 * @param string $file_path Path ke file gambar
 * @return array|false Array dengan info gambar atau false jika invalid
 */
function validateImageFile($file_path) {
    if (!isGDAvailable()) {
        return false;
    }
    
    if (!file_exists($file_path)) {
        return false;
    }
    
    $image_info = @getimagesize($file_path);
    
    if ($image_info === false) {
        return false;
    }
    
    return [
        'width' => $image_info[0],
        'height' => $image_info[1],
        'type' => $image_info[2],
        'mime' => $image_info['mime'],
        'bits' => isset($image_info['bits']) ? $image_info['bits'] : null,
        'channels' => isset($image_info['channels']) ? $image_info['channels'] : null
    ];
}

/**
 * Get image dimensions
 * 
 * @param string $file_path Path ke file gambar
 * @return array|false Array dengan width dan height atau false
 */
function getImageDimensions($file_path) {
    $info = validateImageFile($file_path);
    
    if ($info === false) {
        return false;
    }
    
    return [
        'width' => $info['width'],
        'height' => $info['height']
    ];
}

/**
 * Check if file is a valid image
 * 
 * @param string $file_path Path ke file
 * @return bool True jika valid image
 */
function isValidImage($file_path) {
    return validateImageFile($file_path) !== false;
}

/**
 * Get image MIME type
 * 
 * @param string $file_path Path ke file gambar
 * @return string|false MIME type atau false
 */
function getImageMimeType($file_path) {
    $info = validateImageFile($file_path);
    
    if ($info === false) {
        return false;
    }
    
    return $info['mime'];
}

/**
 * Create thumbnail (jika diperlukan di masa depan)
 * 
 * @param string $source_path Path ke file sumber
 * @param string $dest_path Path untuk menyimpan thumbnail
 * @param int $max_width Maximum width
 * @param int $max_height Maximum height
 * @return bool True jika berhasil
 */
function createThumbnail($source_path, $dest_path, $max_width = 200, $max_height = 200) {
    if (!isGDAvailable()) {
        return false;
    }
    
    $info = validateImageFile($source_path);
    
    if ($info === false) {
        return false;
    }
    
    $source_width = $info['width'];
    $source_height = $info['height'];
    $source_type = $info['type'];
    
    // Calculate thumbnail dimensions
    $ratio = min($max_width / $source_width, $max_height / $source_height);
    $thumb_width = (int)($source_width * $ratio);
    $thumb_height = (int)($source_height * $ratio);
    
    // Create image resource based on type
    switch ($source_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_path);
            break;
        default:
            return false;
    }
    
    if ($source_image === false) {
        return false;
    }
    
    // Create thumbnail
    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
    
    // Preserve transparency for PNG and GIF
    if ($source_type == IMAGETYPE_PNG || $source_type == IMAGETYPE_GIF) {
        imagealphablending($thumb_image, false);
        imagesavealpha($thumb_image, true);
        $transparent = imagecolorallocatealpha($thumb_image, 255, 255, 255, 127);
        imagefilledrectangle($thumb_image, 0, 0, $thumb_width, $thumb_height, $transparent);
    }
    
    // Resize
    imagecopyresampled($thumb_image, $source_image, 0, 0, 0, 0, 
                      $thumb_width, $thumb_height, $source_width, $source_height);
    
    // Save thumbnail
    $result = false;
    switch ($source_type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($thumb_image, $dest_path, 85);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($thumb_image, $dest_path, 9);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($thumb_image, $dest_path);
            break;
    }
    
    // Clean up
    imagedestroy($source_image);
    imagedestroy($thumb_image);
    
    return $result;
}

/**
 * Check if GD supports specific image type
 * 
 * @param int $image_type IMAGETYPE_JPEG, IMAGETYPE_PNG, etc.
 * @return bool True jika didukung
 */
function gdSupportsImageType($image_type) {
    if (!isGDAvailable()) {
        return false;
    }
    
    $gd_info = gd_info();
    
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            return isset($gd_info['JPEG Support']) && $gd_info['JPEG Support'];
        case IMAGETYPE_PNG:
            return isset($gd_info['PNG Support']) && $gd_info['PNG Support'];
        case IMAGETYPE_GIF:
            return isset($gd_info['GIF Support']) && $gd_info['GIF Support'];
        case IMAGETYPE_WEBP:
            return isset($gd_info['WebP Support']) && $gd_info['WebP Support'];
        default:
            return false;
    }
}

/**
 * Get GD Library information
 * 
 * @return array|false GD info array atau false
 */
function getGDInfo() {
    if (!isGDAvailable()) {
        return false;
    }
    
    return gd_info();
}

?>

