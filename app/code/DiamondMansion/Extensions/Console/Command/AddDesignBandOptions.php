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

class AddDesignBandOptions extends Command
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    protected $_connection;
    protected $_eavConfig;

    public function __construct(
        ObjectManagerFactory $objectManagerFactory,
        ResourceConnection $resource,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $params = $_SERVER;
        $params[StoreManager::PARAM_RUN_CODE] = 'admin';
        $params[StoreManager::PARAM_RUN_TYPE] = 'store';

        $this->_objectManager = $objectManagerFactory->create($params);
        $this->_connection = $resource->getConnection();
        $this->_eavConfig = $eavConfig;

        parent::__construct();
    }   
    
    protected function configure()
    {
        $this->setName('dm:addoptions:design-wedding-band')
            ->setDescription('Generate Wedding Band Design Options...');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting...</info>');

        $this->addWeddingBandDesignOptions($output);

        $output->writeln('<info>Finished.</info>');

        return 0;
    }

    public function addWeddingBandDesignOptions($output) {

        $this->_connection->query("DELETE FROM dm_options_group WHERE type='dm_wedding_band_design'");

        $options = [
            ['metal', '14k-white-gold',           '14K White Gold',           'c', 1, ''],
            ['metal', '18k-white-gold',           '18K White Gold',           'i', 0, ''],
            ['metal', '14k-yellow-gold',          '14K Yellow Gold',          'd', 0, ''],
            ['metal', '18k-yellow-gold',          '18K Yellow Gold',          'j', 0, ''],
            ['metal', '14k-rose-gold',            '14K Rose Gold',            'n', 0, ''],
            ['metal', '18k-rose-gold',            '18K Rose Gold',            'e', 0, ''],
            ['metal', '14k-tri-color-gold',       '14K Tri-Color Gold',       'o', 0, ''],
            ['metal', '18k-tri-color-gold',       '18K Tri-Color Gold',       'f', 0, ''],
            ['metal', '14k-two-tone-yellow-gold', '14K Two Tone Yellow Gold', 'b', 0, ''],
            ['metal', '18k-two-tone-yellow-gold', '18K Two Tone Yellow Gold', 'h', 0, ''],
            ['metal', '14k-two-tone-rose-gold',   '14K Two Tone Rose Gold',   'a', 0, ''],
            ['metal', '18k-two-tone-rose-gold',   '18K Two Tone Rose Gold',   'g', 0, ''],
            ['metal', 'platinum',                 'Platinum',                 'k', 0, ''],
            ['metal', 'platinum-two-tone-rose',   'Platinum Two Tone Rose',   'l', 0, ''],
            ['metal', 'platinum-two-tone-yellow', 'Platinum Two Tone Yellow', 'm', 0, ''],

            ['width', '3', '3 mm', 'a', 0, ''],
            ['width', '4', '4 mm', 'b', 0, ''],
            ['width', '5', '5 mm', 'c', 1, ''],
            ['width', '6', '6 mm', 'd', 0, ''],
            ['width', '7', '7 mm', 'e', 0, ''],
            ['width', '8', '8 mm', 'f', 0, ''],
            ['width', '9', '9 mm', 'g', 0, ''],
            ['width', '10', '10 mm', 'h', 0, ''],

            ['finish', 'no-finish',           'No Finish',        'a', 1, ''],
            ['finish', 'bead-blast-matte',    'Bead Blast/Matte', 'b', 0, ''],
            ['finish', 'florentine',          'Florentine',       'c', 0, ''],
            ['finish', 'glass-blast',         'Glass Blast',      'd', 0, ''],
            ['finish', 'hammer',              'Hammer',           'e', 0, ''],
            ['finish', 'ice',                 'Ice',              'f', 0, ''],
            ['finish', 'rock',                'Rock',             'g', 0, ''],
            ['finish', 'satin',               'Satin',            'h', 0, ''],
            ['finish', 'satin-hammer',        'Satin Hammer',     'i', 0, ''],
            ['finish', 'satin-rock',          'Satin Rock',       'j', 0, ''],
            ['finish', 'silk',                'Silk',             'k', 0, ''],
            ['finish', 'stone',               'Stone',            'l', 0, ''],

            ['ring-size', '3',    '3',    'a', 0, ''],
            ['ring-size', '3.25', '3.25', 'b', 0, ''],
            ['ring-size', '3.5',  '3.5',  'c', 0, ''],
            ['ring-size', '3.75', '3.75', 'd', 0, ''],
            ['ring-size', '4',    '4',    'e', 0, ''],
            ['ring-size', '4.25', '4.25', 'f', 0, ''],
            ['ring-size', '4.5',  '4.5',  'g', 0, ''],
            ['ring-size', '4.75', '4.75', 'h', 0, ''],
            ['ring-size', '5',    '5',    'i', 1, ''],
            ['ring-size', '5.25', '5.25', 'j', 0, ''],
            ['ring-size', '5.5',  '5.5',  'k', 0, ''],
            ['ring-size', '5.75', '5.75', 'l', 0, ''],
            ['ring-size', '6',    '6',    'm', 0, ''],
            ['ring-size', '6.25', '6.25', 'n', 0, ''],
            ['ring-size', '6.5',  '6.5',  'o', 0, ''],
            ['ring-size', '6.75', '6.75', 'p', 0, ''],
            ['ring-size', '7',    '7',    'q', 0, ''],
            ['ring-size', '7.25', '7.25', 'r', 0, ''],
            ['ring-size', '7.5',  '7.5',  's', 0, ''],
            ['ring-size', '7.75', '7.75', 't', 0, ''],
            ['ring-size', '8',    '8',    'u', 0, ''],
            ['ring-size', '8.25', '8.25', 'v', 0, ''],
            ['ring-size', '8.5',  '8.5',  'w', 0, ''],
            ['ring-size', '8.75', '8.75', 'x', 0, ''],
            ['ring-size', '9',    '9',    'y', 0, ''],
            ['ring-size', '9.25', '9.25', 'z', 0, ''],
            ['ring-size', '9.5',  '9.5',  '0', 0, ''],
            ['ring-size', '9.75', '9.75', '1', 0, ''],
            ['ring-size', '10',   '10',   '2', 0, ''],
        ];


        foreach ($options as $option) {
            $query = "INSERT INTO dm_options_group (`type`, `group`, `code`, `title`, `slug`, `is_default`, `values`) VALUES ('dm_wedding_band_design', '" . $option[0] . "', '" . $option[1] . "', '" . $option[2] . "', '" . $option[3] . "', " . $option[4] . ", '" . $option[5] . "')";
            $this->_connection->query($query);
        }
    }
}