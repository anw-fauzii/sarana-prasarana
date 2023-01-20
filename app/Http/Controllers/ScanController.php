<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function create($id){
        $barang = Barang::where('qr_code', $id)->firstOrFail();
        return view('barang.scan', compact('barang'));
    }

    public function store(Request $request){
        $nama = BarangKeluar::create(
            [
                'barang_id' => $request->id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan
            ]
        );
        $barang = Barang::findOrfail($request->id);
            $barang->stok -= $request->jumlah;
            $barang->save();
        
        return redirect()->back()->with('sukses','Pengambilan Barang Sudah Dicatat');
    }


}
