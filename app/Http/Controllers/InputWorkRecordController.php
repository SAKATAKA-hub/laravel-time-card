<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputWorkRecordController extends Controller
{
    /**
     * タイムカードページの表示(index)
    */
    public function index()
    {
        return view('time_card.index');
    }
}
