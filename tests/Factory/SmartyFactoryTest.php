<?php

use Factory\SmartyFactory;

class SmartyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance(){
        $smarty = SmartyFactory::getInstance();

        $this->assertInstanceOf('\Smarty', $smarty);
    }
}

