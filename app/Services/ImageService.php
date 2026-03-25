<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    /**
     * Xử lý upload ảnh: Resize + Nén
     *
     * @param UploadedFile $file
     * @param string $directory - Thư mục đích (ví dụ: 'images/images_san_pham')
     * @param array $options - Tùy chọn: ['max_width' => 1200, 'quality' => 75]
     * @return string - Tên file đã lưu
     */
    public static function handleUpload(UploadedFile $file, string $directory = 'images/images_san_pham', array $options = [])
    {
        // Mặc định
        $maxWidth = $options['max_width'] ?? 1200;
        $maxHeight = $options['max_height'] ?? 1200;
        $quality = $options['quality'] ?? 80;

        // Tạo tên file sạch (loại bỏ timestamp, lấy tên gốc slugify)
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slugName = \Illuminate\Support\Str::slug($baseName);
        
        $filename = $slugName . '.webp';
        $filePath = public_path($directory . '/' . $filename);

        // Kiểm tra trùng tên và thêm hậu tố nếu cần
        $counter = 1;
        while (file_exists($filePath)) {
            $filename = $slugName . '-' . $counter . '.webp';
            $filePath = public_path($directory . '/' . $filename);
            $counter++;
        }

        // Đọc ảnh
        $image = Image::read($file->getPathname());

        // Kiểm tra kích thước và resize nếu cần
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scaleDown(width: $maxWidth, height: $maxHeight);
        }

        // Lưu dưới định dạng WebP
        $image->toWebp(quality: $quality)->save($filePath);

        return $filename;
    }

    /**
     * Xóa ảnh cũ
     *
     * @param string|null $filename
     * @param string $directory
     * @return void
     */
    public static function deleteFile(?string $filename, string $directory = 'images/images_san_pham')
    {
        if (!$filename) {
            return;
        }

        $filePath = public_path($directory . '/' . $filename);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
