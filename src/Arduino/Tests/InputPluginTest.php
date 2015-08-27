<?php

namespace WeCamp\Ardo\Arduino\Tests;

use WeCamp\Ardo\Arduino\InputPlugin;

/**
 * Tests for the InputPlugin class
 *
 * @coversDefaultClass WeCamp\Arduino\InputPlugin
 */
class InputPluginTest extends \PHPUnit_Framework_TestCase
{
    /** @var InputPlugin */
    private $plugin;

    final public function setup()
    {
        $this->plugin = new InputPlugin();
    }

    final function testShouldComplainWhenNotGivenMessage()
    {
        $plugin = $this->plugin;

        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            '/none given/'
        );

        /** @noinspection PhpParamsInspection */
        $plugin->handleMessage();
    }
}

/*EOF*/