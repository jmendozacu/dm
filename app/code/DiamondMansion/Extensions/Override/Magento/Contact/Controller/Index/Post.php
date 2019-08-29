<?php

namespace DiamondMansion\Extensions\Override\Magento\Contact\Controller\Index;

class Post extends \Magento\Contact\Controller\Index\Post
{
    public function execute()
    {
        parent::execute();
        
        return $this->resultRedirectFactory->create()->setPath('contactus/');
    }    
}