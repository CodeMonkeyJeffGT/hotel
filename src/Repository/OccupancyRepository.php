<?php

namespace App\Repository;

use App\Entity\Occupancy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Controller\OccupancyController;

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

    public function showIn()
    {
        
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
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `r`.`id` `id`,
            `o`.`status` `os`, `o`.`bookDate` `bookDate`, `o`.`days` `bookDays`,
            FROM `room` `r`
            LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `r`.`id`
            WHERE `r`.`id` = :id
            AND `o`.`status` = :os';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $roomId,
            'os' => OccupancyController::STATUS_IN,
        ));
        $rst = $stmt->fetchAll();
        if (count($rst) === 0) {
            throw new \Exception('', CooupancyController::ROOM_NOT_EXISTS);
        }
        foreach ($rst as $line) {
            if ($line['os'] === 1) {
                throw new \Exception('', CooupancyController::CHECKED);
            }
        }
        $occupancy = new Occupancy();
        $occupancy->setUId($userId);
        $occupancy->setRId($roomId);
        $occupancy->setStatus(CooupancyController::STATUS_IN);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($occupancy);
        $entityManager->flush();
        return $occupancy->getId();
    }

    /**
     * 退房结账
     * @param int $userId 用户id
     * @param int $roomId 房间id
     * 
     * @return int 登记id
     */
    public function checkOut($userId, $occupancyId): int
    {
        $occupancy = $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $occupancyId)
            ->getQuery()
            ->getOneOrNullResult();
        if (is_null($occupancy)) {
            throw new \Exception('', BookingController::ORDER_NOT_EXISTS);
        }
        if ($occupancy->getUId() !== (int)$userId) {
            throw new \Exception('', BookingController::ROOM_NOT_YOURS);
        }
        switch ($bookint->getStatus()) {
            case BookingController::STATUS_OUT:
                throw new \Exception('', BookingController::ALREADY_OUT);
        }
        $occupancy->setStatus(BookingController::STATUS_CANCELED);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($occupancy);
        $entityManager->flush();
        return $occupancyId;
    }

    /**
     * 开房统计
     * 
     * @return int 预订数量
     */
    public function countIn(): int
    {
        return count($this->findAll());
    }

    /**
     * 退房统计
     * 
     * @return int 预订数量
     */
    public function countOut(): int
    {
        return count($this->findBy(array(
            'status' => '2',
        )));
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
