<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PrizeController extends Controller
{
    public function index()
    {
        return view('admin.prize.index');
    }
}
