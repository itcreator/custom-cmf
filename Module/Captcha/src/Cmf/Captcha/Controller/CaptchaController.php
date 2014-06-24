<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Captcha\Controller;

use Cmf\Captcha\Captcha;
use Cmf\Controller\AbstractController;

/**
 * Controller for rendering of a captcha
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CaptchaController extends AbstractController
{
    /**
     * @param Captcha $captcha
     * @return void
     */
    public function buildImage(Captcha $captcha)
    {
        $config = $this->config;
        //creation of an image
        $width = $config->width;
        $height = $config->height;
        $path = $config->pathToDigits;
        $ext = '.png';
        $im = imagecreate($width, $height);

        //creation of a background color
        $wi = imagecolorallocate($im, $config->bgRed, $config->bgGreen, $config->bgBlue);

        //creation of a pen color
        $penColor = imagecolorallocate($im, $config->colorRed, $config->colorGreen, $config->colorBlue);

        ImageFill($im, 1, 1, $wi);

        $digits = $captcha->getDigits();
        $im1 = imagecreatefromPng($path. $digits[0]. $ext);
        $im2 = imagecreatefromPng($path. $digits[1]. $ext);
        $im3 = imagecreatefromPng($path. $digits[2]. $ext);
        $im4 = imagecreatefromPng($path. $digits[3]. $ext);
        $im5 = imagecreatefromPng($path. $digits[4]. $ext);

        $w1 = imageSX($im1);
        $h1 = imageSY($im1);
        $w2 = imageSX($im2);
        $h2 = imageSY($im2);
        $w3 = imageSX($im3);
        $h3 = imageSY($im3);
        $w4 = imageSX($im4);
        $h4 = imageSY($im4);
        $w5 = imageSX($im5);
        $h5 = imageSY($im5);

        $k = rand(-3, 3)/10;

        $k1x = $k + ($width / 5) / $w1;
        $k1y = $k + (3 * $height / 5) / $h1;
        $k2x = $k + ($width / 5) / $w2;
        $k2y = $k + (3 * $height / 5) / $h2;
        $k3x = $k + ($width / 5) / $w3;
        $k3y = $k + (3 * $height / 5) / $h3;
        $k4x = $k + ($width / 5) / $w4;
        $k4y = $k + (3 * $height / 5) / $h4;
        $k5x = $k + ($width / 5) / $w5;
        $k5y = $k + (3 * $height / 5) / $h5;

        $rndX = $width / 21;
        $rndY = $height / 10;

        $dstX = $rndX + rand(-1 * $rndX, $rndX);
        $dstY = $rndY + rand(-1 * $rndY, $rndY);
        imagecopyresampled($im, $im1, $dstX, $dstY, 0, 0, $w1 * $k1x, $h1 * $k1y, $w1, $h1);
        $dstX = $rndX + $width / 5 + rand(-1 * $rndX, $rndX);
        $dstY = $rndY + rand(-1 * $rndY, $rndY);
        imagecopyresampled($im, $im2, $dstX, $dstY, 0, 0, $w2 * $k2x, $h2 * $k2y, $w2, $h2);
        $dstX = $rndX + 2 * $width / 5 + rand(-1 * $rndX, $rndX);
        $dstY = $rndY + rand(-1 * $rndY, $rndY);
        imagecopyresampled($im, $im3, $dstX, $dstY, 0, 0, $w3 * $k3x, $h3 * $k3y, $w3, $h3);
        $dstX = $rndX + 3 * $width / 5 + rand(-1 * $rndX, $rndX);
        $dstY = $rndY + rand(-1 * $rndY, $rndY);
        imagecopyresampled($im, $im4, $dstX, $dstY, 0, 0, $w4 * $k4x, $h4 * $k4y, $w4, $h4);
        $dstX = $rndX + 4 * $width / 5 + rand(-1 * $rndX, $rndX);
        $dstY = $rndY + rand(-1 * $rndY, $rndY);
        imagecopyresampled($im, $im5, $dstX, $dstY, 0, 0, $w5 * $k5x, $h5 * $k5y, $w5, $h5);

        //draw grid
        for ($i = 0; $i <= 40; $i++) {
            imageline($im, $i * ($width - 1) / 30, 0, $i * ($width - 1) / 30, $height, $penColor);
        }
        for ($i = 0; $i <= 14; $i++) {
            imageline($im, 0, $i * ($height - 1) / 10, $width, $i * ($height - 1) / 10, $penColor);
        }

        //random lines

        $rndLines = $config->rndLines;
        for ($i = 0; $i < $rndLines; $i++) {
            $x1 = rand(-30, $width + 30);
            $y1 = rand(-10, $height + 10);
            $x2 = rand(-30, $width + 30);
            $y2 = rand(-10, $height + 10);
            imageline($im, $x1, $y1, $x2, $y2, $penColor);
        }

        //resizing
        $k = 0.7;

        //new image
        $im1 = imagecreatetruecolor($width * $k, $height * $k);

        // resize image
        //imagecopyresized($im1, $im, 0, 0, 0, 0, 101*$k, 26*$k, 101, 26);

        //new image with normal size
        $im2 = imagecreatetruecolor($width, $height);

        //minimization of a image
        imagecopyresampled($im1, $im, 0, 0, 0, 0, $width * $k, $height * $k, $width, $height);
        imagecopyresampled($im2, $im1, 0, 0, 0, 0, $width, $height, $width * $k, $height * $k);

        //generation of a image
        header("Content-type: image/png");
        imagepng($im1);

        //clear memory
        imagedestroy($im4);
        imagedestroy($im3);
        imagedestroy($im2);
        imagedestroy($im1);
        imagedestroy($im);
    }

    public function defaultAction()
    {
        $captcha = new Captcha();
        $captcha->generateCode();
        $this->buildImage($captcha);

        //TODO: make MVC response for images
        die;
    }
}
