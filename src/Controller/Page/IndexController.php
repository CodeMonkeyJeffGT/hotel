<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\User;

class IndexController extends Controller
{
    public const PER_USER = 0;
    public const PER_RECEPTIONIST = 1;
    public const PER_ADMIN = 2;

    public function center(): Response
    {
        return $this->render('center.html');
    }

    public function scenic(): Response
    {
        return $this->render('scenic.html');
    }

    public function scenicMine(): Response
    {
        $session = new Session();
        if (($permitRst = $this->checkPermit()) === true) {
            $filename = 'scenic-mine.html';
            return $this->render($filename);
        } else {
            return $this->signin();
        }
    }

    public function admin(): Response
    {
        $session = new Session();
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) === true) {
            $filename = 'admin.html';
            return $this->render($filename);
        } else {
            return $this->front();
        }
    }

    public function front(): Response
    {
        $session = new Session();
        if (($permitRst = $this->checkPermit(static::PER_RECEPTIONIST)) === true) {
            $filename = 'front.html';
            return $this->render($filename);
        } else {
            return $this->user();
        }
    }

    public function frontOut(): Response
    {
        $session = new Session();
        if (($permitRst = $this->checkPermit(static::PER_RECEPTIONIST)) === true) {
            $filename = 'front-out.html';
            return $this->render($filename);
        } else {
            return $this->user();
        }
    }

    public function user(): Response
    {
        $session = new Session();
        if (($permitRst = $this->checkPermit()) === true) {
            $filename = 'user.html';
            return $this->render($filename);
        } else {
            return $this->signin();
        }
    }

    public function userMine(): Response
    {
        $session = new Session();
        if (($permitRst = $this->checkPermit()) === true) {
            $filename = 'user-mine.html';
            return $this->render($filename);
        } else {
            return $this->signin();
        }
    }

    public function signin(): Response
    {
        return $this->render('login.html');
    }

    public function register(): Response
    {
        return $this->render('register.html');
    }

    /**
     * 检查是否登录及是否有权限
     */
    private function checkPermit($permit = null)
    {
        $session = new Session();
        $permit = $permit ?? self::PER_USER;
        if ( ! $session->has('hotelUser')) {
            return false;
        }
        if ($permit === self::PER_USER) {
            return true;
        }
        $id = $session->get('hotelUser');
        $userDb = $this->getDoctrine()->getRepository(User::class);
        if ($userDb->checkPermit($id, $permit)) {
            return true;
        } else {
            return false;
        }
    }
}
