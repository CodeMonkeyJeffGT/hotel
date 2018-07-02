<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Controller\UserController;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * 登录
     * @param string $account 账号
     * @param string $password 密码
     * 
     * @return array 用户id
     */
    public function sign($account, $password): array
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.account = :account')
            ->setParameter('account', $account)
            ->getQuery()
            ->getOneOrNullResult();
        if (is_null($user)) {
            throw new \Exception('账号不存在', UserController::USER_NOT_EXISTS);
        }
        if ($user->getPassword() !== $password) {
            throw new \Exception('密码错误', UserController::PASSWORD_WRONG);
        }
        return array(
            'id' => $user->getId(),
            's_id' => $user->getSId(),
        );
    }

    /**
     * 注册
     * @param string $account 账号
     * @param string $password 密码
     * @param string $nickname 昵称
     * 
     * @return array 用户id
     */
    public function register($account, $password, $nickname): array
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.account = :account')
            ->setParameter('account', $account)
            ->getQuery()
            ->getOneOrNullResult();
        if ( ! is_null($user)) {
            throw new \Exception('', UserController::USER_EXISTS);
        }

        $soap = new \SoapClient('http://47.93.39.7:8080/SOA/webservice/WebserviceTest?wsdl');
        $account = $account . mt_rand(0, 10000);
        $password = $password;
        $param = array(
            'username' => $account,
            'password' => $password,
        );
        $rst = $soap->register($param);
        $rst = json_decode($rst->return, true);

        $user = new User();
        $user->setAccount($account);
        $user->setPassword($password);
        $user->setNickname($nickname);
        $user->setSId($rst['id']);
        $user->setRole(UserController::PER_USER);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return array(
            'id' => $user->getId(),
            's_id' => $user->getSId(),
        );
    }

    /**
     * 检查权限
     * @param int $id       用户id
     * @param int $permit   权限等级
     * 
     * @return boolean
     */
    public function checkPermit($id, $permit): bool
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->andWhere('u.role >= :permit')
            ->setParameter('permit', $permit)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        return ( ! is_null($user));
    }

    /**
     * 获取用户列表
     */
    public function list()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `u`.`id`, `u`.`nickname`, `u`.`account`, `u`.`role`
            FROM `user` `u`';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * 修改用户角色
     * @param int $userId 用户id
     * @param int $permit 权限
     */
    public function changePermit($userId, $permit): void
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getOneOrNullResult();
        if (is_null($user)) {
            throw new \Exception('', UserController::USER_NOT_FOUND);
        }
        $user->setRole($permit);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
