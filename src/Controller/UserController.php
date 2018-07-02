<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;

class UserController extends Controller
{
    public const USER_EXISTS = 1001;
    public const USER_NOT_EXISTS = 1002;
    public const PASSWORD_WRONG = 1003;
    public const USER_NOT_FOUND = 1004;

    public function __construct()
    {
        parent::__construct();
        $this->setErrMsg(array(
            static::USER_EXISTS => '账号已存在',
            static::USER_NOT_EXISTS => '账号不存在',
            static::PASSWORD_WRONG => '密码错误',
            static::USER_NOT_FOUND => '用户不存在',
        ));
    }

    /**
     * 登录
     * 
     * @Route("/sign", name="sign")
     */
    public function sign(): JsonResponse
    {
        $account = $this->request->request->get('account', null);
        $password = $this->request->request->get('password', null);
        if (in_array(null, array($account, $password))) {
            return $this->error(static::PARAM_MISS);
        }

        $password = md5($password);

        $userDb = $this->getDoctrine()->getRepository(User::class);
        try {
            $userRst = $userDb->sign($account, $password);
            $this->session->set('hotelUser', $userRst['id']);
            $this->session->set('scenicUser', $userRst['s_id']);
            return $this->success(array(
                'id' => $userRst['id'],
            ));
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }

    /**
     * 注册
     * 
     * @Route("/register", name="register")
     */
    public function register(): JsonResponse
    {
        $account = $this->request->request->get('account', null);
        $password = $this->request->request->get('password', null);
        $nickname = $this->request->request->get('nickname', null);
        if (in_array(null, array($account, $password, $nickname))) {
            return $this->error(static::PARAM_MISS);
        }

        $password = md5($password);
        
        $userDb = $this->getDoctrine()->getRepository(User::class);
        try {
            $userRst = $userDb->register($account, $password, $nickname);
            $this->session->set('hotelUser', $userRst['id']);
            $this->session->set('scenicUser', $userRst['s_id']);
            return $this->success(array(
                'id' => $userRst,
            ));
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }


    /**
     * 安全退出
     * 
     * @Route("/signout", name="signout")
     */
    public function signOut()
    {
        session_unset();
        return $this->redirect('/');
    }

    /**
     * 用户列表
     * 
     * @Route("/user/list", name="user-list")
     */
    public function list(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $userDb = $this->getDoctrine()->getRepository(User::class);
        $userRst = $userDb->list();
        return $this->success($userRst);
    }

    /**
     * 修改用户角色
     * 
     * @Route("/user/changePermit", name="user-changePermit")
     */
    public function changeRole(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $userId = $this->request->request->get('userId', null);
        $permit = $this->request->request->get('permit', null);
        if (in_array(null, array($userId, $permit))) {
            return $this->error(static::PARAM_MISS);
        }
        try {
            $userDb = $this->getDoctrine()->getRepository(User::class);
            $userDb->changePermit($userId, $permit);
            return $this->success();
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }
}