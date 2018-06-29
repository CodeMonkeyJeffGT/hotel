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
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        try {
            $occupancyRst = $occupancyDb->checkIn($userId, $roomId);
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
        $roomId = $this->request->request->get('roomId');
        $occupancyDb = $this->getDoctrine()->getRepository(Occupancy::class);
        try {
            $occupancyRst = $occupancyDb->checkOut($userId, $roomId);
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
        $occupancyRst = $occupancyDb->countIn($userId, $roomId);
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
        $occupancyRst = $occupancyDb->countOut($userId, $roomId);
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
        $occupancyRst = $occupancyDb->listIn($userId, $roomId);
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
        $occupancyRst = $occupancyDb->listOut($userId, $roomId);
        return $this->success($occupancyRst);
    }
}