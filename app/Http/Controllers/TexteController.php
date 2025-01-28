<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class TexteController extends Controller
{
    public function index(){

        $setting = Setting::first();


        return view('teste.index', [
            'setting' => $setting
        ]);
    }
}
