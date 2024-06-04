# imagick-text-box
Add a simple text box to an image using PHP's Imagick Library

![Example](https://raw.githubusercontent.com/christianberkman/imagick-text-box/main/example.jpg)

# Installation
Composer:

`composer install christianberkman/imagick-text-box`

# Usage
```php
use \Imagick;
use ImagickTextBox\ImagickTextBox;

$image = new Imagick()->readImage('your-image.php');
$textBox = new ImagickTextBox($image);
$textBox->string = 'Hello World';
$textBox->draw(Imagick::GRAVITY_NORTHWEST);
```

Also see [example.php](https://github.com/christianberkman/imagick-text-box/blob/main/example.php) with a more detailed example of usage.

# Public methods
* `void __construct(Imagick $image)`
Construct the class and initialize ImagickDraw objects
* `void draw(int $gravity = Imagick::GRAVITY_SOUTHEST)` draw the textbox on the `Imagick $image` object
* `bool textFits()`
Returns if the text fits on the given image 

# Public Properties
## Imagick Objects
All these objects are fully accessible and can be manipulated to set desired fill color, font size, etc.
* `Imagick $image` Your image object
* `ImagickDraw $text` Text object, initialized by ImagickTextBox
* `ImagickDraw $box` Box object, initializes by ImagickTextBox

## Configuration
* `string $string = 'Hello World'` The string of text to print
* `int $margin_x = 10` Margin (px) from the side (does not apply to north, south, center)
* `int $margin_y = 10` Margin (px) from the top/bottom (does not apply to west, east, center)
* `int $padding = 5` Padding (px) of text inside text box
* `int $gravity = Imagick::GRAVITY_SOUTHWEST` Imagick Gravity Constant 
* `float $heightFactor = 0.6` Height factor to use. By using the default font, a factor of 1 produces a text box with a lot of vertical whitespace, 0.6 seems to do the trick.


