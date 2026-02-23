<?php
$sizes = [72, 96, 128, 144, 152, 192, 384, 512];

foreach ($sizes as $size) {
    $im = imagecreatetruecolor($size, $size);
    $bg = imagecolorallocate($im, 102, 126, 234); // #667eea
    imagefill($im, 0, 0, $bg);

    // Tambah teks "W"
    $textColor = imagecolorallocate($im, 255, 255, 255);
    $text = 'W';
    $fontSize = $size * 0.6;
    $font = 5; // built-in font
    $x = ($size - imagefontwidth($font) * strlen($text)) / 2;
    $y = ($size - imagefontheight($font)) / 2;
    imagestring($im, $font, $x, $y, $text, $textColor);

    imagepng($im, "icon-{$size}x{$size}.png");
    imagedestroy($im);
    echo "Generated icon-{$size}x{$size}.png\n";
}
