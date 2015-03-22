<?php

namespace AppBundle\Controller;

use AppBundle\Util\ClassUtils;
use Gogocar\Dto\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FE;
use AppBundle\Manager\AbstractManager;

/**
 * @FE\Route("/users")
 */
class UserController extends BaseController implements ClassResourceInterface
{

    /**
     * @ApiDoc(
     *  section="User",
     *  output="Gogocar\Dto\Model\User"
     * )
     *
     * @param $id
     */
    public function getAction($id)
    {
        $user = parent::getAction($id);

        $userModel = ClassUtils::buildObjectFromAnother($user, '\\Gogocar\\Dto\\Model\\User');

        return $userModel;
    }

    /**
     * @ApiDoc(
     *  section="User",
     *  output=""
     * )
     *
     * @param $slug
     */
    public function postAction()
    {

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

    } // "get_user"      [GET] /users/{slug}

    /**
     *
     * @ApiDoc()
     *
     * @param $slug
     */
    public function editUserAction($slug)
    {} // "edit_user"     [GET] /users/{slug}/edit

    /**
     * @return \AppBundle\Manager\UserManager
     */
    protected function getManager()
    {
        return $this->get('app.user_manager');
    }
}