<?php

use \dotzero\Googl;
use \dotzero\GooglException;

class GooglTest extends PHPUnit_Framework_TestCase
{
    private $googl = null;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $apiKey = getenv('GOOGLE_API');
        $this->assertNotEmpty($apiKey, 'The Google URL Shortener API key must not be empty.');

        $this->googl = new Googl($apiKey);
    }

    public function testGooglShorten()
    {
        $actual = $this->googl->shorten('http://github.com/');

        $this->assertRegExp("#^https://goo.gl/(\w+)$#", $actual);
    }

    public function testGooglExpand()
    {
        $expected = 'http://github.com/';
        $actual = $this->googl->expand('https://goo.gl/KkZ8');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException dotzero\GooglException
     */
    public function testGooglException()
    {
        $this->googl->expand('foobar');
    }
}
