<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Booking;

class ScenicController extends Controller
{
    /**
     * 景点列表
     * 
     * @Route("/scenic/list", name="scenic-list")
     */
    public function list(): JsonResponse
    {
        $soap = new \SoapClient('http://47.93.39.7:8080/SOA/webservice/WebserviceTest?wsdl');
        $rst = $soap->getAllScenery();
        $this->return($rst);
    }

    /**
     * 订票
     * 
     * @Route("/scenic/book", name="scenic-book")
     */
    public function book(): JsonResponse
    {
        
    }

    /**
     * 订票列表
     * 
     * @Route("/scenic/mine", name="scenic-mine")
     */
    public function mine(): JsonResponse
    {
    }
}