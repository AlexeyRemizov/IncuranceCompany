<?php
/**
 * Created by PhpStorm.
 * User: Alex Storchovyy
 * Date: 05.03.2017
 * Time: 16:23
 */

namespace AppBundle\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageResizer
{
    function makeThumbnail(UploadedFile $file, $dest, $desired_width) {

        $src = $file->getPathname();

        $filename = md5(time()).'.'.$file->guessExtension();


        $thumb = 'thumb_'.$filename;

        /* read the source image */
        if ($file->guessExtension() == 'png'){
            $source_image = imagecreatefrompng($src);


        } else {
            $source_image = imagecreatefromjpeg($src);
        }
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = $desired_width;

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        imagealphablending($virtual_image, false);
        $transparency = imagecolorallocatealpha($virtual_image, 0, 0, 0, 127);
        imagefill($virtual_image, 0, 0, $transparency);
        imagesavealpha($virtual_image, true);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        if ($file->guessExtension() == 'png') {
            imagepng($virtual_image, $dest.$thumb, 1);
        } else {

            imagejpeg($virtual_image, $dest.$thumb, 100);
        }
        $file->move('uploads/', $filename);

        return [$filename, $thumb];
    }
}