<?php

namespace App\Repository\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 *
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function betById(int $id): ?Module
    {
        return $this->createQueryBuilder('m')
                    ->addSelect('p', 'sm', 'sh')
                    ->leftJoin('m.plannings', 'p')
                    ->leftJoin('m.subModules', 'sm')
                    ->leftJoin('p.schedule', 'sh')
                    ->where('m.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    /**
     * @param Schedule $schedule
     *
     * @return Module[]
     */
    public function getModulesBySchedule(Schedule $schedule): array
    {
        return $this->createQueryBuilder('m')
                    ->addSelect('p')
                    ->leftJoin('m.plannings', 'p')
                    ->where('p.schedule = :schedule')
                    ->setParameter('schedule', $schedule)
                    ->getQuery()
                    ->getResult()
        ;
    }

//    /**
//     * @return Module[] Returns an array of Module objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Module
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
