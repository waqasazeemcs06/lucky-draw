<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DrawController extends Controller
{
    public function index()
    {
        return view('admin.draw.index');
    }

    public function luckDraw()
    {
        return view('admin.draw.lucky-draw');
    }

    public function winners()
    {
        return view('admin.winners.index');
    }
}
