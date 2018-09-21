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



namespace Mirasvit\SeoAutolink\Helper;

/**
 * @covers \Mirasvit\SeoAutolink\Helper\Replace
 */
class ReplaceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\TestFramework\ObjectManager */
    protected $objectManager;

    /** @var \Mirasvit\SeoAutolink\Helper\Replace */
    protected $helper;

    /**
     * setUp.
     */
    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->helper = $this->objectManager->create('Mirasvit\SeoAutolink\Helper\Replace');
    }

    /**
     *
     * @covers  Mirasvit\SeoAutolink\Helper\Replace::getLinks
     *
     * @magentoDataFixture Mirasvit/SeoAutolink/_files/links.php
     */
    public function testGetLinks()
    {
        $links = $this->helper->getLinks('snow');
        $this->assertequals(1, $links->count());
        $this->assertequals(1, count($links));

        $links = $this->helper->getLinks('word word snow cat toyboy');
        $this->assertequals(3, count($links));

        $links = $this->helper->getLinks('mouse การส snow снегири');
        $this->assertequals(4, count($links));
    }

    /**
     * @test
     * @covers Mirasvit\SeoAutolink\Helper\Replace::_addLinks
     *
     * @magentoDataFixture Mirasvit/SeoAutolink/_files/links.php
     * @dataProvider providerParseData
     *
     * @param string $text
     * @param string $expectedResult
     */
    public function testAddLinks($text, $expectedResult)
    {
        $result = $this->helper->addLinks($text);

        $this->assertequals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function providerParseData()
    {
//@codingStandardsIgnoreStart
        return [
            ['snow',"<a href='http://snow.com' class='autolink' >snow</a>"],
            ['snow cat',"<a href='http://snow.com' class='autolink' >snow</a> <a href='http://cat.com' class='autolink' >cat</a>"],
            ['123 snow mouse cat',"123 <a href='http://snow.com' class='autolink' >snow</a> <a href='http://mouse.com' class='autolink' >mouse</a> <a href='http://cat.com' class='autolink' >cat</a>"],
            ['การส снегири',"<a href='http://fafafa.com' class='autolink' >การส</a> <a href='http://birds.com' class='autolink' >снегири</a>"],
            ['123 link1 456 link2', "123 <a href='http://link1.com' class='autolink' >link1</a> 456 <a href='http://link2.com' class='autolink' >link2</a>"],
            ['link1 link2 ', "<a href='http://link1.com' class='autolink' >link1</a> <a href='http://link2.com' class='autolink' >link2</a> "],
            ['link2 link3', "<a href='http://link2.com' class='autolink' >link2</a> link3"],
            ['Link2', "<a href='http://link2.com' class='autolink' >Link2</a>"],
            ['link1, Link2', "<a href='http://link1.com' class='autolink' >link1</a>, <a href='http://link2.com' class='autolink' >Link2</a>"],
            ['link2', "<a href='http://link2.com' class='autolink' >link2</a>"],
            ['link2text', 'link2text'],
            ['textlink2', 'textlink2'],
            ['textlink2text', 'textlink2text'],
            [',link2,', ",<a href='http://link2.com' class='autolink' >link2</a>,"],
            [',link2text', ',link2text'],
            [',спиннингtext', ',спиннингtext'],
            [',spinning text', ",<a href='http://spinning.com' class='autolink' >spinning</a> text"],
            ['textlink2,', 'textlink2,'],
            ['link2,', "<a href='http://link2.com' class='autolink' >link2</a>,"],
            [',link2', ",<a href='http://link2.com' class='autolink' >link2</a>"],
            ['Link2', "<a href='http://link2.com' class='autolink' >Link2</a>"],
            ['Link2text', 'Link2text'],
            ['textLink2', 'textLink2'],
            ['textLink2text', 'textLink2text'],
            [',Link2,', ",<a href='http://link2.com' class='autolink' >Link2</a>,"],
            [',Link2text', ',Link2text'],
            ['textLink2,', 'textLink2,'],
            ['Link2,', "<a href='http://link2.com' class='autolink' >Link2</a>,"],
            [',Link2', ",<a href='http://link2.com' class='autolink' >Link2</a>"],
            ['link1 ‘ ’ “ ” Link2', "<a href='http://link1.com' class='autolink' >link1</a> ‘ ’ “ ” <a href='http://link2.com' class='autolink' >Link2</a>"],
            ['Pinot Noir, link1 and Pinot Meunier link1', "Pinot Noir, <a href='http://link1.com' class='autolink' >link1</a> and Pinot Meunier <a href='http://link1.com' class='autolink' >link1</a>"],
            ['ขั้นตอนการสมัครสมาชิก ในการล็อกอินเพื่อสมัครสมาชิกสามารทำได้2วิธี เมื่อท่านเข้าสู่หน้าโฮมเพจของเราหากท่าน',
            "ขั้นตอนการสมัครสมาชิก <a href='http://thai.com' class='autolink' >ในการล็อกอินเพื่อสมัครสมาชิกสามารทำได้2วิธี</a> เมื่อท่านเข้าสู่หน้าโฮมเพจของเราหากท่าน"],
            ['With durable solid, wood solidp framing, generous padding and plush stain-resistant microfiber asdsolid. aaaaSolid. upholstery. Solid solid djaslkd asdkjklas ssolid, solid
solid,
solid
Solid.
Solid',

                "With durable <a href='http://solid.com' class='autolink' >solid</a>, wood solidp framing, generous padding and plush stain-resistant microfiber asdsolid. aaaaSolid. upholstery. <a href='http://solid.com' class='autolink' >Solid</a> <a href='http://solid.com' class='autolink' >solid</a> djaslkd asdkjklas ssolid, <a href='http://solid.com' class='autolink' >solid</a>
<a href='http://solid.com' class='autolink' >solid</a>,
<a href='http://solid.com' class='autolink' >solid</a>
<a href='http://solid.com' class='autolink' >Solid</a>.
<a href='http://solid.com' class='autolink' >Solid</a>"
            ],
        ];
//@codingStandardsIgnoreStop
    }
}
