<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexController extends Controller
{
    public function admin(): Response
    {
        $session = new Session();
        if ($session->has('hotelUser')) {
            $filename = 'admin.html';
            return $this->render($filename);
        } else {
            return $this->redirect('signin');
        }
    }

    public function front(): Response
    {
        $session = new Session();
        if ($session->has('hotelUser')) {
            $filename = 'front.html';
            return $this->render($filename);
        } else {
            return $this->redirect('signin');
        }
    }

    public function frontOut(): Response
    {
        $session = new Session();
        if ($session->has('hotelUser')) {
            $filename = 'front-out.html';
            return $this->render($filename);
        } else {
            return $this->redirect('signin');
        }
    }

    public function user(): Response
    {
        $session = new Session();
        if ($session->has('hotelUser')) {
            $filename = 'user.html';
            return $this->render($filename);
        } else {
            return $this->redirect('signin');
        }
    }

    public function userMine(): Response
    {
        $session = new Session();
        if ($session->has('hotelUser')) {
            $filename = 'user-mine.html';
            return $this->render($filename);
        } else {
            return $this->redirect('signin');
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
}
