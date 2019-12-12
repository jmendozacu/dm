<?php

namespace DiamondMansion\Extensions\Helper;

class Image extends Data
{
    public function getPlaceholderImageUrl($size = "")
    {
        return $this->getMediaUrl() . 'diamondmansion/placeholder/image' . $size . '.jpg';
    }

    public function getProductImageDir()
    {
        return $this->getMediaDir() . 'catalog' . DIRECTORY_SEPARATOR . 'design' . DIRECTORY_SEPARATOR;
    }

    public function getProductImagePath($option) {
        extract($option);
        $imagePath = "";
        if (isset($sku)) {
            $imagePath .= $sku . DIRECTORY_SEPARATOR;
        }
        if (isset($band) && $band == 'bridal-set') {
            $imagePath .= 'include' . DIRECTORY_SEPARATOR;
        }
        if (isset($type) && $type != "") {
            if ($type == 'natural') {
                $imagePath .= 'colorless' . DIRECTORY_SEPARATOR;
            } else {
                $imagePath .= $type . DIRECTORY_SEPARATOR;
            }
        }

        if (isset($metal) && $metal != "") {
            if (strpos($metal, "white") !== false || strpos($metal, "platinum") !== false) {
                $metal = "white";
            } else if (strpos($metal, "yellow") !== false) {
                $metal = "yellow";
            } else if (strpos($metal, "rose") !== false) {
                $metal = "rose";
            }

            $imagePath .= $metal . DIRECTORY_SEPARATOR;
        }

        if (isset($shape) && $shape != "") {
            $imagePath .= $shape . DIRECTORY_SEPARATOR;
        }

        if (isset($carat) && $carat != "") {
            $imagePath .= $carat . DIRECTORY_SEPARATOR;
        }

        if (isset($width) && $width != "") {
            $imagePath .= $width . DIRECTORY_SEPARATOR;
        }

        if (isset($finish) && $finish != "") {
            $imagePath .= $finish . DIRECTORY_SEPARATOR;
        }
        
        return $imagePath;
    }    

    public function getProductImages($option, $firstOnly = true)
    {
        $imagePath = $this->getProductImagePath($option);
        $dir = $this->getProductImageDir() . $imagePath;

        if (is_dir($dir)) {
            foreach (glob($dir . '*.*') as $imageFile) {
                if (!is_file($imageFile)) {
                    continue;
                }

                $fileName = basename($imageFile);

                $tmpImages = [
                    "main" => $this->_resize($imagePath, $fileName, 720),
                    "pop" => $this->_resize($imagePath, $fileName, ''),
                    "thumb" => $this->_resize($imagePath, $fileName, 90),
                    "filename" => $fileName,
                ];

                if ($firstOnly) {
                    $images = $tmpImages;
                    break;
                } else {
                    if (!isset($images)) {
                        $images = [];
                    }

                    $images[] = $tmpImages;
                }
            }
        }

        if (!isset($images)) {
            $product = $this->_productRepository->get($option['sku']);
            if ($product->getData('image') != 'no_selection' && $product->getData('image') != '') {
                $mediaUrl = $this->getMediaUrl();
                $tmpImages = [
                    "main" => $mediaUrl . 'catalog/product' . $product->getData('small_image'),
                    "pop" => $mediaUrl . 'catalog/product' . $product->getData('image'),
                    "thumb" => $mediaUrl . 'catalog/product' . $product->getData('thumbnail'),
                    "filename" => basename($product->getData('image')),
                ];

                if ($firstOnly) {
                    $images = $tmpImages;
                } else {
                    $images = [$tmpImages];
                }
            }
        }

        $placeholder = [
            "main" => $this->getPlaceholderImageUrl('_420x420'),
            "pop" => $this->getPlaceholderImageUrl(),
            "thumb" => $this->getPlaceholderImageUrl(),
            "filename" => "image.jpg",
        ];

        return isset($images) ? $images : ($firstOnly ? $placeholder : [$placeholder]);
    }

    private function _resize($relativePath, $fileName, $width, $height = '', $quality = 100)
    {
        if ($height == "") {
            $height = $width;
        }
        $folderURL = $this->getMediaUrl() . 'catalog/design/';
        $imageURL = $folderURL . $relativePath . $fileName;

        $basePath = $this->getProductImageDir() . $relativePath . $fileName;
        $newPath = $this->getProductImageDir() . "resized" . DIRECTORY_SEPARATOR . $relativePath . $width . "x" . $height . "_" . $fileName;
        if (!file_exists($this->getProductImageDir() . "resized" . DIRECTORY_SEPARATOR . $relativePath)) {
            mkdir($this->getProductImageDir() . "resized" . DIRECTORY_SEPARATOR . $relativePath, 0777, true);
        }

        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = $this->_imageFactory->create();
                $imageObj->open($basePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(false);
                $imageObj->keepFrame(FALSE);
                $imageObj->quality($quality);
                $imageObj->resize($width, $height);
                $imageObj->save($newPath);
            }
            $resizedURL = $folderURL . "resized/" . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath) . $width."x".$height."_".$fileName;
        } else {
            $resizedURL = str_replace(DIRECTORY_SEPARATOR, '/', $imageURL);
        }
        return $resizedURL;
    }
}