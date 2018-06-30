<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImgController extends Controller
{
    /**
     * @Route("/img", name="img")
     */
    public function index()
    {
        $num = Request::createFromGlobals()->query->get('num');
        $this->boot($num);
        die;
    }

    private function boot($num)
    {
        $width = 48;
        $height = 48;
        $image = imagecreate($width, $height);
        imagecolorallocate($image, 66, 139, 202);//填充背景颜色

        $color = imagecolorallocate($image, 255, 255, 255);//验证码颜色
    
        $num = (string)($num);
        $base = 24 - strlen($num) * 4;
        for ($i = 0, $loop = strlen($num); $i < $loop; $i++) {
            imagechar($image, 5, $base + $i * 8, 16, $num[$i], $color);
        }

        header('content-type: image/png');
        imagepng($image);
    }
}
