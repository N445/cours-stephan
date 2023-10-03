<?php

namespace App\Repository\Cart;

use App\Entity\Cart\Cart;
use App\Entity\Module\Schedule;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findByAnonymousToken(string $anonymousToken): ?Cart
    {
        return $this->createQueryBuilder('c')
                    ->where('c.anonymousToken = :anonymousToken')
                    ->setParameter('anonymousToken', $anonymousToken)
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    public function findByUser(User $user): ?Cart
    {
        return $this->createQueryBuilder('c')
                    ->where('c.user = :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    /**
     * @param Schedule $schedule
     *
     * @return Cart[]
     */
    public function findBySchedule(Schedule $schedule, ?string $occurenceId): array
    {
        $qb = $this->createQueryBuilder('c')
                   ->addSelect('ci')
                   ->leftJoin('c.cartItems', 'ci')
                   ->where('ci.scheduleId = :scheduleId')
                   ->setParameter('scheduleId', $schedule->getId())
        ;

        if ($occurenceId) {
            $qb->andWhere('ci.occurenceId = :occurenceId')
               ->setParameter('occurenceId', $occurenceId)
            ;
        }
//                    ->andWhere('c.place = :place')
//                    ->setParameter('place', Cart::PLACE_COMPLETE)
//        ;

        return $qb->getQuery()
                  ->getResult()
        ;
    }

//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
