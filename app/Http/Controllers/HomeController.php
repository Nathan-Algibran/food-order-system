<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->take(6)->get(); // ambil 6 menu terbaru

        return view('welcome', compact('menus'));
    }
}