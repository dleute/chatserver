<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Testwork\Hook\Call\BeforeSuite;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{

    use KernelDictionary;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

    }

    /**
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $event)
    {
        exec('php app/console doctrine:fixtures:load --env=test -n');

//        $em = $this->getContainer()->get('doctrine')->getManager();
//
//        $loader = new \Doctrine\Common\DataFixtures\Loader();
//        $loader->addFixture(new \AppBundle\DataFixtures\ORM\LoadUserData());
//
//        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
//        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
//        $executor->execute($loader->getFixtures());
//
//        echo "do some stuff\n";
        // prepare system for test suite
        // before it runs
    }

    /**
     * @Given I wait :arg1 for AJAX to finish
     */
    public function iWaitForAjaxToFinish2($time)
    {
        $this->getSession()->wait($time, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
    }

    /**
     * @Given I am logged in as :arg1
     */
    public function iAmLoggedInAs($username)
    {
        $this->visit('/');
        $this->clickLink("Log In");
        $this->fillField('username', $username);
        $this->fillField('password', 'test');
        $this->pressButton('Log In');
    }

}
