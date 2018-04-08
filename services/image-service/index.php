<?php
ob_start();
global $realPath, $imagePath, $config, $size;
require_once __DIR__ .'/vendor/autoload.php';
use Jcupitt\Vips;

$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
$imageDirectory = $baseDirectory . '/wp-content/uploads';
$imagePath = str_replace("/services/image-service","",$_SERVER['REQUEST_URI']);

function compressImage(\Imagick &$Image, int $quality) {
    $Clone = new \Imagick();
    $Clone->setCompressionQuality($quality);
    $Clone->newPseudoImage(
        $Image->getImageWidth(),
        $Image->getImageHeight(),
        'canvas:white'
    );

    $Clone->compositeImage(
        $Image,
        \Imagick::COMPOSITE_ATOP,
        0,
        0
    );

    $Clone->setFormat('jpeg');
    $Image = $Clone;
}

function getImageHeader(string $file): string {
    $ext = [
        'jpg|jpeg|jpe' => 'image/jpeg',
    	'gif' => 'image/gif',
    	'png' => 'image/png',
    	'bmp' => 'image/bmp',
    	'tiff|tif' => 'image/tiff',
    	'ico' => 'image/x-icon'
    ];

    foreach($ext as $regex => $header) {
        if(preg_match('/\.('.$regex.')$/',$file)) {
            return $header;
        }
    }
    return "";
}

function getRealImagePath(string $file): string {
    $ret = realpath($file);
    if(empty($ret)) {
        preg_match("/(.*)(\-[0-9]+x[0-9]+)(\.[A-Za-z0-9]+)$/",$file,$matches);
        $ret = realpath($matches[1].$matches[3]);
    }

    return $ret;
}

function getSize(string $file): array {
    preg_match("/-([0-9]+)x([0-9]+)\.[A-Za-z0-9]+/",$file,$matches);
    if(!empty($matches)) {
        return [
            'w' => $matches[1],
            'h' => $matches[2]
        ];
    }
    return [];
}

function resizeImage(\Imagick &$Image, int $width, int $height, int $filterType, float $blur, bool $bestFit = false, bool $cropZoom = false) {
    $Image->setOption( 'filter:support', '2.0' );
    $Image->resizeImage($width, $height, $filterType, $blur, $bestFit);

    $cropWidth = $Image->getImageWidth();
    $cropHeight = $Image->getImageHeight();

    if ($cropZoom) {
        $newWidth = $cropWidth / 2;
        $newHeight = $cropHeight / 2;

        $Image->cropimage(
            $newWidth,
            $newHeight,
            ($cropWidth - $newWidth) / 2,
            ($cropHeight - $newHeight) / 2
        );

        $Image->scaleimage(
            $Image->getImageWidth() * 4,
            $Image->getImageHeight() * 4
        );
    }
}

if(strlen($imagePath) > 0) {
    $imagePath = $imageDirectory . $imagePath;
    $size = getSize($imagePath);
    $filepath = getRealImagePath($imagePath);

    if(file_exists($filepath)) {
        $imgHeader = getImageHeader($imagePath);

        if(strlen($imgHeader) > 0) {
            header("Content-Type: {$imgHeader}");
            if($imgHeader == "image/gif") {
                $Image = new \Imagick($filepath);

                if(!empty($size)) {
                    resizeImage($Image,$size['w'],$size['h'],\Imagick::FILTER_TRIANGLE,1,false,false);
                }

                if($imgHeader == "image/jpeg") {
                    $Image->unsharpMaskImage( 0.25, 0.25, 8, 0.065 );
                    $Image->setOption( 'jpeg:fancy-upsampling', 'off' );

                    compressImage($Image,$config->compression->jpeg);
                    $Image->setCompression(\Imagick::COMPRESSION_JPEG);
                    $Image->setCompressionQuality($config->compression->jpeg);
                } elseif($imgHeader == "image/gif") {
                    $Image->setOption( 'png:compression-filter', '5' );
                    $Image->setOption( 'png:compression-level', '9' );
                    $Image->setOption( 'png:compression-strategy', '1' );
                    $Image->setOption( 'png:exclude-chunk', 'all' );
                }

                echo $Image->getImageBlob();
            } else {
                $Image = Vips\Image::newFromFile($filepath);

                if(!empty($size)) {
                    $ratio = $Image->width / $Image->height;
                    if($size['w'] == 0 && $size['h'] > 0) {
                        /**
                        *   width1 / height1 = width2 / height2
                        *   width1 = (width2 / height2) * height1
                        **/
                        $size['w'] = ($Image->width / $Image->height) * $size['h'];
                    } elseif($size['h'] == 0 && $size['w'] > 0) {
                        /**
                        *   width1 / height1 = width2 / height2
                        *   (width1 / width2) * height2 = height1
                        **/
                        $size['h'] = ($size['w'] / $Image->width) * $Image->height;
                    }

                    //error_log(print_r($size,true));
                    //error_log(print_r([$Image->width,$Image->height],true));
                    $Image = $Image->resize(
                        $size['w'] / $Image->width
                    );

                    if($Image->height > $size['h']) {
                        $Image = $Image->crop(
                            0,
                            intval(($Image->height - $size['h']) / 2),
                            $size['w'],
                            $size['h']
                        );
                    } else {
                        $Image = $Image->resize(
                            1,
                            [
                                "vscale" => ($size['h'] / $Image->height)
                            ]
                        );
                    }

                    /*$Image->resize($ratio,[
                        'width' => $size['w'],
                        'height' => $size['h']
                    ]);*/
                }

                $Image = $Image->gaussblur(0.3);
                //$Image->percent(0.1);
                if($imgHeader == "image/jpeg") {
                    echo $Image->jpegsave_buffer();
                } elseif($imgHeader == "image/gif") {
                    echo $Image->pngsave_buffer();
                } elseif($imgHeader == "image/png") {
                    echo $Image->pngsave_buffer();
                } elseif($imgHeader == "image/tiff") {
                    echo $Image->tiffsave_buffer();
                } elseif($imgHeader == "image/webp") {
                    echo $Image->webpsave_buffer();
                }
            }

            header("Content-Length: " . ob_get_length());
            ob_flush();
            exit;
        }
    }
}

http_response_code(404);
header("Content-Length: " . ob_get_length());
ob_flush();
exit;
