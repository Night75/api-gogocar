<?php

namespace AppBundle\Listener;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Gogocar\Dto\Exception\ApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    protected $viewHandler;
    protected $logger;

    public function __construct(
        ViewHandler $viewHandler,
        LoggerInterface $logger
    ) {
        $this->viewHandler = $viewHandler;
        // TODO: Use the logger?
        $this->logger      = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var ApiException $exception */
        $exception = $event->getException();
        $request   = $event->getRequest();

        if (!$exception instanceof ApiException) {
            return;
        }

        $content = [
            'code'           => $exception->getCode(),
            'message'        => $exception->getMessage(),
            'extra'          => $exception->getExtra(),
            'exceptionClass' => get_class($exception),
        ];

        $view     = new View($content, $exception->getCode());
        $response = $this->viewHandler->handle($view);

        $event->setResponse($response);
    }

    protected function resolveFormat(Request $request)
    {
        return $request->attributes->get('_format', $this->defaultFormat);
    }
}
