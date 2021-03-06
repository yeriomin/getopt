<?php

namespace Yeriomin\Getopt;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-12-05 at 04:48:59.
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Parser
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Parser;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Yeriomin\Getopt\Parser::getOptionsLong
     * @covers Yeriomin\Getopt\Parser::getOptionsShort
     * @covers Yeriomin\Getopt\Parser::getArguments
     * @covers Yeriomin\Getopt\Parser::parse
     * @covers Yeriomin\Getopt\Parser::parseArg
     * @covers Yeriomin\Getopt\Parser::parseOption
     */
    public function testEmpty()
    {
        $this->object->parse(array());
        $optionsShort = $this->object->getOptionsShort();
        $this->assertEmpty($optionsShort);
        $optionsLong = $this->object->getOptionsLong();
        $this->assertEmpty($optionsLong);
        $arguments = $this->object->getArguments();
        $this->assertEmpty($arguments);
    }

    /**
     * @covers Yeriomin\Getopt\Parser::getOptionsLong
     * @covers Yeriomin\Getopt\Parser::getOptionsShort
     * @covers Yeriomin\Getopt\Parser::getArguments
     * @covers Yeriomin\Getopt\Parser::parse
     * @covers Yeriomin\Getopt\Parser::parseArg
     * @covers Yeriomin\Getopt\Parser::parseOption
     */
    public function testBooleans()
    {
        $this->object->parse(array('-s', '--ss'));
        $optionsShort = $this->object->getOptionsShort();
        $this->assertEquals(array('s' => true), $optionsShort);
        $optionsLong = $this->object->getOptionsLong();
        $this->assertEquals(array('ss' => true), $optionsLong);
        $arguments = $this->object->getArguments();
        $this->assertEmpty($arguments);
    }

    /**
     * @covers Yeriomin\Getopt\Parser::getOptionsLong
     * @covers Yeriomin\Getopt\Parser::getOptionsShort
     * @covers Yeriomin\Getopt\Parser::getOptionLong
     * @covers Yeriomin\Getopt\Parser::getOptionShort
     * @covers Yeriomin\Getopt\Parser::getArguments
     * @covers Yeriomin\Getopt\Parser::parse
     * @covers Yeriomin\Getopt\Parser::parseArg
     * @covers Yeriomin\Getopt\Parser::parseOption
     */
    public function testStrings()
    {
        $this->object->parse(array('-s', 'asd', '--ss', 'qwe'));
        $optionsShort = $this->object->getOptionsShort();
        $this->assertEquals(array('s' => 'asd'), $optionsShort);
        $optionShort1 = $this->object->getOptionShort('s');
        $this->assertEquals('asd', $optionShort1);
        $optionShort2 = $this->object->getOptionShort('z');
        $this->assertEquals(null, $optionShort2);
        $optionsLong = $this->object->getOptionsLong();
        $this->assertEquals(array('ss' => 'qwe'), $optionsLong);
        $optionLong1 = $this->object->getOptionLong('ss');
        $this->assertEquals('qwe', $optionLong1);
        $optionLong2 = $this->object->getOptionLong('zz');
        $this->assertEquals(null, $optionLong2);
        $arguments = $this->object->getArguments();
        $this->assertEmpty($arguments);
    }

    /**
     * @covers Yeriomin\Getopt\Parser::getOptionsLong
     * @covers Yeriomin\Getopt\Parser::getOptionsShort
     * @covers Yeriomin\Getopt\Parser::getArguments
     * @covers Yeriomin\Getopt\Parser::parse
     * @covers Yeriomin\Getopt\Parser::parseArg
     * @covers Yeriomin\Getopt\Parser::parseOption
     */
    public function testClustering()
    {
        $this->object->parse(array('arg1', '-sdf', 'arg2'));
        $optionsShort = $this->object->getOptionsShort();
        $this->assertEquals(
            array('s' => true, 'd' => true, 'f' => true),
            $optionsShort
        );
        $optionsLong = $this->object->getOptionsLong();
        $this->assertEmpty($optionsLong);
        $arguments = $this->object->getArguments();
        $this->assertEquals(array('arg1', 'arg2'), $arguments);
    }

    /**
     * @covers Yeriomin\Getopt\Parser::getOptionsLong
     * @covers Yeriomin\Getopt\Parser::getOptionsShort
     * @covers Yeriomin\Getopt\Parser::getArguments
     * @covers Yeriomin\Getopt\Parser::parse
     * @covers Yeriomin\Getopt\Parser::parseArg
     * @covers Yeriomin\Getopt\Parser::parseOption
     */
    public function testArguments()
    {
        $this->object->parse(array(
            $_SERVER['PHP_SELF'],
            'arg1',
            '-s',
            'asd',
            '--ss',
            'qwe',
            'arg2',
            '--tt',
            '--',
            '--arg3'
        ));
        $optionsShort = $this->object->getOptionsShort();
        $this->assertEquals(array('s' => 'asd'), $optionsShort);
        $optionsLong = $this->object->getOptionsLong();
        $this->assertEquals(array('ss' => 'qwe', 'tt' => true), $optionsLong);
        $arguments = $this->object->getArguments();
        $this->assertEquals(array('arg1', 'arg2', '--arg3'), $arguments);
        $this->assertNotContains($_SERVER['PHP_SELF'], $arguments);
    }
}
