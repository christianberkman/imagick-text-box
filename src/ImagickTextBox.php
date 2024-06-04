<?php
/**
 * imagick-textbox
 * Draw a simple textbox with imagick
 * (c) Christian Berkman, May 2024
 * Add a simple text box to an image using PHP's Imagick Library
 */
namespace ImagickTextBox;

use \Imagick;
use \ImagickDraw;

class ImagickTextBox{
    /**
     * String to print
     */
    public $string = 'Hello World';
    
    /**
     * Margin and padding (px)
     */
    public $margin_x = 10;
    public $margin_y = 10;
    public $padding = 5;
    
    /**
     * Gravity
     */
    public $gravity = Imagick::GRAVITY_SOUTHWEST;

    /**
     * Imagick Objects
     */
    public Imagick $image;
    public ImagickDraw $text;
    public ImagickDraw $box;

    /**
     * Constructor
     *
     * @param Imagick $image
     */
    public function __construct(Imagick $image){
        // Set image object
        $this->image = $image;
        
        // Initialize box object
        $this->box = new ImagickDraw();
        $this->box->setFillColor('white');

        // Initialize text object
        $this->text = new ImagickDraw();
        $this->text->setFillColor('black');
        $this->text->setFontSize(24);
    }

    /**
     * Draw the text box onto the image
     *
     * @param integer|null $gravity
     * @return void
     */
    public function draw(?int $gravity = null) {
        if(! empty($gravity) ) $this->gravity = $gravity;
        
        $this->queryMetrics();
        $this->drawBox();
        $this->drawText();        
    }

    /**
     * Return if the text fits onto the image
     *
     * @return boolean
     */
    public function textFits() : bool{
        $this->queryMetrics();
        return ( $this->text_width < $this->image->width && $this->text_height < $this->image->height);
    }

    /**
     * Query Font Metrics
     */
    public float $heightFactor = 0.6;
    protected array $metrics;
    protected int $text_width, $text_height;

    private function queryMetrics() : void{
        $metrics = $this->image->queryFontMetrics($this->text, $this->string, false);
        $this->text_width = $metrics['textWidth'];
        $this->text_height = $metrics['textHeight'] * $this->heightFactor;
    }

    /**
     * Draw the box object onto the image
     */
    private function drawBox() : void{
        $this->calcBoxCoords();

        $this->box->rectangle(
            $this->box_x1,
            $this->box_y1,
            $this->box_x2,
            $this->box_y2 
        );

        $this->image->drawImage($this->box);
    }
    
    /**
     * Draw the text image onto the image
     */
    private function drawText() : void{
        $this->calcTextCoords();
        
        $this->image->annotateImage(
            $this->text,    // text object
            $this->text_x,  
            $this->text_y,
            0,              // angle
            $this->string   // string
        );
    }

    /**
     * Calculate box coordinates
     */
    private int $box_x1, $box_y1, $box_x2, $box_y2;
    
