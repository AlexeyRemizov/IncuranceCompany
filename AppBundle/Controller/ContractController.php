<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contract;
use AppBundle\Form\ContractType;
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
 * Class ContractController
 * @package AppBundle\Controller
 * @Route("api/contract")
 */
class ContractController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     section="Contract",
     *     resource=true,
     *     authentication=true,
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer {token}",
     *             "required"=true
     *         }
     *     },
     *     description="Retrieves the collection of Contract resources by current user.",
     *     statusCodes={
     *         200="Letter collection response"
     *     }
     * )
     * @Route("/list", name="list_contract")
     * @Rest\View(serializerGroups={"contract"})
     * @Method({"GET"})
     * @return View
     */
    public function listAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $contracts = $em->getRepository(Contract::class)->findContractsByUser($this->getUser());
        return View::create($contracts, Response::HTTP_OK);
    }


    /**
     * @ApiDoc(
     *     section="Contract",
     *     authentication=true,
     *     resource=true,
     *     description="Getting Contract resource",
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
     * @Route("/{id}", requirements={"id":"\d+"}, name="show_contract")
     * @Rest\View(serializerGroups={"contract"})
     * @Method({"GET"})
     * @return View
     * @throws \Exception
     */
    public function showAction ($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $contract = $em->getRepository(Contract::class)->findOneBy(['id' => $id]);
        if (!$contract instanceof Contract) {
            return View::create(['message' => 'Contract resource not found'], Response::HTTP_NOT_FOUND);
        }
        if ($contract->getUser() !== $this->getUser()) {
            return View::create(['message' => 'Access denied for this user'], Response::HTTP_FORBIDDEN);
        }
        return View::create($contract, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *     section="Contract",
     *     authentication=true,
     *     resource=true,
     *     description="Created Contract resource",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer {token}",
     *             "required"=true
     *         }
     *     },
     *     parameters={
     *         {"name"="dateIN", "dataType"="datetime", "required"=true, "description"="Date on of contract"},
     *         {"name"="dateOFF", "dataType"="datetime", "required"=true, "description"="Date off of contract"},
     *         {"name"="subject", "dataType"="string", "required"=true, "description"="Subject of insurance"},
     *         {"name"="vinID", "dataType"="integer", "required"=true, "description"="VIN code"},
     *         {"name"="registrationID", "dataType"="integer", "required"=true, "description"="Number of registration"},
     *         {"name"="datePay", "dataType"="datetime", "required"=true, "description"="Payment date"},
     *         {"name"="sumpay", "dataType"="integer", "required"=true, "description"="Amount of payment"},
     *         {"name"="type", "dataType"="string", "required"=true, "description"="Type of insurance"},
     *     },
     *     statusCodes={
     *         200="Created resource response",
     *         403="Access denied for this user",
     *         404="Resource not found"
     *     }
     * )
     * @Route("/new", name="new_contract")
     * @Rest\View(serializerGroups={"contract"})
     * @Method({"POST"})
     * @return View
     * @throws \Exception
     */
    public function createAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        /** @var Contract $contract */
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $data = array_merge($request->request->all(), $request->files->all());
        $form->submit($data);

        if ($form->isValid()) {
            $contract->setUser($this->getUser());
            $em->persist($contract);
            $em->flush();
            return View::create($contract, Response::HTTP_CREATED);
        }
        return View::create($form, Response::HTTP_NOT_FOUND);
    }
}