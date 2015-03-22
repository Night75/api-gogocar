<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Quote;
use Doctrine\DBAL\Schema\View;
use FOS\RestBundle\Request\ParamFetcher;
use Gogocar\Dto\Form\QuoteType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FE;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Util\Codes;

/**
 * @FE\Route("/quotes")
 */
class QuoteController extends BaseController implements ClassResourceInterface
{
    /**
     *
     * @QueryParam(name="id", requirements="\d+(,\d+)*", array=true, description="Id filters")
     *
     * @ApiDoc(
     *  section="Quote",
     *  output=""
     * )
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $dynamicQueryParam = new QueryParam();
        $dynamicQueryParam->name = "dynamic_query";
        $dynamicQueryParam->requirements="[a-z]+";
        $paramFetcher->addParam($dynamicQueryParam);

        $d = $paramFetcher->get('id');

        $a = 2;
    }

    /**
     * @ApiDoc(
     *  section="Quote",
     *  output="Gogocar\Dto\Model\Quote"
     * )
     *
     * @param $id
     */
    public function getAction($id)
    {
        return parent::getAction($id);
    }

    /**
     * @ApiDoc(
     *  section="Quote",
     *  input="Gogocar\Dto\Form\QuoteType",
     *  output="Gogocar\Dto\Model\Quote"
     * )
     *
     * @param $slug
     */
    public function postAction(Request $request)
    {
        $quote = $this->getFormData($request, new QuoteType());
        $quote = $this->getManager()->save($quote);

        return $this->response($quote, Codes::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  section="Quote",
     *  output=""
     * )
     *
     * @param
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    } // "get_user"      [GET] /users/{slug}



    /**
     * @ApiDoc()
     *
     * @param $slug
     */
    public function putAction()
    {

    } // "get_user"      [GET] /users/{slug}


    /**
     * @ApiDoc()
     *
     * @param $slug
     */
    public function newAction()
    {

    }

    protected function getManager()
    {
        return $this->get('app.quote_manager');
    }
}