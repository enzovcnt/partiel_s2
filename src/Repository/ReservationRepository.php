<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findReservedSeatsByScreening(int $screeningId): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.seatChoice')
            ->where('r.screening = :screeningId')
            ->setParameter('screeningId', $screeningId);

        $results = $qb->getQuery()->getArrayResult();

        $reservedSeats = [];
        foreach ($results as $row) {
            if (!empty($row['seatChoice'])) {
                $reservedSeats = array_merge($reservedSeats, $row['seatChoice']);
            }
        }
        return array_unique($reservedSeats);
    }
}
