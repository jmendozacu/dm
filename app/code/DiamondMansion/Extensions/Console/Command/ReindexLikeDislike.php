<?php

namespace DiamondMansion\Extensions\Console\Command;

use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\App\State;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ReindexLikeDislike extends Command
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */

    private $state;

    protected $_connection;
    protected $_eavConfig;
    protected $_productFactory;
    protected $_likedislikeCollectionFactory;

    public function __construct(
        ResourceConnection $resource,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \DiamondMansion\Extensions\Model\ResourceModel\LikeDislike\CollectionFactory $likedislikeCollectionFactory,
        \Magento\Framework\App\State $state
    ) {
        $this->_connection = $resource->getConnection();
        $this->_eavConfig = $eavConfig;
        $this->_productFactory = $productFactory;
        $this->_likedislikeCollectionFactory = $likedislikeCollectionFactory;

        $this->state = $state;

        parent::__construct();
    }   
    
    protected function configure()
    {
        $this->setName('dm:reindex:likedislike')
            ->setDescription('Reindex like and dislike...');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);

        $output->writeln('<info>Starting...</info>');

        try {
            $collection = $this->_productFactory->create()->getCollection();

            foreach ($collection as $product) {
                $product = $this->_productFactory->create()->load($product->getId());
                $likes = $this->_likedislikeCollectionFactory->create()
                            ->addFieldToFilter('product_id', $product->getId())
                            ->addFieldToFilter('review', 1)
                            ->getSize();
                $dislikes = $this->_likedislikeCollectionFactory->create()
                            ->addFieldToFilter('product_id', $product->getId())
                            ->addFieldToFilter('review', 0)
                            ->getSize();
        
                if ($product->getDmLikes() != $likes || $product->getDmDislikes() != $dislikes) {
                    $output->writeln('WRONG SKU: ' . $product->getSku() . " Likes: " . $product->getDmLikes() . "-" . $likes . " Dislikes: " . $product->getDmDislikes() . "-" . $dislikes);
                    $product->setDmLikes($likes);
                    $product->setDmDislikes($dislikes);
                    //$product->save();
                } else {
                    $output->writeln('<info>CORRECT SKU: ' . $product->getSku() . " Likes: " . $product->getDmLikes() . "-" . $likes . " Dislikes: " . $product->getDmDislikes() . "-" . $dislikes . "</info>");
                }
                
                //$output->writeln('SKU: ' . $product->getSku());
            }

        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }

        
        $output->writeln('<info>Finished.</info>');

        return 0;
    }
}