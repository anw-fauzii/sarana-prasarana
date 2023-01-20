<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','revalidate']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $seragam = Barang::All()->count();
        $hadir = Barang::where('keterangan',1)->count();
        $tidak = Barang::where('keterangan',0)->count();
        $kosong = Barang::where('keterangan', NULL)->count();
        return view('home',compact('hadir','tidak','kosong','seragam'));
    }
    
}
