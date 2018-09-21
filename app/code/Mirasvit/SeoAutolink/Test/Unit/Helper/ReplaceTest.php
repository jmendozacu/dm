<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.0.85
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */


/**
 * for Magento 2.2
 * ./vendor/phpunit/phpunit/phpunit -d memory_limit=-1 -c
 * ./dev/tests/unit/phpunit.xml.dist
 * ./vendor/mirasvit/module-seo/src/SeoAutolink/Test/Unit/Helper
 */
namespace Mirasvit\SeoAutolink\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManager;

/**
 * @covers \Mirasvit\SeoAutolink\Helper\Replace
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RepalceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * setup tests.
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->replace = $objectManager->getObject(\Mirasvit\SeoAutolink\Helper\Replace::class);
        $this->data = $this->getParseData();
    }

    /**
     * dummy test.
     */
    public function test_addLinks()
    {
        foreach ($this->data as $value) {
            $this->assertEquals($value[2], $this->replace->_addLinks($value[0], $value[1], false));
        }
    }

    public function getParseData()
    {
        $link1 = new \Magento\Framework\DataObject(array(
            'keyword' => 'link1',
            'url' => 'http://link1.com',
        ));
        $link2 = new \Magento\Framework\DataObject(array(
            'keyword' => 'link2',
            'url' => 'http://link2.com',
        ));
        $link3 = new \Magento\Framework\DataObject(array(
            'keyword' => 'link2 link3',
            'url' => 'http://link3.com',
        ));
        $link4 = new \Magento\Framework\DataObject(array(
            'keyword' => 'спиннинг',
            'url' => 'http://spinning.com',
        ));
        $link5 = new \Magento\Framework\DataObject(array(
            'keyword' => 'spinning',
            'url' => 'http://spinning.com',
        ));
        $link6 = new \Magento\Framework\DataObject(array(
            'keyword' => 'solid',
            'url' => 'http://solid.com',
        ));
        $link7 = new \Magento\Framework\DataObject(array(
            'keyword' => 'ในการล็อกอินเพื่อสมัครสมาชิกสามารทำได้2วิธี',
            'url' => 'http://thai.com',
        ));
        $link8 = new \Magento\Framework\DataObject(array(
            'keyword' => '123link',
            'url' => 'http://123link.com',
        ));

        return array(
            array('link1 link2', array($link1, $link2, $link3), "<a href='http://link1.com' class='autolink' >link1</a> <a href='http://link2.com' class='autolink' >link2</a>"),
            array('link1 link2 link3', array($link1, $link2, $link3), "<a href='http://link1.com' class='autolink' >link1</a> <a href='http://link2.com' class='autolink' >link2</a> link3"),
            array("<a href='http://link1.com' class='autolink' >link1 aaaa</a>", array($link1, $link3, $link2), "<a href='http://link1.com' class='autolink' >link1 aaaa</a>"),
            array('link2 link3', array($link3, $link2), "<a href='http://link3.com' class='autolink' >link2 link3</a>"),
            array('Link2', array($link3, $link2), "<a href='http://link2.com' class='autolink' >Link2</a>"),
            array('Best spinnings ultra', array($link5), 'Best spinnings ultra'),
            array('Лучшие спиннинги ультралайт', array($link4), 'Лучшие спиннинги ультралайт'),

            array('link1, Link2', array($link1, $link2), "<a href='http://link1.com' class='autolink' >link1</a>, <a href='http://link2.com' class='autolink' >Link2</a>"),
            array('link2', array($link2), "<a href='http://link2.com' class='autolink' >link2</a>"),
            array('link2text', array($link2), 'link2text'),
            array('textlink2', array($link2), 'textlink2'),
            array('textlink2text', array($link2), 'textlink2text'),
            array(',link2,', array($link2), ",<a href='http://link2.com' class='autolink' >link2</a>,"),
            array(',link2text', array($link2), ',link2text'),
            array('textlink2,', array($link2), 'textlink2,'),
            array('link2,', array($link2), "<a href='http://link2.com' class='autolink' >link2</a>,"),
            array(',link2', array($link2), ",<a href='http://link2.com' class='autolink' >link2</a>"),
            array('Link2', array($link2), "<a href='http://link2.com' class='autolink' >Link2</a>"),
            array('Link2text', array($link2), 'Link2text'),
            array('textLink2', array($link2), 'textLink2'),
            array('textLink2text', array($link2), 'textLink2text'),
            array(',Link2,', array($link2), ",<a href='http://link2.com' class='autolink' >Link2</a>,"),
            array(',Link2text', array($link2), ',Link2text'),
            array('textLink2,', array($link2), 'textLink2,'),
            array('Link2,', array($link2), "<a href='http://link2.com' class='autolink' >Link2</a>,"),
            array(',Link2', array($link2), ",<a href='http://link2.com' class='autolink' >Link2</a>"),
            array('link1 ‘ ’ “ ” Link2', array($link2), "link1 ‘ ’ “ ” <a href='http://link2.com' class='autolink' >Link2</a>"),
//          array('Pinot Noir, link1 and Pinot Meunier link1', array($link1), "Pinot Noir, <a href='http://link1.com' class='autolink' >link1</a> and Pinot Meunier link1", 1),
            array('ขั้นตอนการสมัครสมาชิก ในการล็อกอินเพื่อสมัครสมาชิกสามารทำได้2วิธี เมื่อท่านเข้าสู่หน้าโฮมเพจของเราหากท่าน',
                array($link7),
                "ขั้นตอนการสมัครสมาชิก <a href='http://thai.com' class='autolink' >ในการล็อกอินเพื่อสมัครสมาชิกสามารทำได้2วิธี</a> เมื่อท่านเข้าสู่หน้าโฮมเพจของเราหากท่าน", ),
            array(
                'With durable solid, wood solidp framing, generous padding and plush stain-resistant microfiber asdsolid. aaaaSolid. upholstery. Solid solid djaslkd asdkjklas ssolid, solid
solid,
solid
Solid.
Solid',
                array($link6),
                "With durable <a href='http://solid.com' class='autolink' >solid</a>, wood solidp framing, generous padding and plush stain-resistant microfiber asdsolid. aaaaSolid. upholstery. <a href='http://solid.com' class='autolink' >Solid</a> <a href='http://solid.com' class='autolink' >solid</a> djaslkd asdkjklas ssolid, <a href='http://solid.com' class='autolink' >solid</a>
<a href='http://solid.com' class='autolink' >solid</a>,
<a href='http://solid.com' class='autolink' >solid</a>
<a href='http://solid.com' class='autolink' >Solid</a>.
<a href='http://solid.com' class='autolink' >Solid</a>",
            ),
            array('text 123link text,', array($link8), "text <a href='http://123link.com' class='autolink' > 123link </a> text,"),
        );
    }
}
