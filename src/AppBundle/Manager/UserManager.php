<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;

class UserManager extends AbstractManager
{

    /**
     * Return the repository for the default class.
     *
     * @return mixed
     */
    protected function getEntityClass()
    {
        return User::class;
    }
}