<?php
/**
 * Created by May.
 * User: May
 * Date: 8/19/2018
 * Time: 5:25 PM
 */

namespace DiamondMansion\Extensions\Block\Page;

use \Magento\Backend\Block\Template;
use \Magento\Variable\Model\Variable;

class Notice extends \Magento\Backend\Block\Template
{
    public $variable;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Variable\Model\Variable $variable
    ) {
        $this->variable = $variable;

        parent::__construct($context);
    }

    public function getNotices() {
        $notices = [];
        $variables = $this->variable->getCollection();
        foreach ($variables as $variable) {
            $variable = $variable->load($variable->getId());
            $options = explode(',', $variable->getValue(Variable::TYPE_TEXT));
            if (!count($options)) {
                continue;
            }

            $duration = explode('~', $options[0]);
            if (count($duration) != 2) {
                continue;
            }

            $today = date('Y-m-d H:i:s');
            if (strpos($variable->getCode(), 'notice-header') !== false && $today > $duration[0] && $today < $duration[1]) {
                $notices[] = [
                    'message' => $variable->getValue(),
                    'options' => $options
                ];
            }
        }

        return $notices;
    }
}