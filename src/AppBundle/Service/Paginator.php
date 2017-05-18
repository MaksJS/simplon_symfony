<?php

namespace AppBundle\Service;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\ORM\EntityManager;

class Paginator {

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function paginate($entityClass, $page) {
        $limit = $entityClass::PER_PAGE;
        if (!$limit) {
            throw "Missing PER_PAGE constant in class ".$entityClass;
        }
        $repository = $this->em->getRepository($entityClass);
        $query = $repository
            ->createQueryBuilder('a')
            ->getQuery()
            ->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit);
        $paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
        $nbPages = ceil(count($paginator) / $limit);
        $nbPages = ($nbPages === 0) ? 1 : $nbPages;
        return compact('paginator', 'nbPages');
    }
}