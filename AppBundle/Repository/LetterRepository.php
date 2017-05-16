<?php
namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;

class LetterRepository extends EntityRepository
{
    public function findLettersByUser(ParamFetcher $paramFetcher, User $user)
    {
        $qb = $this->createQueryBuilder('l');

        $qb
            ->select('l')
            ->join('l.user', 'u')
            ->where('u.id = :userId')
            ->setParameters(['userId' => $user->getId()]);

        if (is_array($paramFetcher->get('status'))) {
            $qb->andWhere($qb->expr()->in('l.status', $paramFetcher->get('status')));
        }
        $qb->addOrderBy('l.'.$paramFetcher->get('sort'), $paramFetcher->get('direction'));
        return $qb;
    }
}