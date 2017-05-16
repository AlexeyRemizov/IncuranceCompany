<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;
use AppBundle\Services\ImageResizer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 */
class SecurityController extends FOSRestController
{
    /**
     * @Route("/registration", name="api_registration")
     * @Method({"POST"})
     * @Rest\View(serializerGroups={"user"})
     * @ApiDoc(
     *     section="Security",
     *     resource=true,
     *     description="Create User resource",
     *     parameters={
     *         {"name"="email", "dataType"="email", "required"=true, "description"="User email"},
     *         {"name"="password", "dataType"="string", "required"=true, "description"="User password"},
     *         {"name"="file", "dataType"="file", "required"=true, "description"="User avatar"},
     *     },
     *    statusCodes={
     *         201="Successful created User resource",
     *         400="Bad request(validation errors)"
     *     }
     * )
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        /** @var User $user */
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $data = array_merge($request->request->all(), $request->files->all());
        $form->submit($data);

        if ($form->isValid()) {
            $imageResizer = new ImageResizer();
            $result = $imageResizer->makeThumbnail($user->getFile(), 'uploads/', 100);
            $user->setAvatar($result[0]);
            $user->setAvatarThumbnail($result[1]);
            // Save encoded password
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush();
            $token = new JWTUserToken($user->getRoles(), $user, null, 'app.user_provider');

            /** @var \Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse $response */
            $response = $this
                ->get('lexik_jwt_authentication.handler.authentication_success')
                ->onAuthenticationSuccess($request, $token);

            return View::create(json_decode($response->getContent(), true), Response::HTTP_CREATED);
        }
        return View::create($form, Response::HTTP_NOT_FOUND);
    }


    /**
     * @Route("api/login", name="api_login")
     * @Method({"POST"})
     * @Rest\View(serializerEnableMaxDepthChecks=false, serializerGroups={"user"})
     * @ApiDoc(
     *     section="Security",
     *     resource=false,
     *     description="Authorize User and issuance token",
     *     parameters={
     *         {"name"="email", "dataType"="email", "required"=true, "description"="User email"},
     *         {"name"="password", "dataType"="string", "required"=true, "description"="User password"},
     *     },
     *    statusCodes={
     *         200="Successful authorize User",
     *         401="Bad credentials",
     *     }
     *
     * )
     * @return boolean
     * @throws \Exception
     */
    public function indexAction()
    {
        /** @see AuthenticationSuccessListener */
        return true;
    }


}