<?php
// Direct
require __DIR__ .'/src/ImagickTextBox.php';

// Composer
//require __DIR__ .'/vendor/autoload.php';

use ImagickTextBox\ImagickTextBox;

$image = new Imagick();
$image->newImage(800, 400, 'gray');
$image->setImageFormat('jpg');

// TextBox at north west corner
$textBoxNW = new ImagickTextBox($image);
    $textBoxNW->string ='composer require christianberkman/imagick-text-box';
    $textBoxNW->box->setFillCOlor('navy blue');
    $textBoxNW->text->setFillColor('white');
$textBoxNW->draw(Imagick::GRAVITY_NORTHWEST);

// Text box at south east corner
$textBoxSE = new ImagickTextBox($image);
    $textBoxSE->string = 'use ImagickTextBox\ImagickTextBox;';
    $textBoxSE->text->setFillColor('navy blue');
$textBoxSE->draw(Imagick::GRAVITY_SOUTHEAST);

header('Content-Type: image/jpeg');
echo $image;

return;

?>