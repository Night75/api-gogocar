<?php

namespace AppBundle\Controller;

use AppBundle\Manager\Exception\ResourceNotFoundException;
use Gogocar\Dto\Exception\ApiException;
use Gogocar\Dto\Exception\InvalidFormException;
use AppBundle\Manager\AbstractManager;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Util\Codes;

abstract class BaseController extends FOSRestController
{
    public function getAction($id)
    {
        try {
            $resource = $this->getManager()->get($id);
        } catch (ResourceNotFoundException $e) {
            throw new \Gogocar\Dto\Exception\ResourceNotFoundException();
        }

        return $this->response($resource, Codes::HTTP_OK);
    }

    public function deleteAction($id)
    {
        try {
            $this->getManager()->delete($id);
        } catch (ResourceNotFoundException $e) {
            throw new \Gogocar\Dto\Exception\ResourceNotFoundException();
        }

        return new Response('', Codes::HTTP_NO_CONTENT);
    }

    /**
     * @QueryParam(name="id", requirements="\d+(,\d+)*", array=true, description="Id of the resource")
     * @QueryParam(name="order", requirements="asc|desc", array=true, description="Sort order")
     * @QueryParam(name="offset", requirements="\d+", description="Offset")
     * @QueryParam(name="limit", requirements="\d+", description="Number of items")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     *
     * @throws \Gogocar\Dto\Exception\ResourceNotFoundException
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        return 2;
    }

    /**
     * Retrieve data from the request and validate through a form.
     *
     * @param FormTypeInterface|string $formType
     * @param mixed                    $defaultData
     * @param string                   $method
     *
     * @return mixed        depend on form type
     * @throws ApiException
     */
    protected function getFormData(Request $request, $formType, $defaultData = null, $method = 'POST')
    {
        $form = $this->createForm($formType, $defaultData, [
            'csrf_protection' => false,
            'method' => $method,
        ]);

        // _format is added by FOSRest
        $formRequest = clone $request;
        $formRequest->request->remove('_format');

        $form->handleRequest($formRequest);

        if (!$form->isValid()) {
            throw new InvalidFormException($this->buildFormErrors($form));
        }

        return $form->getData();
    }


    /**
     * @param FormInterface $form
     *
     * @return array
     */
    private function buildFormErrors(FormInterface $form)
    {
        $data = [];

        foreach ($form->getErrors() as $error) {
            $data['errors'][] = [
                'message' => $error->getMessageTemplate(),
                'parameters' => $error->getMessageParameters(),
                'pluralization' => $error->getMessagePluralization(),
            ];
        }

        foreach ($form as $child) {
            /** @var FormInterface $child */
            $childErrors = $this->buildFormErrors($child);
            if ($childErrors) {
                $data['children'][$child->getName()] = $childErrors;
            }
        }

        return $data;
    }

    /**
     * Creates a response from some mixed data.
     *
     * Convenience method to allow for a fluent interface.
     *
     * @param mixed|null    $data
     * @param integer|null  $statusCode
     * @param array         $headers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response($data = null, $statusCode = null, array $headers = [])
    {
        return $this->handleView($this->view($data, $statusCode, $headers));
    }

    /**
     * @return AbstractManager
     */
    abstract protected function getManager();
}
