<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * 预订房间
     * @param int $userId 用户id
     * @param int $roomId 房间id
     * 
     * @return int 预订id
     */
    public function book($userId, $roomId): int
    {

    }

    /**
     * 取消预订
     * @param int $userId 用户id
     * @param int $roomId 房间id
     * 
     * @return int 预订id
     */
    public function unBook(): int
    {
        
    }

    /**
     * 预订统计
     * 
     * @return int 预订数量
     */
    public function countBook(): int
    {
        
    }

    /**
     * 预订信息详情
     * 
     * @return array 预订信息详情
     */
    public function listBook(): array
    {
        
    }

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
