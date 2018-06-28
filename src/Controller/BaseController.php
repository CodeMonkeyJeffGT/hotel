<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * 基础类，提供基本方法
 * @method JsonResponse success($data = null, string $message = null, $code = null)
 * @method JsonResponse error($code = null, string $message = null, $data = null)
 * @method JsonResponse toSign(string $message = null, $data = null, $code = null)
 * @method JsonResponse toUrl(string $message = null, $data = null, $code = null)
 * @method JsonResponse return($data = null, string $message = null, $code = null)
 */
abstract class BaseController extends Controller
{
    protected $request;
    protected $session;

    public const OK         = 0;
    public const REDIRECT   = 302;
    public const TO_SIGN    = 403;
    public const ERROR      = 500;

    public const PER_USER           = 0;
    public const PER_RECEPTIONIST   = 1;
    public const PER_ADMIN          = 2;

    private $errMsg = array();
    
    /**
     * 设置 request 和 session
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->session = new Session();
        $this->setErrMsg();
    }

    /**
     * 检查是否登录及是否有权限
     */
    public function checkPermit($permit = null)
    {
        $permit = $permit ?? self::PER_USER;
        if ( ! $this->session->has('hotelUser')) {
            return $this->toSign();
        }
        if ($permit === self::PER_USER) {
            return true;
        }
        $userDb = $this->getDoctrine()->getRepository(User::class);
        if ($userDb->checkPermit($permit)) {
            return true;
        } else {
            return $this->error(static::ERROR, '很抱歉，您没有权限这么做');
        }
    }

    /**
     * 设置错误信息文本
     * 
     * @param array         $errMsg     错误信息文本数组
     */
    protected function setErrMsg($errMsg = array())
    {
        $this->errMsg = array(
            static::OK         => 'OK',
            static::REDIRECT   => '自动跳转中，请稍候',
            static::TO_SIGN    => '请登录',
            static::ERROR      => '出错了',
        ) + $this->errMsg + $errMsg;
    }

    /**
     * 返回格式化数据：请求成功
     * @param mixed         $data       返回数据
     * @param string        $message    提示信息
     * @param mixed         $code       返回类型代码
     * 
     * @return JsonResponse
     */
    protected function success($data = null, string $message = null, $code = null): JsonResponse
    {
        return $this->return($data, $code, $message);
    }

    /**
     * 返回格式化数据：通用失败
     * @param string        $message    提示信息
     * @param mixed         $code       返回类型代码
     * @param mixed         $data       返回数据
     * 
     * @return JsonResponse
     */
    protected function error($code = null, string $message = null, $data = null): JsonResponse
    {
        $code = $code ?? static::ERROR;
        return $this->return($data, $code, $message);
    }

    /**
     * 返回格式化数据：未登录
     * @param string        $message    提示信息
     * @param mixed         $data       返回数据
     * @param mixed         $code       返回类型代码
     * 
     * @return JsonResponse
     */
    protected function toSign(string $message = null, $data = null, $code = null): JsonResponse
    {
        $code = $code ?? static::TO_SIGN;
        return $this->return($data, $code, $message);
    }

    /**
     * 返回格式化数据：跳转到指定url
     * @param string        $message    提示信息
     * @param mixed         $data       返回数据        array('url' => $url);
     * @param mixed         $code       返回类型代码
     * 
     * @return JsonResponse
     */
    protected function toUrl(string $message = null, $data = null, $code = null): JsonResponse
    {
        $code = $code ?? static::REDIRECT;
        return $this->return($data, $code, $message);
    }

    /**
     * 返回格式化数据
     * @param mixed         $data       返回数据
     * @param mixed         $code       返回类型代码
     * @param string        $message    提示信息
     * 
     * @return JsonResponse
     */
    protected function return($data = null, $code = null, string $message = null): JsonResponse
    {
        $code    = $code ?? static::OK;
        $message = $message ?? $this->errMsg[$code];
        return $this->json(array(
            'data' => $data ?? array(),
            'code' => $code,
            'message' => $message,
        ));
    }
}
