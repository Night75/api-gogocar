<?php

namespace AppBundle\Listener;

use AppBundle\Util\ClassUtils;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use FOS\RestBundle\Request\ParamReader;

class QueryParamListener
{
    protected $paramReader;
    protected $arrayDelimiter;

    /**
     * @param ParamReader $paramReader
     * @param string $arrayDelimiter
     */
    public function __construct(ParamReader $paramReader, $arrayDelimiter = ',')
    {
        $this->paramReader = $paramReader;
        $this->arrayDelimiter = $arrayDelimiter;
    }

    /**
     * Core controller handler.
     *
     * @param FilterControllerEvent $event
     *
     * @throws \InvalidArgumentException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $controller = $event->getController();

        $params = $this->paramReader->read(
            new \ReflectionClass(ClassUtils::getClass($controller[0])),
            $controller[1]
        );

        foreach ($params as $key => $param) {
            if ($param instanceof QueryParam && $param->array) {
                $this->explodeQuery($key, $request, $this->arrayDelimiter);
            }
        }
    }

    /**
     * Explode a query string by a delimiter.
     *
     * Example: /some-url?id=2,45,6
     * will be parsed as the array [2, 45, 6].
     * This value is also set in the Request
     *
     * @param $key
     * @param Request $request
     * @param string $arrayDelimiter
     */
    protected function explodeQuery($key, Request $request, $arrayDelimiter = ',')
    {
        $query = $request->query;

        if (!$query->has($key)) {
            return;
        }

        $val = $query->get($key);
        if (is_scalar($val)) {
            $val = explode($arrayDelimiter, $val);
            $query->set($key, $val);
        }
    }
}
