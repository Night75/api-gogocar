<?php

namespace Gomycar\ApiBundle\Request;

use FOS\RestBundle\Request\ParamReaderInterface;
use Gomycar\ApiBundle\Controller\Annotations\InheritParam;
use Doctrine\Common\Annotations\Reader;
use FOS\RestBundle\Controller\Annotations\Param;
use FOS\RestBundle\Request\ParamReader as BaseReader;

class ParamReader extends BaseReader implements ParamReaderInterface
{
    protected $annotationReader;

    /**
     * Fetches parameters from provided annotation array (fetched from annotationReader)
     *
     * @param array $annotations
     *
     * @return Param[]
     */
    protected function getParamsFromAnnotationArray(array $annotations)
    {
        $params = [];
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Param) {
                $params[$annotation->name] = $annotation;
            }
        }

        return $params;
    }

    /**
     * {@inheritDoc}
     */
    public function getParamsFromMethod(\ReflectionMethod $method)
    {
        $annotations = $this->annotationReader->getMethodAnnotations($method);
        $annotations = array_merge($annotations, $this->getInheritedMethodAnnotations($annotations));

        return $this->getParamsFromAnnotationArray($annotations);
    }

    /**
     * Get the inherited method annotations
     *
     * @param array $annotations
     *
     * @return Param[] Param annotation objects of the method. Indexed by parameter name.
     */
    public function getInheritedMethodAnnotations($annotations)
    {
        $parentAnnotations = [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof InheritParam) {
                $rMethod = new \ReflectionMethod($annotation->class, $annotation->method);
                $parentAnnotations = array_merge(
                    $parentAnnotations,
                    $this->annotationReader->getMethodAnnotations($rMethod)
                );
            }
        }

        return $parentAnnotations;
    }
}
