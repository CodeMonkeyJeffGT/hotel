<?php

namespace App\Repository;

use App\Entity\Occupancy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Occupancy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Occupancy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Occupancy[]    findAll()
 * @method Occupancy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupancyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Occupancy::class);
    }

    /**
     * 开房登记
     * @param int $userId 用户id
     * @param int $roomId 房间id
     * 
     * @return int 登记id
     */
    public function checkIn($userId, $roomId): int
    {

    }

    /**
     * 退房结账
     * @param int $userId 用户id
     * @param int $roomId 房间id
     * 
     * @return int 登记id
     */
    public function checkOut($userId, $roomId): int
    {

    }

    /**
     * 开房统计
     * 
     * @return int 预订数量
     */
    public function countIn(): int
    {
        
    }

    /**
     * 退房统计
     * 
     * @return int 预订数量
     */
    public function countOut(): int
    {
        
    }

    /**
     * 开房信息详情
     * 
     * @return array 预订信息详情
     */
    public function listIn(): array
    {
        
    }

    /**
     * 退房信息详情
     * 
     * @return array 预订信息详情
     */
    public function listOut(): array
    {
        
    }

//    /**
//     * @return Occupancy[] Returns an array of Occupancy objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Occupancy
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
