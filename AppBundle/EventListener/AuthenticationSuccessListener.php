<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Accounter;
use AppBundle\Entity\Brand;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Serializer;

class AuthenticationSuccessListener
{
    /** @var Serializer  */
    protected $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();
        $user->setToken($data['token']);
        $response =  $this->serializer->normalize($event->getUser(), 'json', ['groups' => ['user']]);
        $event->setData($response);
    }
}
