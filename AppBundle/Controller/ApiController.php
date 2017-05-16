<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Letter;
use AppBundle\Entity\RequestLetters;
use AppBundle\Form\LettersRequestType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class ApiController extends FOSRestController
{

    /**
     * @ApiDoc(
     *     section="Api",
     *     description="Create Letter resource",
     *     authentication=true,
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer {token}",
     *             "required"=true
     *         }
     *     },
     *     parameters={
     *         {"name"="letters", "dataType"="array", "required"=true, "description"="Collection Letter. Max 5 letter for each request"},
     *         {"name"="letters[]username", "dataType"="string", "required"=true, "description"="Github username"},
     *         {"name"="letters[]message", "dataType"="string", "required"=true, "description"="Message for sending"}
     *     },
     *     statusCodes={
     *         201="Letter successfully created - letters collection",
     *         400="Bad request (validation errors)",
     *         401="Invalid access token"
     *     }
     * )
     * @Route("/letters", name="index")
     * @Rest\View(serializerGroups={"letter"})
     * @Method({"POST"})
     * @return View
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $requestLetters = new RequestLetters();
        $form = $this->createForm(LettersRequestType::class, $requestLetters, []);
        $data = array_merge($request->request->all());
        $form->submit($data);
        if ($form->isValid()) {
            $response = $this->get('app.letter_manager')->handleLetters($requestLetters, $this->getUser());
            return View::create($response, Response::HTTP_CREATED);
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
    /**
     * @ApiDoc(
     *     section="Api",
     *     authentication=true,
     *     resource=true,
     *     description="Getting Letter resource",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer {token}",
     *             "required"=true
     *         }
     *     },
     *     statusCodes={
     *         200="Letter resource response",
     *         403="Access denied for this user",
     *         404="Resource not found"
     *     }
     * )
     * @Route("/letter/{id}", requirements={"id":"\d+"}, name="show_letter")
     * @Rest\View(serializerGroups={"letter"})
     * @Method({"GET"})
     * @return View
     * @throws \Exception
     */
    public function showAction ($id)
    {
            $em = $this->get('doctrine.orm.entity_manager');
        $letter = $em->getRepository(Letter::class)->findOneBy(['id' => $id]);
            if (!$letter instanceof Letter) {
            return View::create(['message' => 'Letter resource not found'], Response::HTTP_NOT_FOUND);
        }
        if ($letter->getUser() !== $this->getUser()) {
            return View::create(['message' => 'Access denied for this user'], Response::HTTP_FORBIDDEN);
        }
        return View::create($letter, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *     section="Api",
     *     resource=true,
     *     authentication=true,
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer {token}",
     *             "required"=true
     *         }
     *     },
     *     description="Retrieves the collection of Letter resources by current user.",
     *     statusCodes={
     *         200="Letter collection response"
     *     }
     * )
     * @Rest\QueryParam(name="page",  requirements="\d+", default="1", description="Page to show")
     * @Rest\QueryParam(name="limit", default="10", requirements="\d+", description="Limit")
     * @Rest\QueryParam(name="sort", requirements="(id|status|username)", default="id", description="Sort field.", nullable=true)
     * @Rest\QueryParam(name="direction", requirements="(asc|desc)", default="asc" ,description="Sort direction", nullable=true)
     * @Rest\QueryParam(name="status",  requirements="\d+", map=true,  description="Get letters by status", nullable=true)
     * @Route("/letters", name="list_letter")
     * @Rest\View(serializerGroups={"letter"})
     * @Method({"GET"})
     * @param ParamFetcher $paramFetcher
     * @return View
     */
    public function listAction (ParamFetcher $paramFetcher)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $letters = $em->getRepository(Letter::class)->findLettersByUser($paramFetcher, $this->getUser());
        /** @var SlidingPagination $paginatedCollection */
        $paginatedCollection  = $this->get('knp_paginator')->paginate(
            $letters->getQuery(),
            $paramFetcher->get('page', true),
            $paramFetcher->get('limit', true)
        );

        return View::create(
            [
                'letters'           => $paginatedCollection->getItems(),
                'paginationData'    => $paginatedCollection->getPaginationData()
            ],
            Response::HTTP_OK
        );
    }

}