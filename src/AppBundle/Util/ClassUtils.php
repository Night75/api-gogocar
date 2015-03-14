<?php

namespace AppBundle\Util;

use Doctrine\Common\Util\ClassUtils as BaseUtils;

class ClassUtils extends BaseUtils
{
    /**
     * Build an object from another
     *
     * @param object $object
     * @param object $newObjectClass
     *
     * @return mixed
     */
    public static function buildObjectFromAnother($object, $newObjectClass)
    {
        $model = new $newObjectClass();

        $rObject = new \ReflectionObject($object);
        $rMethods = $rObject->getMethods();

        foreach ($rMethods as $rMethod) {
            // Check for getters
            if (!$rMethod->isPublic()
                || $rMethod->getNumberOfRequiredParameters() != 0
                || substr($rMethod->getName(), 0, 3) != 'get'
            ) {
                continue;
            }
            $getter = $rMethod->getName();
            $setter = 'set'. substr($getter, 3);

            if (method_exists($model, $setter)) {
                $model->$setter($object->$getter());
            }
        }

        return $model;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    public static function isEntity($object)
    {
        return (bool)strpos('Entity', get_class($object));
    }
}