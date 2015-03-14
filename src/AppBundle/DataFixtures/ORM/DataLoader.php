<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

class DataLoader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return [
            __DIR__ . '/data/User.yml',
            __DIR__ . '/data/Quote.yml',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getProcessors()
    {
        return [];
    }
}