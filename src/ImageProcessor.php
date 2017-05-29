<?php

namespace Butterfly\Component\ImageProcessor;

use Imagine\Image;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\File\File;

class ImageProcessor
{
    /**
     * @param File $image
     * @param string $output
     * @param int $maxWidth
     * @param int $maxHeigth
     * @return File
     */
    public function normalizeSize(File $image, $output, $maxWidth, $maxHeigth)
    {
        $imagine = new Imagine();
        $size    = new Image\Box($maxWidth, $maxHeigth);
        $mode    = Image\ImageInterface::THUMBNAIL_INSET;

        $imagine
            ->open($image->getRealPath())
            ->thumbnail($size, $mode)
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($output);

        return new File($output);
    }

    /**
     * @param File $image
     * @param string $output
     * @param int $width
     * @param int $height
     * @return File
     */
    public function thumbnail(File $image, $output, $width, $height)
    {
        $imagine = new Imagine();
        $size    = new Image\Box($width, $height);
        $mode    = Image\ImageInterface::THUMBNAIL_OUTBOUND;

        $imagine
            ->open($image->getRealPath())
            ->thumbnail($size, $mode)
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($output);

        return new File($output);
    }

    /**
     * @param File $image
     * @param string $output
     * @param int $cropX
     * @param int $cropY
     * @param int $cropWidth
     * @param int $cropHeight
     * @param int $width
     * @param int $height
     * @param int $rotate
     * @param bool $scaleX
     * @param bool $scaleY
     * @return File
     */
    public function crop(File $image, $output, $cropX, $cropY, $cropWidth, $cropHeight, $width, $height, $rotate = 0, $scaleX = false, $scaleY = false)
    {
        $imagine = new Imagine();

        $file = $imagine->open($image->getRealPath());

        if ($rotate != 0) {
            $file->rotate($rotate);
        }
        if ($scaleX) {
            $file->flipHorizontally();
        }
        if ($scaleY) {
            $file->flipVertically();
        }

        $file
            ->crop(new Image\Point($cropX, $cropY), new Image\Box($cropWidth, $cropHeight))
            ->resize(new Image\Box($width, $height))
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($output);

        return new File($output);
    }

    /**
     * @param File $image
     * @param string $output
     * @return File
     */
    public function reSave(File $image, $output)
    {
        $imagine = new Imagine();

        $imagine
            ->open($image->getRealPath())
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($output);

        return new File($output);
    }
}
