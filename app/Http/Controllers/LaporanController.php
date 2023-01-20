<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use DataTables;
use Validator;
use Carbon\Carbon;

class LaporanController extends Controller
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

    public function keluar(Request $request){
        if ($request->ajax()) {
            $startDate = $request->mulai;
            $endDate = $request->selesai;
            $data = BarangKeluar::whereBetween('created_at', [$startDate, $endDate])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('barang', function($data){
                    return $data->barang->nama." (".$data->barang->ukuran.")";
                })
                ->addColumn('tanggal', function($data){
                    return $data->created_at->isoFormat('D MMMM Y/hh:mm');
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            $startDate = $request->mulai;
            $endDate = $request->selesai;
            $barang = BarangKeluar::whereBetween('barang_keluar.created_at', [$startDate, $endDate])->get();
        return view('laporan.keluar', compact('barang','startDate','endDate'));
    }

    public function masuk(Request $request){
        if ($request->ajax()) {
            $startDate = $request->mulai;
            $endDate = $request->selesai;
            $data = BarangMasuk::whereBetween('created_at', [$startDate, $endDate])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('barang', function($data){
                    return $data->barang->nama." (".$data->barang->ukuran.")";
                })
                ->addColumn('tanggal', function($data){
                    return $data->created_at->isoFormat('D MMMM Y/hh:mm');
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            $startDate = $request->mulai;
            $endDate = $request->selesai;
            $barang = BarangMasuk::whereBetween('barang_masuk.created_at', [$startDate, $endDate])->get();
        return view('laporan.masuk', compact('barang','startDate','endDate'));
    }
}
