<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class PaintingImage {
 public function store(UploadedFile $file): string {
  $image = @imagecreatefromstring($file->get());
  if (!$image) throw ValidationException::withMessages(['image' => 'Изображение повреждено.']);
  $width = imagesx($image); $height = imagesy($image);
  $scale = min(1, 1400 / max($width, $height));
  $result = imagecreatetruecolor(max(1, (int) round($width*$scale)), max(1, (int) round($height*$scale)));
  imagefill($result, 0, 0, imagecolorallocate($result, 255, 255, 255));
  imagecopyresampled($result, $image, 0, 0, 0, 0, imagesx($result), imagesy($result), $width, $height);
  ob_start(); imagejpeg($result, null, 87); $bytes = ob_get_clean();
  imagedestroy($image); imagedestroy($result);
  $path = 'paintings/'.Str::uuid().'.jpg';
  if (!Storage::disk('public')->put($path, $bytes)) throw new \RuntimeException('Не удалось сохранить изображение.');
  return $path;
 }
}
