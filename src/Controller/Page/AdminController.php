<?php

namespace App\Controller\Page;

use App\Controller\Page\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    protected $packageName = 'Admin';

    public function index(): Response
    {
        return $this->return(__FUNCTION__);
    }

    public function in(): Response
    {
        return $this->return(__FUNCTION__);
    }

    public function out(): Response
    {
        return $this->return(__FUNCTION__);
    }

    public function order(): Response
    {
        return $this->return(__FUNCTION__);
    }

    public function room(): Response
    {
        return $this->return(__FUNCTION__);
    }

    public function receptionist(): Response
    {
        return $this->return(__FUNCTION__);
    }

    public function people(): Response
    {
        return $this->return(__FUNCTION__);
    }
}
