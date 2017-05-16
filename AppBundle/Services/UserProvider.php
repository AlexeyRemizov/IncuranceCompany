<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class UserProvider
 * @package AppBundle\Security\Security
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    public $em;

    /**
     * UserProvider constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $username
     * @return User|null
     * @throws NotFoundHttpException
     */
    public function loadUserByUsername($email)
    {
        $user =  $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found');
        }
        return $user;
    }

    /**
     * @param UserInterface $user
     * @return User|null
     * @throws \Exception
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}