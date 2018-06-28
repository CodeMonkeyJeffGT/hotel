<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;

class UserController extends Controller
{
    /**
     * 登录
     * 
     * @Route("/sign", name="sign")
     */
    public function sign(): JsonResponse
    {
        $account = $this->request->request->get('account');
        $password = $this->request->request->get('password');
        $userDb = $this->getDoctrine()->getRepository(User::class);
        try {
            $userRst = $userDb->sign($account, $password);
            $this->session->set('hotelUser', $userRst);
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
     * 注册
     * 
     * @Route("/register", name="register")
     */
    public function register(): JsonResponse
    {
        $account = $this->request->request->get('account');
        $password = $this->request->request->get('password');
        $nickname = $this->request->request->get('nickname');
        $userDb = $this->getDoctrine()->getRepository(User::class);
        try {
            $userRst = $userDb->register($account, $password, $nickname);
            $this->session->set('hotelUser', $userRst);
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

    // /**
    //  * 用户列表
    //  */
    // public function list(): JsonResponse
    // {
    //     $this->checkAdmin();
    // }

    // /**
    //  * 修改用户角色
    //  */
    // public function changeRole(): JsonResponse
    // {
    //     $this->checkAdmin();
    // }
}