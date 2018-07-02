<?php
namespace App\Controller;

use App\Controller\BaseController as Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $rst = json_decode($rst->return, true);
        return $this->return($rst);
    }

    /**
     * 订票
     * 
     * @Route("/scenic/book", name="scenic-book")
     */
    public function book(): JsonResponse
    {
        $soap = new \SoapClient('http://47.93.39.7:8080/SOA/webservice/WebserviceTest?wsdl');
        $sId = $this->request->request->get('sId');
        $count = $this->request->request->get('count');
        $rst = $soap->getById($sId);
        echo '<pre>';
        var_dump($rst);die;
        $rst = json_decode($rst->return, true);
        $tid = $rst['tid'];
        date_default_timezone_set('PRC');
        $now = date('Y-m-d', time());
        $total = $count * $rst['price'];
        $soap->bookTicket($this->session->get('scenicUser'), $count, $tid, $now, $total);
        return $this->return();
    }

    /**
     * 订票列表
     * 
     * @Route("/scenic/mine/list", name="scenic-mine")
     */
    public function mine(): JsonResponse
    {
        $soap = new \SoapClient('http://47.93.39.7:8080/SOA/webservice/WebserviceTest?wsdl');
        $rst = $soap->getAllBookTicket($this->session->get('scenicUser'));
        $rst = json_decode($rst->return, true);
        return $this->return($rst);
    }
}