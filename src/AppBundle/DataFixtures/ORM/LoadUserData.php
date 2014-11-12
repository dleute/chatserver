<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var UserManager $um */
        $um = $this->container->get('fos_user.user_manager');

        $test1 = $um->createUser();
        $test1->setUsername('testuser1');
        $test1->setEmail('test1@test.com');
        $test1->setPlainPassword('test');
        $test1->setEnabled(true);
        $manager->persist($test1);

        $test2 = $um->createUser();
        $test2->setUsername('testuser2');
        $test2->setEmail('test2@test.com');
        $test2->setPlainPassword('test');
        $test2->setEnabled(true);
        $manager->persist($test2);

        $test3 = $um->createUser();
        $test3->setUsername('testuser3');
        $test3->setEmail('test3@test.com');
        $test3->setPlainPassword('test');
        $test3->setEnabled(true);
        $manager->persist($test3);

        $test4 = $um->createUser();
        $test4->setUsername('testuser4');
        $test4->setEmail('test4@test.com');
        $test4->setPlainPassword('test');
        $test4->setEnabled(true);
        $manager->persist($test4);
        
        $manager->flush();
    }
}