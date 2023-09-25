<?php

namespace App\Repository\Module;

use App\Entity\Module\SubModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubModule>
 *
 * @method SubModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubModule[]    findAll()
 * @method SubModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubModule::class);
    }

//    /**
//     * @return SubModule[] Returns an array of SubModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SubModule
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
