<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
//use Behat\Behat\;
//use Behat\Testwork\Hook\Scope\;


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

    /** @BeforeFeature */
    public static function prepare()
    {
        exec('php app/console doctrine:fixtures:load --env=test -n');
    }

    /**
     * @Given I wait :arg1 for AJAX to finish
     */
    public function iWaitForAjaxToFinish2($time)
    {
        $this->getSession()->wait($time, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
        usleep(300000);
        $this->getSession()->wait($time, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
//        $this->getSession()->wait($time, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');


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

    /**
     * @When I enter :arg1 in :arg2 field
     */
    public function iEnterInField($value, $field)
    {
        $this->getSession()->getDriver()->setValue('//*[@name="'.$field.'"]', $value);
//        session->getDriver()->setValue('//*[@id="'.$key.'"]', $value);
    }


}
