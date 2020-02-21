<?php

namespace DiamondMansion\Extensions\Override\Mageplaza\ProductFeed\Helper;

class Data extends \Mageplaza\ProductFeed\Helper\Data
{
    public function generateLiquidTemplate($feed) {
        if ($feed->getName() == 'Setting Feed') {
            $data = $this->_getSettingsData();

            $content = "";
            foreach ($data as $row) {
                $content .= implode(',', $row) . "\r\n";
            }

            $this->file->checkAndCreateFolder(self::FEED_FILE_PATH);
            $fileName = $feed->getFileName() . '.' . $feed->getFileType();
            $fileUrl = self::FEED_FILE_PATH . '/' . $fileName;
            $this->file->write($fileUrl, $content);
    
            return count($data);
        } else {
            return parent::generateLiquidTemplate($feed);
        }
    }

    public function getProductsData($feed) {
        $productCollection = parent::getProductsData($feed);

        $newCollection = clone $productCollection;
        $newCollection->removeAllItems();

        $index = 0;
        foreach ($productCollection as $product) {
            if ($product->getVisibility() == "Not Visible Individually") {
                continue;
            }

            if (strpos($product->getTypeId(), 'dm_ring') === false) {
                if ($product->getTypeId() == 'configurable') {
                    $children = $product->getTypeInstance()->getUsedProducts($product);
                    foreach ($children as $child) {
                        $product->setData('final_price', $child->getFinalPrice());
                        break;
                    }
                }

                $index ++;
                $product->setId($index);

                $newCollection->addItem($product);

                continue;
            }

            if (strpos($feed->getName(), '(exclude design rings)') !== false) {
                continue;
            }

            $variations = $this->_getVariationsByProduct($product);

            foreach ($variations as $variation) {
                $index ++;
                $variation->setId($index);
                $newCollection->addItem($variation);
            }
        }

        return $newCollection;
    }

    protected function _getVariationsByProduct($product) {
        $variations = [];
        
        if ($product->getTypeId() == 'dm_ring_design') {
            $allOptions = $product->getAllDmOptions(true);

            if (!isset($allOptions['main-stone-type']) || 
                !isset($allOptions['main-stone-shape']) || 
                !isset($allOptions['main-stone-carat']) || 
                !isset($allOptions['main-stone-color']) || 
                !isset($allOptions['main-stone-clarity']) || 
                !isset($allOptions['metal'])) {
                return [];
            }

            $skus = [];
            foreach (['natural', 'yellow', 'black'] as $type) {
                if (isset($allOptions['main-stone-type'][$type])) {
                    $sku['main-stone-type'] = $allOptions['main-stone-type'][$type]->getSlug();
    
                    $values = json_decode($allOptions['main-stone-type'][$type]->getValues(), true);
                    $values = $values['children'];

                    if (!isset($values['main-stone-shape'])) {
                        continue;
                    }
                    foreach ($values['main-stone-shape'] as $shape) {
                        $sku['main-stone-shape'] = $allOptions['main-stone-shape'][$shape]->getSlug();
    
                        if (!isset($values['main-stone-carat'])) {
                            continue;
                        }    
                        foreach (['1.00', '2.00', '3.00'] as $carat) {
                            if (!in_array($carat, $values['main-stone-carat'])) {
                                continue;
                            }
    
                            $sku['main-stone-carat'] = $allOptions['main-stone-carat'][$carat]->getSlug();
    
                            if ($type == 'natural') {
                                $color = 'i+';
                                $clarity = 'vs1-vs2';
                            } else if ($type = 'yellow') {
                                $color = 'fancy-yellow';
                                $clarity = 'si1+';
                            } else if ($type = 'black') {
                                $color = 'fancy-black';
                                $clarity = 'aaa';
                            }
                            $sku['main-stone-color'] = (in_array($color, $values['main-stone-color'])) ? $allOptions['main-stone-color'][$color]->getSlug() : '0';
                            $sku['main-stone-clarity'] = (in_array($clarity, $values['main-stone-clarity'])) ? $allOptions['main-stone-clarity'][$clarity]->getSlug() : '0';

                            $sku['main-stone-cert'] = 'g';
    
                            foreach (['14k-white-gold', '14k-yellow-gold', '14k-rose-gold'] as $metal) {
                                if (!isset($allOptions['metal'][$metal])) {
                                    continue;
                                }
    
                                $sku['metal'] = $allOptions['metal'][$metal]->getSlug();
    
                                $newProduct = clone $product;
                                $newProduct->setFilters(['option' => implode('', $sku)]);
                                $newProduct->setIsCustomized(true);
    
                                if ($newProduct->getPrice() <= 0.001) {
                                    continue;
                                }

                                $newProduct->setData('final_price', round($newProduct->getPrice() / 10) * 10);
                                $newProduct->setData('link', $newProduct->getProductUrl());
                                $newProduct->setData('image_link', $newProduct->getImage());
                                $newProduct->setData('feed_title', $newProduct->getFeedTitle());
                                $newProduct->setData('feed_sku', $newProduct->getSku() . '-' . implode('', $sku));
                                $newProduct->setData('feed_category_path', $newProduct->getFeedCategoryPath());

                                $variations[] = $newProduct;
                            }
                        }
                    }
                }
            }
        } else {
            $product->setData('feed_title', $product->getName());
            $product->setData('feed_sku', $product->getSku());
            $variations[] = $product;
        }

        return $variations;
    }

