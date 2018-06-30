<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Booking;

class BookingController extends Controller
{
    public const STATUS_BOOKED = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_DONE = 3;

    public const ROOM_NOT_EXISTS = 3000;
    public const BOOKED = 3001;
    public const CHECKED = 3002;
    public const ROOM_NOT_YOURS = 3003;
    public const BOOKING_NOT_EXISTS = 3004;
    public const ALREADY_CANCELED = 3005;
    public const ALREADY_DONE = 3006;

    public function __constuct()
    {
        parent::__construct();
        $this->setErrMsg(array(
            static::ROOM_NOT_EXISTS => '房间不存在',
            static::BOOKED => '此房间已被预订',
            static::CHECKED => '此房间已有其他顾客',
            static::ROOM_NOT_YOURS => '您没有权限操作这个房间',
            static::BOOKING_NOT_EXISTS => '订单不存在',
            static::ALREADY_CANCELED => '该预订已被取消',
            static::ALREADY_DONE => '已入住，无法取消',
        ));
    }

    /**
     * 预订房间
     * 
     * @Route("/book", name="book")
     */
    public function book(): JsonResponse
    {
        if (($permitRst = $this->checkPermit()) !== true) {
            return $permitRst;
        }
        $userId = $this->session->get('hotelUser');
        $roomId = $this->request->request->get('roomId');
        $bookDate = $this->request->request->get('bookDate');
        $days = $this->request->request->get('days');
        if (in_array(null, array($roomId, $bookDate, $days))) {
            return $this->error(static::PARAM_MISS);
        }

        $bookingDb = $this->getDoctrine()->getRepository(Booking::class);
        try {
            $bookingRst = $bookingDb->book($userId, $roomId, $bookDate, $days);
            return $this->success(array(
                'id' => $bookingRst,
            ));
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }

    /**
     * 取消预订
     * 
     * @Route("/unBook", name="unBook")
     */
    public function unBook(): JsonResponse
    {
        if (($permitRst = $this->checkPermit()) !== true) {
            return $permitRst;
        }
        $userId = $this->session->get('hotelUser');
        $bookingId = $this->request->request->get('bookingId');
        $bookingDb = $this->getDoctrine()->getRepository(Booking::class);
        try {
            $bookingRst = $bookingDb->unBook($userId, $bookingId);
            return $this->success();
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                return $this->error();
            }
            return $this->error($e->getCode());
        }
    }

    /**
     * 预订统计
     * 
     * @Route("/count/book", name="count-book")
     */
    public function countBook(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $bookingDb = $this->getDoctrine()->getRepository(Booking::class);
        $bookingRst = $bookingDb->countBook($userId, $roomId);
        return $this->success($bookingRst);
    }

    /**
     * 预订统计详情
     * 
     * @Route("/list/book", name="list-book")
     */
    public function listBook(): JsonResponse
    {
        if (($permitRst = $this->checkPermit(static::PER_ADMIN)) !== true) {
            return $permitRst;
        }
        $bookingDb = $this->getDoctrine()->getRepository(Booking::class);
        $bookingRst = $bookingDb->listBook($userId, $roomId);
        return $this->success($bookingRst);
    }
}