<?php

namespace AppBundle\DataFixtures\ORM;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;
use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserProcessor implements ProcessorInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function preProcess($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function postProcess($object)
    {
        if (!($object instanceof UserInterface)) {
            return;
        }

        return;

        /** @var UserManager $manager */
        $manager = $this->container->get('fos_user.user_manager');
        $manager->updateUser($object);
    }
}