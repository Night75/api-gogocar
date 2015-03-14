<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Quote;

class QuoteManager extends AbstractManager
{

    /**
     * Return the repository for the default class.
     *
     * @return mixed
     */
    protected function getEntityClass()
    {
        return Quote::class;
    }
}