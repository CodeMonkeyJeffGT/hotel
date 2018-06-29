<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Controller\BookingController;
use App\Controller\OccupancyController;

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
    public function book($userId, $roomId, $bookDate, $days): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `r`.`id` `id`,
            `o`.`status` `os`, `o`.`bookDate` `bookDate`, `o`.`days` `bookDays`,
            `b`.`status` `bs`, `b`.`in_date` `checkDate`, `b`.`days` `checkDays`
            FROM `room` `r`
            LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `r`.`id`
            LEFT JOIN `booking` `b` ON `b`.`r_id` = `r`.`id`
            WHERE `r`.`id` = :id
            AND `o`.`status` = :os
            AND `b`.`status` = :bs';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $roomId,
            'os' => OccupancyController::STATUS_IN,
            'bs' => BookingController::STATUS_BOOKED,
        ));
        $rst = $stmt->fetchAll();
        if (count($rst) === 0) {
            throw new \Exception('', BookingController::ROOM_NOT_EXISTS);
        }
        foreach ($rst as $line) {
            if ($line['os'] === 1) {
                // if (strtotime($line['checkDate']) + $line['checkDays'] * 86400 <  )
                throw new \Exception('', BookingController::CHECKED);
            }
            if ($line['bs'] === 1) {
                throw new \Exception('', BookingController::BOOKED);
            }
        }
        $booking = new Booking();
        $booking->setUId($userId);
        $booking->setRId($roomId);
        $booking->setStatus(BookingController::STATUS_BOOKED);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($booking);
        $entityManager->flush();
        return $booking->getId();
    }

    /**
     * 取消预订
     * @param int $userId 用户id
     * @param int $roomId 房间id
     * 
     * @return int 预订id
     */
    public function unBook($userId, $bookingId): int
    {
        $booking = $this->createQueryBuilder('b')
            ->andWhere('b.id = :id')
            ->setParameter('id', $bookingId)
            ->getQuery()
            ->getOneOrNullResult();
        if (is_null($booking)) {
            throw new \Exception('', BookingController::BOOKING_NOT_EXISTS);
        }
        if ($booking->getUId() !== (int)$userId) {
            throw new \Exception('', BookingController::ROOM_NOT_YOURS);
        }
        switch ($bookint->getStatus()) {
            case BookingController::STATUS_CANCELED:
                throw new \Exception('', BookingController::ALREADY_CANCELED);
            case BookingController::STATUS_DONE:
                throw new \Exception('', BookingController::ALREADY_DONE);
        }
        $booking->setStatus(BookingController::STATUS_CANCELED);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($booking);
        $entityManager->flush();
        return $bookingId;
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
