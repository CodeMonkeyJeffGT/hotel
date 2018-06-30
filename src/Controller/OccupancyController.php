<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Occupancy;

class OccupancyController extends Controller
{
    public const STATUS_IN = 1;
    public const STATUS_OUT = 2;

    public const ROOM_NOT_EXISTS = 3000;
    public const BOOKED = 3001;
    public const CHECKED = 3002;
    public const ROOM_NOT_YOURS = 3003;
    public const ORDER_NOT_EXISTS = 3004;
    public const ALREADY_OUT = 3005;

    public function __constuct()
    {
        parent::__construct();
        $this->setErrMsg(array(
            static::ROOM_NOT_EXISTS => '房间不存在',
            static::BOOKED => '此房间已被预订',
            static::CHECKED => '此房间已有其他顾客',
            static::ROOM_NOT_YOURS => '您没有权限操作这个房间',
            static::ORDER_NOT_EXISTS => '订单不存在',
            static::ALREADY_OUT => '已退房，无法重复退房',
        ));
    }

    /**
     * 开房登记
     * 
     * @Route("/check/in", name="check-in")
     */
    public function checkIn(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_RECEPTIONIST)) !== true) {
            return $permitRst;
        }
        $userId = $this->session->get('hotelUser');
        $roomId = $this->request->request->get('roomId');
        $guest = $this->request->request->get('guest');
        $inDate = $this->request->request->get('inDate');
        $days = $this->request->request->get('days');
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        try {
            $occupancyRst = $occupancyDb->checkIn($userId, $roomId, $guest, $inDate, $days);
            return $this->success(array(
                'id' => $occupancyRst,
            ));
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }

    /**
     * 退房结账
     * 
     * @Route("/check/out", name="check-out")
     */
    public function checkOut(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_RECEPTIONIST)) !== true) {
            return $permitRst;
        }
        $userId = $this->session->get('hotelUser');
        $occupancyId = $this->request->request->get('occupancyId');
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        try {
            $occupancyRst = $occupancyDb->checkOut($occupancyId);
            return $this->success(array(
                'id' => $occupancyRst,
            ));
        } catch (\Exception $e) {
            throw $e;die;
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }

    public function showIn()
    {
        if (($permitRst = $this->checkPermit(static::PER_RECEPTIONIST)) !== true) {
            return $permitRst;
        }
        
    }

    public function showOut()
    {
        if (($permitRst = $this->checkPermit(static::PER_RECEPTIONIST)) !== true) {
            return $permitRst;
        }

    }

    /**
     * 开房统计
     * 
     * @Route("/count/in", name="count-in")
     */
    public function countIn(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        $occupancyRst = $occupancyDb->countIn();
        return $this->success($occupancyRst);
    }

    /**
     * 退房统计
     * 
     * @Route("/count/out", name="count-out")
     */
    public function countOut(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        $occupancyRst = $occupancyDb->countOut();
        return $this->success($occupancyRst);
    }

    /**
     * 开房统计详情
     * 
     * @Route("/list/in", name="list-in")
     */
    public function listIn(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        $occupancyRst = $occupancyDb->listIn();
        return $this->success($occupancyRst);
    }

    /**
     * 退房统计详情
     * 
     * @Route("/list/out", name="list-out")
     */
    public function listOut(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        $occupancyRst = $occupancyDb->listOut();
        return $this->success($occupancyRst);
    }
}