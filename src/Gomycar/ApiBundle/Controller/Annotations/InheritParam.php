<?php

namespace Gomycar\ApiBundle\Controller\Annotations;

/**
 * This will be used for annotation inheritance.
 * Contains the class and method of the class to inherit from
 *
 * @Annotation
 */
class InheritParam
{
    /** @var string */
    public $class;
    /** @var string */
    public $method = null;
}
