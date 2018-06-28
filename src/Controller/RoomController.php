<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Room;

class RoomController extends Controller
{
    /**
     * 列出所有房间
     * 
     * @Route("/room/list", name="room-list")
     */
    public function list(): JsonResponse
    {
        $roomDb = $this->getDoctrine()->getRepository(Room::class);
        $roomRst = $roomDb->list();
        return $this->success($roomRst);
    }
}