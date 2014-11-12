<?php

namespace {
require_once dirname(__DIR__).'/../../app/AppKernel.php';
}

namespace AppBundle\Tests {

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\DependencyInjection\Container;

abstract class ContainerAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @return null
     */
    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();

        parent::setUp();
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        $this->kernel->shutdown();

        parent::tearDown();
    }
}
}