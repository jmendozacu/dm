<?php

namespace DiamondMansion\Extensions\Override\Mageplaza\ProductFeed\Helper;

class Data extends \Mageplaza\ProductFeed\Helper\Data
{
    public function getProductsData($feed) {
        $productCollection = parent::getProductsData($feed);

        $newCollection = clone $productCollection;
        $newCollection->removeAllItems();

        $index = 0;
        foreach ($productCollection as $product) {
            if (strpos($product->getTypeId(), 'dm_ring') === false) {
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
}