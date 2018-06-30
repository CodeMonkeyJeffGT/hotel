<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * 房间列表
     * @return array 房间列表
     */
    public function list($type, $userId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        switch ($type) {
            case 'all':
                //no break;
            default:
                return $this->findAll();
            case 'booking': 
                $sql = 'SELECT `r`.`num`, `r`.`people`, `r`.`id`
                    FROM `room` `r`
                    WHERE `r`.`id` NOT IN (
                        SELECT `rt`.`id` `id`
                        FROM `room` `rt`
                        LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `rt`.`id`
                        LEFT JOIN `booking` `b` ON `b`.`r_id` = `rt`.`id`
                        WHERE (`o`.`status` IS NOT NULL AND `o`.`status` = 1)
                        OR (`b`.`status` IS NOT NULL AND `b`.`status` = 1)
                    )
                ';
                $params = array();
                break;
            case 'order':
                $sql = 'SELECT `r1`.`num`, `r1`.`people`, `r1`.`id`, `u`.`nickname`, `b`.`book_date`, `b`.`days`
                    FROM `room` `r1`
                    LEFT JOIN `booking` `b` ON `b`.`r_id` = `r1`.`id`
                    LEFT JOIN `user` `u` ON `b`.`u_id` = `u`.`id`
                    WHERE `r1`.`id` NOT IN (
                        SELECT `rt1`.`id` `id`
                        FROM `room` `rt1`
                        LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `rt1`.`id`
                        WHERE (`o`.`status` IS NOT NULL AND `o`.`status` = 1)
                    )
                    AND `b`.`status` = 1
                    OR `b`.`status` IS NULL
                    UNION
                    SELECT `r2`.`num`, `r2`.`people`, `r2`.`id`, null, null, null
                    FROM `room` `r2`
                    LEFT JOIN `booking` `b` ON `b`.`r_id` = `r2`.`id`
                    LEFT JOIN `user` `u` ON `b`.`u_id` = `u`.`id`
                    WHERE `r2`.`id` NOT IN (
                        SELECT `rt2`.`id` `id`
                        FROM `room` `rt2`
                        LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `rt2`.`id`
                        WHERE (`o`.`status` IS NOT NULL AND `o`.`status` = 1)
                    )
                    AND `b`.`status` <> 1
                    AND `r2`.`id` NOT IN (
                        SELECT `rt3`.`id`
                        FROM `room` `rt3`
                        LEFT JOIN `booking` `b` ON `b`.`r_id` = `rt3`.`id`
                        LEFT JOIN `user` `u` ON `b`.`u_id` = `u`.`id`
                        WHERE `rt3`.`id` NOT IN (
                            SELECT `rtt`.`id` `id`
                            FROM `room` `rtt`
                            LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `rtt`.`id`
                            WHERE (`o`.`status` IS NOT NULL AND `o`.`status` = 1)
                        )
                        AND `b`.`status` = 1
                        OR `b`.`status` IS NULL
                    )
                    ORDER BY `num`
                ';
                $params = array();
                break;
            case 'out':
                $sql = 'SELECT `r`.`num`, `r`.`people`, `o`.`id`, `o`.`guest`, `o`.`in_date` `inDate`, `o`.`days`
                    FROM `room` `r`
                    LEFT JOIN `occupancy` `o` ON `o`.`r_id` = `r`.`id`
                    WHERE (`o`.`status` IS NOT NULL AND `o`.`status` = 1)
                ';
                $params = array();
                break;
            case 'mine':
                $sql = 'SELECT `b`.`id`, `r`.`num`, `r`.`people`, `b`.`status`, `b`.`book_date`, `b`.`days`
                    FROM `room` `r`
                    LEFT JOIN `booking` `b` ON `r`.`id` = `b`.`r_id`
                    WHERE `u_id` = :id
                    ORDER BY `b`.`created` DESC
                ';
                $params = array(
                    'id' => $userId,
                );
                break;
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

//    /**
//     * @return Room[] Returns an array of Room objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
