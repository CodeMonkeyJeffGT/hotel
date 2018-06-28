<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * 基础类，提供基本方法
 * @method Response success($data = null, string $message = null, $code = null)
 * @method Response error($code = null, string $message = null, $data = null)
 * @method Response toSign(string $message = null, $data = null, $code = null)
 * @method Response toUrl(string $message = null, $data = null, $code = null)
 * @method Response return($data = null, string $message = null, $code = null)
 */
abstract class BaseController extends Controller
{
    protected $packageName;

    protected function return($name): Response
    {
        $session = new Session();
        if (!$session->has('admin_id')) {
            $filename = $this->packageName . '/' . strtolower($name) . '.html';
            return $this->render($filename);
        } else {
            return $this->render('login.html');
        }
    }
}