    protected function _getSettingsData() {
        $productCollection = $this->productFactory->create()->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('type_id', 'dm_ring_design');

        $data = [];
        $data[] = [
            'Id','URL','ImagesURL','VideosURL','Price','Name','Description','Style','Metal','MetalWeight','RhodiumPlated ','WidthMin','WidthMax','SizeMin','SizeMax','RoundCompatible','RoundCaratMin','RoundCaratMax','CushionCompatible','CushionCaratMin','CushionCaratMax','OvalCompatible','OvalCaratMin','OvalCaratMax','PrincessCompatible','PrincessCaratMin','PrincessCaratMax','EmeraldCompatible','EmeraldCaratMin','EmeradlCaratMax','RadiantCompatible','RadiantCaratMin','RadiantCaratMax','PearCompatible','PearCaratMin','PearCaratMax','AsscherCompatible','AsscherCaratMin','AsscherCaratMax','MarquiseCompatible','MarquiseCaratMin','MarquiseCaratMax','HeartCompatible','HeartCaratMin','HeartCaratMax','SideDiamondsShape','SideDiamondsNumber','SideDiamondsWeight','SideDiamondsColor','SideDiamondsClarity','SideGemstone','SideGemstonesShape','SideGemstonesNumber','SideGemstonesSize','SideGemstonesColor','SideGemstonesClarity','SideGemstonesEnhancement'
        ];

        foreach ($productCollection as $product) {
            $allOptions = $product->getAllDmOptions(true);
            $defaultOptions = $product->getDefaultDmOptions();
    
            if (!isset($allOptions['main-stone-type']) || 
                !isset($allOptions['main-stone-type']['setting']) ||
                !isset($allOptions['main-stone-shape']) || 
                !isset($allOptions['main-stone-carat']) || 
                !isset($allOptions['main-stone-color']) || 
                !isset($allOptions['main-stone-clarity']) || 
                !isset($allOptions['metal'])) {
                return [];
            }
    
            foreach (['natural'] as $type) {
                if (!isset($allOptions['main-stone-type'][$type])) {
                    continue;
                }
                
                $skus = [];
    
                $skus['main-stone-type'] = $allOptions['main-stone-type']['setting']->getSlug();
    
                $values = json_decode($allOptions['main-stone-type'][$type]->getValues(), true);
                $values = $values['children'];
    
                if (!isset($values['main-stone-shape'])) {
                    continue;
                }
    
                foreach ($values['main-stone-shape'] as $shape) {
                    $skus['main-stone-shape'] = $allOptions['main-stone-shape'][$shape]->getSlug();
                    $skus['main-stone-carat'] = $defaultOptions['main-stone-carat']->getSlug();
                    $skus['main-stone-color'] = $defaultOptions['main-stone-color']->getSlug();
                    $skus['main-stone-clarity'] = $defaultOptions['main-stone-clarity']->getSlug();
                    $skus['main-stone-cert'] = $defaultOptions['main-stone-cert']->getSlug();
    
                    foreach ($allOptions['metal'] as $metal) {
    
                        $skus['metal'] = $metal->getSlug();
                        $skus['band'] = $defaultOptions['band']->getSlug();
    
                        $newProduct = clone $product;
                        $newProduct->setFilters(['option' => implode('', $skus)]);
                        $newProduct->setIsCustomized(true);
    
                        if ($newProduct->getPrice() <= 0.001) {
                            continue;
                        }

                        $settingStyle = $newProduct->getResource()->getAttribute('dm_setting_style')->getFrontend()->getValue($newProduct);
                        if (!in_array($settingStyle, ["Solitaire", "Three Stone", "Halo"])) {
                            $settingStyle = "Side Stone";
                        }
                        $rhodiumPlated = (strpos($metal->getTitle(), 'White') !== false || strpos($metal->getTitle(), 'Platinum') !== false) ? 1 : 0;

                        $data[] = [
                            '"' . $newProduct->getSku() . '-' . implode('', $skus) . '"',
                            '"' . $newProduct->getProductUrl() . '"',
                            '["' . $newProduct->getImage() . ']"',
                            '',
                            '"' . $newProduct->getPrice() . '"',
                            '"' . $newProduct->getName() . '"',
                            '"' . $newProduct->getDescription() . '"',
                            '"' . $settingStyle . '"',
                            '"' . $metal->getTitle() . '"',
                            '',
                            (strpos($metal->getTitle(), 'White') !== false || strpos($metal->getTitle(), 'Platinum') !== false) ? 1 : 0,
                            '',
                            '',
                            '',
                            '',
                            ($shape == 'round') ? 1 : 0, '', '',
                            ($shape == 'cushion') ? 1 : 0, '', '',
                            ($shape == 'oval') ? 1 : 0, '', '',
                            ($shape == 'princess') ? 1 : 0, '', '',
                            ($shape == 'emerald') ? 1 : 0, '', '',
                            ($shape == 'radiant') ? 1 : 0, '', '',
                            ($shape == 'pear') ? 1 : 0, '', '',
                            ($shape == 'asscher') ? 1 : 0, '', '',
                            ($shape == 'marquise') ? 1 : 0, '', '',
                            ($shape == 'heart') ? 1 : 0, '', '',
                            '','','','','','','','','','','','',
                        ];
                    }
                }
            }
        }
        
        return $data;
    }
}