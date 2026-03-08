<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ParticipantController extends Controller
{
    public function index()
    {
        return view('admin.participant.index');
    }
}
