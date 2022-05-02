<?php

namespace app\controller;

use app\core\Controller;

class DashboardController extends Controller
{
    public function home()
    {
        $this->load('home/dashboard');
    }
}
