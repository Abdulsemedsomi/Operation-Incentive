<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Cfrcontroller extends Controller
{
    public function index()
    {
        return view('cfrpage');
    }
    public function conductcfr()
    {
        return view('conductcfr');
    }
    public function actionplanedit()
    {
        return view('actionplanedit');
    }
    public function cfrview()
    {
        return view('cfrview');
    }
    public function editcfrresponse()
    {
        return view('editcfrresponse');
    }
}
