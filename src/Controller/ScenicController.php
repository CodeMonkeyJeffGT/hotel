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
        $sId = $this->request->request->get('sid');
        $count = $this->request->request->get('count');
        $param =  array('sid' => (int)$sId);
        $rst = $soap->__soapCall('getTicket',array('parameters' => $param));
        $rst = json_decode($rst->return, true);
        $tid = $rst['id'];
        $price = $rst['price'];
        date_default_timezone_set('PRC');
        $now = date('Y-m-d', time());
        $total = $count * $rst['price'];
        $soap->bookTicket(array(
            'userid' => (int)$this->session->get('scenicUser'),
            'count' => (int)$count,
            'tid' => (int)$tid,
            'time' => $now,
            'totalprice' => (int)$total
        ));
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
        $rst = $soap->getAllBookTicket(array('userid' => $this->session->get('scenicUser')));
        $rst = json_decode($rst->return, true);
        return $this->return($rst);
    }
}