    private function calcBoxCoords() : void{

        $box_width = $this->text_width + $this->padding*2;
        $box_height = $this->text_height + $this->padding*2;
        
        switch($this->gravity){
            case Imagick::GRAVITY_NORTHWEST:
            default:
                $anchor_x = $this->margin_x;
                $anchor_y = $this->margin_y;

                $this->box_x1 = $anchor_x;
                $this->box_x2 = $anchor_x + $box_width;
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y + $box_height;
            break;

            case Imagick::GRAVITY_NORTH:
                $anchor_x = $this->image->width /2;
                $anchor_y = 0;
                
                $this->box_x1 = $anchor_x + $this->margin_x - ($box_width / 2);
                $this->box_x2 = $anchor_x + ($box_width /2);
                $this->box_y1 = $anchor_y + $this->margin_y;
                $this->box_y2 = $anchor_y + $this->text_height + $this->margin_y + $this->padding*2;
            break;

            case Imagick::GRAVITY_NORTHEAST:
                $anchor_x = $this->image->width - $box_width - $this->margin_x;
                $anchor_y = 0 + $this->margin_y;

                $this->box_x1 = $anchor_x;
                $this->box_x2 = $anchor_x + $box_width;
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y + $box_height;
            break;

            case Imagick::GRAVITY_EAST:
                $anchor_x = $this->image->width - $box_width - $this->margin_x;
                $anchor_y = ($this->image->height /2) - ($box_height / 2);
                
                $this->box_x1 = $anchor_x;
                $this->box_x2 = $anchor_x + $box_width;
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y + $box_height;
            break;


            case Imagick::GRAVITY_SOUTHEAST:
                $anchor_x = $this->image->width - $box_width - $this->margin_x;
                $anchor_y = $this->image->height - $box_height - $this->margin_y;

                $this->box_x1 = $anchor_x;
                $this->box_x2 = $anchor_x + $box_width;
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y + $box_height;

            break;

            case Imagick::GRAVITY_SOUTH:
                $anchor_x = ($this->image->width / 2) + $this->margin_x;
                $anchor_y = $this->image->height - $this->margin_y;
                
                $this->box_x1 = $anchor_x - ($box_width / 2);
                $this->box_x2 = $anchor_x + ($box_width /2);
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y - $box_height;
            break;
            
            case Imagick::GRAVITY_SOUTHWEST:
                $anchor_x = 0 + $this->margin_x;
                $anchor_y = $this->image->height - $this->margin_y;
                
                $this->box_x1 = $anchor_x; 
                $this->box_x2 = $anchor_x + $box_width;
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y - $box_height;
            break;

            case Imagick::GRAVITY_WEST:
                $anchor_x = $this->margin_x;
                $anchor_y = ($this->image->height / 2 ) - ($box_height / 2);

                $this->box_x1 = $anchor_x;
                $this->box_x2 = $anchor_x + $box_width;
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y + $box_height;
            break;

            case Imagick::GRAVITY_CENTER:
                $anchor_x = ($this->image->width / 2) + $this->margin_x;
                $anchor_y = ($this->image->height / 2 ) - ($box_height / 2);

                $this->box_x1 = $anchor_x - ($box_width / 2);
                $this->box_x2 = $anchor_x + ($box_width /2);
                $this->box_y1 = $anchor_y;
                $this->box_y2 = $anchor_y + $box_height;
            break;
        }
    }
        
    /**
     * Calculate text coordinates
     */
    private int $text_x, $text_y;

    private function calcTextCoords() : void{
        switch($this->gravity){
            case Imagick::GRAVITY_NORTHWEST:
            default:
                $this->text_x = $this->margin_x + $this->padding;
                $this->text_y = $this->margin_y + $this->text_height + $this->padding;
            break;
            
            case Imagick::GRAVITY_NORTH:
                $this->text_x = ($this->image->width / 2) - ($this->text_width /2) + $this->padding;
                $this->text_y = $this->margin_y + $this->text_height + $this->padding;
            break;

            case Imagick::GRAVITY_NORTHEAST:
                $this->text_x = $this->image->width - $this->text_width - $this->margin_x - $this->padding;
                $this->text_y = $this->margin_y + $this->text_height + $this->padding;
            break;

            case Imagick::GRAVITY_EAST:
                $this->text_x = $this->image->width - $this->text_width - $this->margin_x - $this->padding;
                $this->text_y = ($this->image->height / 2) + ($this->text_height / 2);
            break;

            case Imagick::GRAVITY_SOUTHEAST:
                $this->text_x = $this->image->width - $this->text_width - $this->margin_x - $this->padding;
                $this->text_y = ($this->image->height - $this->margin_y) - $this->padding;
            break;

            case Imagick::GRAVITY_SOUTH:
                $this->text_x = ($this->image->width / 2) - ($this->text_width /2) + $this->padding;
                $this->text_y = ($this->image->height - $this->margin_y) - $this->padding;
            break;
            
            case Imagick::GRAVITY_SOUTHWEST:
                $this->text_x = $this->margin_x + $this->padding;
                $this->text_y = ($this->image->height - $this->margin_y) - $this->padding;
            break;

            case Imagick::GRAVITY_WEST:
                $this->text_x = $this->margin_x + $this->padding;
                $this->text_y = ($this->image->height / 2) + ($this->text_height / 2);
            break;  

            case Imagick::GRAVITY_CENTER:
                $this->text_x = ($this->image->width / 2) - ($this->text_width /2) + $this->padding;
                $this->text_y = ($this->image->height / 2) + ($this->text_height / 2);
            break;
        }
     }

}